<?php
/**
 * Copyright © Born, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Born\SwitchUser\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Registry;
/**
 * Class ProductActions
 */
class Switchaccount extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        Registry $registry,
        UiComponentFactory $uiComponentFactory,
        Session $customerSession,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->_registry = $registry;
        $this->customerSession = $customerSession;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $customerId = $this->customerSession->getCustomerId();
            $role_id = $this->searchForId($customerId,$dataSource['data']['items']);
            foreach ($dataSource['data']['items'] as &$item) {
                if(($this->customerSession->getCustomerId()!=$item['entity_id'])&&($role_id==0 || $this->customerSession->getFlagData()==1)){
                    $item[$this->getData('name')]['switch'] = [
                        'href' => $this->urlBuilder->getUrl(
                            'born_switchuser/index/index',
                            ['id' => $item['entity_id']]
                        ),
                        'label' => __('Switch User'),
                        'hidden' => false,
                    ];
                }
            }
        }
        return $dataSource;
    }

    function searchForId($id, $array) {
     foreach ($array as $key => $val) {
         if ($val['entity_id'] === $id) {
             return $val['role_id'];
         }
     }
     return null;
 }
 
 
}

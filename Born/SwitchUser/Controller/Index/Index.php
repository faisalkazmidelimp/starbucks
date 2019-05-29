<?php
namespace Born\SwitchUser\Controller\Index;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Action;
class Index extends Action
{

    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
         Context $context,
         RequestInterface $request,
         CustomerRepositoryInterface $customerRepo,
         CustomerFactory $customerFactory,
         Session $customerSession,
         Registry $registry,
         PageFactory $resultPageFactory
    ) {
        $this->customerRepo = $customerRepo;
        $this->customerFactory = $customerFactory;
        $this->customerSession = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->request = $request;
        $this->_registry = $registry;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $subUserId = $this->getRequest()->getParams();
        $this->logoutUser($subUserId);
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('company/users/');
        return $resultRedirect;
    }
    public function logoutUser($subUserId){

        $this->customerSession->setCustomerParentId($this->customerSession->getCustomerId());
        $this->customerSession->logout();
        $this->loginUser($subUserId['id']);
    }
    public function loginUser($subUserId){
        $customer = $this->customerFactory->create()->load($subUserId);
        $this->customerSession->setCustomerAsLoggedIn($customer);
		$this->customerSession->setFlagData(1);
    }
}

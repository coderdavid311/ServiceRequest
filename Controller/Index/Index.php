<?php
namespace Appseconnect\ServiceRequest\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;
use Appseconnect\ServiceRequest\Model\ServiceRequestFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;

class Index extends Action
{
    protected $resultPageFactory;
    protected $customerSession;
    protected $serviceRequestFactory;
    protected $customerRepository;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Session $customerSession,
        ServiceRequestFactory $serviceRequestFactory,
        CustomerRepositoryInterface $customerRepository
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
        $this->serviceRequestFactory = $serviceRequestFactory;
        $this->customerRepository = $customerRepository;
    }

    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return $this->_redirect('customer/account/login');
        }

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPostValue();

            try {
                $serviceRequest = $this->serviceRequestFactory->create();
                $serviceRequest->setCustomerId($this->customerSession->getCustomerId());
                $serviceRequest->setCustomerName($postData['customer_name']);
                $serviceRequest->setCustomerEmail($postData['customer_email']);
                $serviceRequest->setCustomerMobile($postData['customer_mobile']);
                $serviceRequest->setDeviceSerialNumber($postData['device_serial_number']);
                $serviceRequest->setModelNumber($postData['model_number']);
                $serviceRequest->setIssueType($postData['issue_type'] ?? '');
                $serviceRequest->setIssueDescription($postData['issue_description'] ?? '');
                $serviceRequest->setCity($postData['city']);
                $serviceRequest->setState($postData['state']);
                $serviceRequest->setFascCenter($postData['fasc_center']);
                $serviceRequest->setStatus(\Appseconnect\ServiceRequest\Model\ServiceRequest::STATUS_NEW);
                $serviceRequest->setCreatedAt(date('Y-m-d H:i:s'));
                $serviceRequest->setUpdatedAt(date('Y-m-d H:i:s'));

                $serviceRequest->save();

                $this->messageManager->addSuccessMessage(__('Service request submitted successfully.'));
                return $this->_redirect('servicerequest/index/index');

            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Error occurred while submitting service request: %1', $e->getMessage()));
            }
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Service Request'));

        return $resultPage;
    }
}

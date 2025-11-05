<?php
namespace Appseconnect\ServiceRequest\Controller\Adminhtml\Request;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Appseconnect\ServiceRequest\Model\ServiceRequestFactory;
use Magento\Framework\Registry;

class Edit extends Action
{
    protected $resultPageFactory;
    protected $serviceRequestFactory;
    protected $registry;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ServiceRequestFactory $serviceRequestFactory,
        Registry $registry
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->serviceRequestFactory = $serviceRequestFactory;
        $this->registry = $registry;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->serviceRequestFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This service request no longer exists.'));
                return $this->_redirect('*/*/');
            }
        }

        $this->registry->register('service_request_data', $model);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Appseconnect_ServiceRequest::service_request');

        $resultPage->getConfig()->getTitle()->prepend(
            $id ? __('Edit Service Request #%1', $id) : __('New Service Request')
        );

        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Appseconnect_ServiceRequest::service_request');
    }
}

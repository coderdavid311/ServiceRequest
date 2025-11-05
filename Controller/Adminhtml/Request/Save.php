<?php
namespace Appseconnect\ServiceRequest\Controller\Adminhtml\Request;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Appseconnect\ServiceRequest\Model\ServiceRequestFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action
{
    protected $serviceRequestFactory;
    protected $dataPersistor;

    public function __construct(
        Context $context,
        ServiceRequestFactory $serviceRequestFactory,
        DataPersistorInterface $dataPersistor
    ) {
        $this->serviceRequestFactory = $serviceRequestFactory;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $id = $this->getRequest()->getParam('entity_id');

            try {
                $model = $this->serviceRequestFactory->create();

                if ($id) {
                    $model->load($id);
                    if (!$model->getId()) {
                        throw new LocalizedException(__('This service request no longer exists.'));
                    }
                }

                $model->setData($data);
                $model->setUpdatedAt(date('Y-m-d H:i:s'));
                $model->save();

                $this->messageManager->addSuccessMessage(__('Service request has been saved.'));
                $this->dataPersistor->clear('service_request_data');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }

                return $resultRedirect->setPath('*/*/');

            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the service request.'));
            }

            $this->dataPersistor->set('service_request_data', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Appseconnect_ServiceRequest::service_request');
    }
}

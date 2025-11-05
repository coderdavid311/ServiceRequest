<?php
namespace Appseconnect\ServiceRequest\Block\Adminhtml\Request\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    protected $_serviceRequest;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Appseconnect\ServiceRequest\Model\ServiceRequest $serviceRequest,
        array $data = []
    ) {
        $this->_serviceRequest = $serviceRequest;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post'
                ]
            ]
        );

        $form->setHtmlIdPrefix('service_request_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Service Request Information'), 'class' => 'fieldset-wide']
        );

        $model = $this->_coreRegistry->registry('service_request_data');

        if ($model->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        }

        $fieldset->addField(
            'customer_name',
            'text',
            [
                'name' => 'customer_name',
                'label' => __('Customer Name'),
                'title' => __('Customer Name'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'customer_email',
            'text',
            [
                'name' => 'customer_email',
                'label' => __('Customer Email'),
                'title' => __('Customer Email'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'customer_mobile',
            'text',
            [
                'name' => 'customer_mobile',
                'label' => __('Customer Mobile'),
                'title' => __('Customer Mobile'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'device_serial_number',
            'text',
            [
                'name' => 'device_serial_number',
                'label' => __('Device Serial Number'),
                'title' => __('Device Serial Number'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'model_number',
            'text',
            [
                'name' => 'model_number',
                'label' => __('Model Number'),
                'title' => __('Model Number'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'issue_type',
            'select',
            [
                'name' => 'issue_type',
                'label' => __('Issue Type'),
                'title' => __('Issue Type'),
                'required' => false,
                'values' => $this->_serviceRequest->getIssueTypeOptions()
            ]
        );

        $fieldset->addField(
            'issue_description',
            'textarea',
            [
                'name' => 'issue_description',
                'label' => __('Issue Description'),
                'title' => __('Issue Description'),
                'required' => false,
                'rows' => 5,
                'cols' => 30,
            ]
        );

        $fieldset->addField(
            'city',
            'text',
            [
                'name' => 'city',
                'label' => __('City'),
                'title' => __('City'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'state',
            'text',
            [
                'name' => 'state',
                'label' => __('State'),
                'title' => __('State'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'fasc_center',
            'select',
            [
                'name' => 'fasc_center',
                'label' => __('FASC Center'),
                'title' => __('FASC Center'),
                'required' => true,
                'values' => $this->_serviceRequest->getFascCenterOptions()
            ]
        );

        $fieldset->addField(
            'status',
            'select',
            [
                'name' => 'status',
                'label' => __('Status'),
                'title' => __('Status'),
                'required' => true,
                'values' => $this->_serviceRequest->getStatusOptions()
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}

<?php
namespace Appseconnect\ServiceRequest\Block\Adminhtml\Request;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    protected $_coreRegistry = null;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Appseconnect_ServiceRequest';
        $this->_controller = 'adminhtml_request';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Service Request'));
        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ],
            -100
        );
    }

    public function getHeaderText()
    {
        $serviceRequest = $this->_coreRegistry->registry('service_request_data');
        if ($serviceRequest->getId()) {
            return __("Edit Service Request #%1", $this->escapeHtml($serviceRequest->getId()));
        } else {
            return __('New Service Request');
        }
    }

    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('servicerequest/*/save', ['_current' => true, 'back' => 'edit']);
    }
}

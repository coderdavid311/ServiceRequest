<?php
namespace Appseconnect\ServiceRequest\Block\Adminhtml\Request;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $_collectionFactory;
    protected $_serviceRequest;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Appseconnect\ServiceRequest\Model\ResourceModel\ServiceRequest\CollectionFactory $collectionFactory,
        \Appseconnect\ServiceRequest\Model\ServiceRequest $serviceRequest,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_serviceRequest = $serviceRequest;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('serviceRequestGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
    }

    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );

        $this->addColumn(
            'customer_name',
            [
                'header' => __('Customer Name'),
                'index' => 'customer_name',
            ]
        );

        $this->addColumn(
            'customer_email',
            [
                'header' => __('Customer Email'),
                'index' => 'customer_email',
            ]
        );

        $this->addColumn(
            'customer_mobile',
            [
                'header' => __('Customer Mobile'),
                'index' => 'customer_mobile',
            ]
        );

        $this->addColumn(
            'device_serial_number',
            [
                'header' => __('Device Serial Number'),
                'index' => 'device_serial_number',
            ]
        );

        $this->addColumn(
            'model_number',
            [
                'header' => __('Model Number'),
                'index' => 'model_number',
            ]
        );

        $this->addColumn(
            'issue_type',
            [
                'header' => __('Issue Type'),
                'index' => 'issue_type',
                'type' => 'options',
                'options' => $this->_serviceRequest->getIssueTypeOptions()
            ]
        );

        $this->addColumn(
            'fasc_center',
            [
                'header' => __('FASC Center'),
                'index' => 'fasc_center',
            ]
        );

        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' => $this->_serviceRequest->getStatusOptions(),
                'frame_callback' => [$this, 'decorateStatus']
            ]
        );

        $this->addColumn(
            'created_at',
            [
                'header' => __('Created At'),
                'index' => 'created_at',
                'type' => 'datetime',
            ]
        );

        $this->addColumn(
            'action',
            [
                'header' => __('Action'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => 'servicerequest/request/edit'
                        ],
                        'field' => 'id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'entity_id',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );

        return parent::_prepareColumns();
    }

    public function decorateStatus($value, $row, $column, $isExport)
    {
        $status = $row->getStatus();
        $class = '';

        switch ($status) {
            case 'new':
                $class = 'grid-severity-minor';
                break;
            case 'in_progress':
                $class = 'grid-severity-major';
                break;
            case 'completed':
                $class = 'grid-severity-notice';
                break;
            default:
                $class = 'grid-severity-critical';
        }

        return '<span class="' . $class . '"><span>' . $value . '</span></span>';
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('servicerequest/request/edit', ['id' => $row->getId()]);
    }
}

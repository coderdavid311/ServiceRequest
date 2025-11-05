<?php
namespace Appseconnect\ServiceRequest\Block\ServiceRequest;

use Appseconnect\ServiceRequest\Model\ResourceModel\ServiceRequest\CollectionFactory;

class History extends \Magento\Framework\View\Element\Template
{
    protected $collectionFactory;
    protected $customerSession;
    protected $serviceRequest;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        CollectionFactory $collectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Appseconnect\ServiceRequest\Model\ServiceRequest $serviceRequest,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
        $this->customerSession = $customerSession;
        $this->serviceRequest = $serviceRequest;
    }

    public function getServiceRequests()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return [];
        }

        $customerId = $this->customerSession->getCustomerId();
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('customer_id', $customerId);
        $collection->setOrder('created_at', 'DESC');

        return $collection;
    }

    public function getStatusLabel($status)
    {
        $options = $this->serviceRequest->getStatusOptions();
        return isset($options[$status]) ? $options[$status] : __('Unknown');
    }

    public function getStatusColor($status)
    {
        switch ($status) {
            case 'new':
                return 'blue';
            case 'in_progress':
                return 'orange';
            case 'completed':
                return 'green';
            default:
                return 'gray';
        }
    }

    public function getIssueTypeLabel($issueType)
    {
        $options = $this->serviceRequest->getIssueTypeOptions();
        return $issueType && isset($options[$issueType]) ? $options[$issueType] : __('Not Specified');
    }

    public function formatCreatedDate($date)
    {
        return date('d-m-Y H:i', strtotime($date));
    }
}

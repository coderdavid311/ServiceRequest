<?php
namespace Appseconnect\ServiceRequest\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;

class ServiceRequest extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'service_request';

    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';

    protected $_cacheTag = 'service_request';
    protected $_eventPrefix = 'service_request';

    protected function _construct()
    {
        $this->_init('Appseconnect\ServiceRequest\Model\ResourceModel\ServiceRequest');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        return [];
    }

    public function getIssueTypeOptions()
    {
        return [
            'hardware' => __('Hardware'),
            'software' => __('Software'),
            'other' => __('Other')
        ];
    }

    public function getFascCenterOptions()
    {
        return [
            'MQS - Delhi' => __('MQS - Delhi'),
            'MQS - Kolkata' => __('MQS - Kolkata'),
            'MQS - Bengaluru' => __('MQS - Bengaluru'),
            'MQS - Chennai' => __('MQS - Chennai'),
            'MQS - Hyderabad' => __('MQS - Hyderabad'),
            'PMS - Mumbai' => __('PMS - Mumbai')
        ];
    }

    public function getStatusOptions()
    {
        return [
            self::STATUS_NEW => __('New'),
            self::STATUS_IN_PROGRESS => __('In Progress'),
            self::STATUS_COMPLETED => __('Completed')
        ];
    }

    public function getStatusLabel()
    {
        $options = $this->getStatusOptions();
        return isset($options[$this->getStatus()]) ? $options[$this->getStatus()] : __('Unknown');
    }
}

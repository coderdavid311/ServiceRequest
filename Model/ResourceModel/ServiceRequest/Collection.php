<?php
namespace Appseconnect\ServiceRequest\Model\ResourceModel\ServiceRequest;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'service_request_collection';
    protected $_eventObject = 'service_request_collection';

    protected function _construct()
    {
        $this->_init(
            'Appseconnect\ServiceRequest\Model\ServiceRequest',
            'Appseconnect\ServiceRequest\Model\ResourceModel\ServiceRequest'
        );
    }
}

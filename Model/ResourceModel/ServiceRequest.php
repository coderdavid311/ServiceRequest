<?php
namespace Appseconnect\ServiceRequest\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ServiceRequest extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('service_request', 'entity_id');
    }
}

<?php
namespace Appseconnect\ServiceRequest\Block\Customer;

class ServiceRequestTab extends \Magento\Framework\View\Element\Html\Link\Current
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\DefaultPathInterface $defaultPath,
        array $data = []
    ) {
        parent::__construct($context, $defaultPath, $data);
    }

    public function getHref()
    {
        return $this->getUrl('servicerequest/index/history');
    }

    public function getLabel()
    {
        return __('Service Requests');
    }

    public function isCurrent()
    {
        $currentAction = $this->getRequest()->getFullActionName();
        return in_array($currentAction, ['servicerequest_index_history', 'servicerequest_index_index']);
    }
}

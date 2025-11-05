<?php
namespace Appseconnect\ServiceRequest\Block\ServiceRequest;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\AddressFactory;

class Form extends \Magento\Framework\View\Element\Template
{
    protected $customerSession;
    protected $serviceRequest;
    protected $customerRepository;
    protected $addressFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Appseconnect\ServiceRequest\Model\ServiceRequest $serviceRequest,
        CustomerRepositoryInterface $customerRepository,
        AddressFactory $addressFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->serviceRequest = $serviceRequest;
        $this->customerRepository = $customerRepository;
        $this->addressFactory = $addressFactory;
    }

    public function getFormAction()
    {
        return $this->getUrl('servicerequest/index/index');
    }

    public function getCustomerData()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return [];
        }

        try {
            $customerId = $this->customerSession->getCustomerId();
            $customer = $this->customerRepository->getById($customerId);

            // Get customer addresses
            $addresses = $customer->getAddresses();
            $primaryAddress = null;

            if (!empty($addresses)) {
                // Get default billing address
                foreach ($addresses as $address) {
                    if ($address->isDefaultBilling()) {
                        $primaryAddress = $address;
                        break;
                    }
                }

                // If no default billing, use first address
                if (!$primaryAddress && !empty($addresses)) {
                    $primaryAddress = $addresses[0];
                }
            }

            $customerData = [
                'name' => $customer->getFirstname() . ' ' . $customer->getLastname(),
                'email' => $customer->getEmail(),
                'mobile' => '',
                'city' => '',
                'state' => ''
            ];

            // Get mobile number from customer attributes
            $mobile = $customer->getCustomAttribute('telephone') ?
                $customer->getCustomAttribute('telephone')->getValue() :
                ($customer->getCustomAttribute('mobile') ?
                    $customer->getCustomAttribute('mobile')->getValue() : '');

            // If we have a primary address, get city and state from there
            if ($primaryAddress) {
                $customerData['mobile'] = $mobile ?: ($primaryAddress->getTelephone() ?: '');
                $customerData['city'] = $primaryAddress->getCity() ?: '';

                // Get region (state)
                $region = $primaryAddress->getRegion();
                if ($region) {
                    $customerData['state'] = is_object($region) ? $region->getRegion() : $region;
                } else {
                    $customerData['state'] = $primaryAddress->getRegion() ?: '';
                }
            } else {
                // Fallback: try to get from customer attributes directly
                $customerData['mobile'] = $mobile;
                $customerData['city'] = $customer->getCustomAttribute('city') ?
                    $customer->getCustomAttribute('city')->getValue() : '';
                $customerData['state'] = $customer->getCustomAttribute('region') ?
                    $customer->getCustomAttribute('region')->getValue() : '';
            }

            return $customerData;

        } catch (\Exception $e) {
            // Log error and return empty data
            // You might want to add proper logging here
            return [
                'name' => '',
                'email' => '',
                'mobile' => '',
                'city' => '',
                'state' => ''
            ];
        }
    }

    public function getIssueTypeOptions()
    {
        return $this->serviceRequest->getIssueTypeOptions();
    }

    public function getFascCenterOptions()
    {
        return $this->serviceRequest->getFascCenterOptions();
    }
}

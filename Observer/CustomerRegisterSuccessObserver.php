<?php


namespace Alan\CustomCustomerRegistration\Observer;


use Alan\CustomCustomerRegistration\Model\EmailSender;
use Alan\CustomCustomerRegistration\Model\Logger\Logger;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class CustomerRegisterSuccessObserver implements \Magento\Framework\Event\ObserverInterface
{
    /** @var CustomerRepositoryInterface */
    protected $customerRepository;
    /** @var Logger $logger */
    protected $logger;
    /** @var EmailSender $emailSender */
    protected $emailSender;
    /** @var TimezoneInterface $timezone */
    protected $timezone;

    /**
     * CustomerRegisterSuccessObserver constructor.
     * @param CustomerRepositoryInterface $customerRepository
     * @param Logger $logger
     * @param EmailSender $emailSender
     * @param TimezoneInterface $timezone
     */
    public function __construct(CustomerRepositoryInterface $customerRepository, Logger $logger, EmailSender $emailSender, TimezoneInterface $timezone)
    {
        $this->customerRepository = $customerRepository;
        $this->logger = $logger;
        $this->emailSender = $emailSender;
        $this->timezone = $timezone;
    }


    public function execute(Observer $observer)
    {
        // Validate firstname
        /** @var CustomerInterface $customer */
        $customer = $observer->getData('customer');

        $customer->setFirstname(str_replace(' ', '', $customer->getFirstname()));

        $this->customerRepository->save($customer);

        // Log customer data to file

        $this->logger->info('Customer Data: ',[
            $this->timezone->date()->format('Y-m-d H:i:s'),
            $customer->getFirstname(),
            $customer->getLastname(),
            $customer->getEmail()
        ]);

        // Send email

        $this->emailSender->sendCustomerDetailEmail($customer);
    }
}

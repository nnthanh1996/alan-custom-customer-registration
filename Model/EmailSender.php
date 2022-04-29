<?php


namespace Alan\CustomCustomerRegistration\Model;


use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class EmailSender
{

    const EMAIL_TEMPLATE_ID = 'customer_registration_data_email';

    /** @var TransportBuilder $transportBuilder */
    protected $transportBuilder;
    /** @var StoreManagerInterface $storeManager */
    protected $storeManager;
    /** @var StateInterface $inlineTranslation */
    protected $inlineTranslation;
    /** @var LoggerInterface $logger */
    protected $logger;
    /**
     * @var ConfigProvider
     */
    protected $configProvider;

    /**
     * EmailSender constructor.
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param LoggerInterface $logger
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,
        LoggerInterface $logger,
        ConfigProvider $configProvider
    )
    {
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->logger = $logger;
        $this->configProvider = $configProvider;
    }

    /** Sending customer detail email
     * @param CustomerInterface $customer
     */
    public function sendCustomerDetailEmail($customer) {

        $templateId = self::EMAIL_TEMPLATE_ID;
        $fromEmail = $this->configProvider->getFromEmailConfig();
        $fromName = $this->configProvider->getFromNameConfig();
        $toEmail = $this->configProvider->getCustomerSupportEmailConfig();

        try {

            $templateVars = [
                'customerFirstName' => $customer->getFirstname(),
                'customerLastName' => $customer->getLastname(),
                'customerEmail' => $customer->getEmail()
            ];

            $storeId = $this->storeManager->getStore()->getId();

            $from = ['email' => $fromEmail, 'name' => $fromName];

            $this->inlineTranslation->suspend();

            $templateOptions = [
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $storeId
            ];
            $transport = $this->transportBuilder->setTemplateIdentifier($templateId)
                ->setFromByScope($from, \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars($templateVars)
                ->addTo($toEmail)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }

    }

}

<?php


namespace Alan\CustomCustomerRegistration\Model;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

class ConfigProvider
{


    const CUSTOMER_SUPPORT_EMAIL_CONFIG_PATH = 'trans_email/ident_support/email';

    const FROM_EMAIL_CONFIG_PATH = 'customer/email_registration_information/from_email';

    const FROM_NAME_CONFIG_PATH = 'customer/email_registration_information/from_name';

    /** @var ScopeConfigInterface $scopeConfig */
    protected $scopeConfig;
    /** @var StoreManagerInterface $storeManager */
    protected $storeManager;

    /**
     * ConfigProvider constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(ScopeConfigInterface $scopeConfig, StoreManagerInterface $storeManager)
    {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    public function getCustomerSupportEmailConfig(){
        return $this->scopeConfig->getValue(
            self::CUSTOMER_SUPPORT_EMAIL_CONFIG_PATH,
            'stores',
            $this->storeManager->getStore()->getId()
        );
    }

    public function getFromEmailConfig(){
        return $this->scopeConfig->getValue(
            self::FROM_EMAIL_CONFIG_PATH,
            'stores',
            $this->storeManager->getStore()->getId()
        );

    }

    public function getFromNameConfig(){
        return $this->scopeConfig->getValue(
            self::FROM_NAME_CONFIG_PATH,
            'stores',
            $this->storeManager->getStore()->getId()
        );

    }
}

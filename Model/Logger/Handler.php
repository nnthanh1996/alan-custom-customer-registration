<?php


namespace Alan\CustomCustomerRegistration\Model\Logger;


class Handler extends \Magento\Framework\Logger\Handler\Base
{

    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/customer_data.log';

}

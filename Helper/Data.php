<?php
/**
 * Magezon
 *
 * This source file is subject to the Magezon Software License, which is available at https://magezon.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to https://www.magezon.com for more information.
 *
 * @category  Magezon
 * @author    LeeNguyen(chiennq@magezon.com)
 * @package   Magezon_TransferOrder
 * @copyright Copyright (C) 2023 Magezon (https://magezon.com)
 */

namespace Magezon\TransferOrder\Helper;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Area;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Path config of module
     */
    const MODULE_CONFIG_PATH = 'mgz_transfer_order';

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\User\Model\UserFactory 
     */
    protected $userFactory;

    /**
     * @var \Magento\Backend\Model\Auth\Session 
     */
    protected $authSession;

    /**
     * @var \Magento\Backend\App\ConfigInterface
     */
    protected $backendConfig;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @var array
     */
    protected $isArea = [];


    /**
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\User\Model\UserFactory $userFactory
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param \Magento\Backend\App\ConfigInterface $backendConfig
     * @param \Magento\Framework\App\State $state
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\User\Model\UserFactory $userFactory,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Backend\App\ConfigInterface $backendConfig,
        \Magento\Framework\App\State $state
    ) {
        $this->customerFactory = $customerFactory;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->userFactory = $userFactory;
        $this->authSession = $authSession;
        $this->backendConfig = $backendConfig;
        $this->state = $state;
        parent::__construct($context);
    }

    /**
     * @param $storeId
     * @return array|mixed
     */
    public function isEnabled($storeId = null)
    {
        return $this->getConfigGeneral('enabled', $storeId);
    }

    /**
     * @param string $code
     * @param $storeId
     * @return array|mixed
     */
    public function getConfigGeneral(string $code = '', $storeId = null)
    {
        $code = ($code !== '') ? '/' . $code : '';

        return $this->getConfigValue(static::MODULE_CONFIG_PATH . '/general' . $code, $storeId);
    }

    /**
     * @param $field
     * @param $scopeValue
     * @param string $scopeType
     * @return mixed
     */
    public function getConfigValue($field, $scopeValue = null, string $scopeType = ScopeInterface::SCOPE_STORE)
    {
        if ($scopeValue === null && !$this->isArea()) {
            return $this->backendConfig->getValue($field);
        }

        return $this->scopeConfig->getValue($field, $scopeType, $scopeValue);
    }

    /**
     * @param string $area
     * @return bool|mixed
     */
    public function isArea(string $area = Area::AREA_FRONTEND)
    {
        if (!isset($this->isArea[$area])) {
            try {
                $this->isArea[$area] = ($this->state->getAreaCode() == $area);
            } catch (\Exception $e) {
                $this->isArea[$area] = false;
            }
        }

        return $this->isArea[$area];
    }

    /**
     * @return mixed
     */
    public function getAdminUserId()
    {
        return $this->authSession->getUser()->getId();
    }

    /**
     * @param $value
     * @return \Magento\Customer\Model\Customer
     * @throws NoSuchEntityException
     */
    public function getCustomer($value)
    {
        try {
            $websiteId = $this->storeManager->getStore()->getWebsiteId();
            $customer = $this->customerFactory->create()->setWebsiteId($websiteId);
            if (is_numeric($value)) {
                $customer->getResource()->load($customer, $value);
            } else {
                $customer->getResource()->loadByEmail($customer, $value);
            }
        } catch (NoSuchEntityException $e) {
            $this->logger->critical($e->getMessage());
            throw new NoSuchEntityException(__('Customer with ID "%1" does not exist.', $customer->getId()));
        }

        return $customer;
    }

    /**
     * @param $value
     * @return string
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomerName($value)
    {
        return $this->getCustomer($value)->getName();
    }

    /**
     * @param $value
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getCustomerId($value)
    {
        return $this->getCustomer($value)->getId();
    }

    /**
     * @param int $userId
     * @return mixed|string|null
     * @throws NoSuchEntityException
     */
    public function getAdminUserName($userId)
    {
        $adminUser = $this->userFactory->create()->load($userId);
        if (!$adminUser->getId()) {
            throw new NoSuchEntityException(__('Admin user with ID "%1" does not exist.', $userId));
        }
        return $adminUser->getUserName();
    }
}
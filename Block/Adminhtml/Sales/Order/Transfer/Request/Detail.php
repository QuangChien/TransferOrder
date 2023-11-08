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

namespace Magezon\TransferOrder\Block\Adminhtml\Sales\Order\Transfer\Request;

use  Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Json\Helper\Data as JsonHelper;

class Detail extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magento\Sales\Model\Order 
     */
    protected $order;

    /**
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magezon\TransferOrder\Helper\Data
     */
    protected $helperData;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Sales\Model\Order $order
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param \Magento\Framework\Registry $registry
     * @param \Magezon\TransferOrder\Helper\Data $helperData
     * @param array $data
     * @param JsonHelper|null $jsonHelper
     * @param DirectoryHelper|null $directoryHelper
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Sales\Model\Order $order,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Framework\Registry $registry,
        \Magezon\TransferOrder\Helper\Data $helperData,
        array $data = [],
        ?JsonHelper $jsonHelper = null,
        ?DirectoryHelper $directoryHelper = null
    )
    {
        $this->order = $order;
        $this->groupRepository = $groupRepository;
        $this->registry = $registry;
        $this->helperData = $helperData;
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
    }

    /**
     * @return \Magento\Sales\Model\Order
     * @throws NoSuchEntityException
     */
    public function getOrder()
    {
        try {
            return $this->order->loadByIncrementId($this->getOrderIncrement());
        } catch (LocalizedException $e) {
            throw new LocalizedException(__('We can\'t get the order instance right now.'));
        }
    }

    /**
     * @return mixed|null
     * @throws LocalizedException
     */
    public function getTransferRequest()
    {
        if ($this->registry->registry('current_transfer_request')) {
            return $this->registry->registry('current_transfer_request');
        }
        throw new LocalizedException(__('We can\'t get the transfer order request instance right now.'));
    }

    /**
     * @return mixed
     * @throws LocalizedException
     */
    public function getOrderIncrement()
    {
        return $this->getTransferRequest()->getOrderIncrementId();
    }

    /**
     * @return bool
     */
    public function isSingleStoreMode()
    {
        return $this->_storeManager->isSingleStoreMode();
    }

    /**
     * @return bool
     * @throws NoSuchEntityException
     */
    public function shouldDisplayCustomerIp()
    {
        return !$this->_scopeConfig->isSetFlag(
            'sales/general/hide_customer_ip',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getOrder()->getStoreId()
        );
    }

    /**
     * @param $orderId
     * @return string
     */
    public function getViewUrl($orderId)
    {
        return $this->getUrl('*/*/view', ['order_id' => $orderId]);
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getCustomerViewUrl()
    {
        if ($this->getOrder()->getCustomerIsGuest() || !$this->getOrder()->getCustomerId()) {
            return '';
        }

        return $this->getUrl('customer/index/edit', ['id' => $this->getOrder()->getCustomerId()]);
    }

    /**
     * @return string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCustomerToViewUrl()
    {
        return $this->getUrl('customer/index/edit', ['id' => $this->getCustomer()->getId()]);
    }
    /**
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getOrderStoreName()
    {
        if ($this->getOrder()) {
            $storeId = $this->getOrder()->getStoreId();
            if ($storeId === null) {
                $deleted = __(' [deleted]');
                return nl2br($this->getOrder()->getStoreName()) . $deleted;
            }
            $store = $this->_storeManager->getStore($storeId);
            $name = [$store->getWebsite()->getName(), $store->getGroup()->getName(), $store->getName()];
            return implode('<br/>', $name);
        }

        return null;
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getCustomerGroupName()
    {
        if ($this->getOrder()) {
            $customerGroupId = $this->getOrder()->getCustomerGroupId();
            try {
                if ($customerGroupId !== null) {
                    return $this->groupRepository->getById($customerGroupId)->getCode();
                }
            } catch (NoSuchEntityException $e) {
                return '';
            }
        }

        return '';
    }

    /**
     * @return string|void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCustomerGroupTo()
    {
        $customerGroupId = $this->getCustomer()->getGroupId();
        try {
            if ($customerGroupId !== null) {
                return $this->groupRepository->getById($customerGroupId)->getCode();
            }
        } catch (NoSuchEntityException $e) {
            return '';
        }
    }

    /**
     * @param $createdAt
     * @return \DateTime
     * @throws \Exception
     */
    public function getOrderAdminDate($createdAt)
    {
        return $this->_localeDate->date(new \DateTime($createdAt));
    }

    /**
     * @return mixed
     * @throws LocalizedException
     */
    public function getTransferRequestStatus()
    {
        return $this->getTransferRequest()->getStatus();
    }

    /**
     * @return \Magento\Customer\Model\Customer
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCustomer()
    {
        return $this->helperData->getCustomer($this->getTransferRequest()->getCustomerToEmail());
    }

    /**
     * @return mixed|string|null
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getAdminUserNameModify()
    {
        $adminUserId = $this->getTransferRequest()->getAdminUserModifyId();
        if(!$adminUserId) return __('No update yet');
        return $this->helperData->getAdminUserName($adminUserId);
    }
}

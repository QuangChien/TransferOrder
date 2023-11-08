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

namespace Magezon\TransferOrder\Block\Sales\Order\Transfer;

class Request extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magezon\TransferOrder\Model\ResourceModel\TransferRequest\CollectionFactory
     */
    protected $requestCollectionFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magezon\TransferOrder\Model\ResourceModel\TransferRequest\Collection
     */
    protected $orderTransferRequests;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magezon\TransferOrder\Model\ResourceModel\TransferRequest\CollectionFactory $requestCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magezon\TransferOrder\Model\ResourceModel\TransferRequest\CollectionFactory $requestCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    )
    {
        $this->requestCollectionFactory = $requestCollectionFactory;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * @return $this|Request
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getOrderTransferRequests()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'sales.order.transfer.request.pager'
            )->setCollection(
                $this->getOrderTransferRequests()
            );
            $this->setChild('pager', $pager);
            $this->getOrderTransferRequests()->load();
        }
        return $this;
    }

    /**
     * @return false|\Magezon\TransferOrder\Model\ResourceModel\TransferRequest\Collection
     */
    public function getOrderTransferRequests()
    {
        if (!$this->orderTransferRequests) {
            $this->orderTransferRequests = $this->requestCollectionFactory->create()
                ->addFieldToFilter('customer_from_id', $this->_customerSession->getId())
                ->addFieldToSelect(
                ['request_id', 'customer_to_email', 'order_increment_id', 'status', 'order_id', 'created_at']
            )->setOrder(
                'created_at',
                'desc'
            );
        }
        return $this->orderTransferRequests;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @param object $transferRequest
     * @return string
     */
    public function getViewUrl($transferRequest)
    {
        return $this->getUrl('sales/order/view', ['order_id' => (int)$transferRequest->getOrderId()]);
    }
}
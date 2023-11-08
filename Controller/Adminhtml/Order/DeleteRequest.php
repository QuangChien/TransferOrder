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

namespace Magezon\TransferOrder\Controller\Adminhtml\Order;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class DeleteRequest extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * @var \Magezon\TransferOrder\Model\ResourceModel\TransferRequest\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magezon\TransferOrder\Helper\Data
     */
    protected $helperData;

    /**
     * @param Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Magezon\TransferOrder\Model\ResourceModel\TransferRequest\CollectionFactory $collectionFactory
     * @param \Magezon\TransferOrder\Helper\Data $helperData
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magezon\TransferOrder\Model\ResourceModel\TransferRequest\CollectionFactory $collectionFactory,
        \Magezon\TransferOrder\Helper\Data $helperData

    )
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->helperData = $helperData;
        parent::__construct($context);
    }

    /**
     * @return Redirect|ResponseInterface|ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        if(!$this->helperData->isEnabled()) return $this->_response->setRedirect($this->_redirect->getRefererUrl());
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $item) {
            $item->delete();
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/transferrequest');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magezon_TransferOrder::transfer_order_requests_delete');
    }
}
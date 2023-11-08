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

use Magento\Backend\App\Action;

class DetailRequest extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     *
     */
    protected $resultPageFactory;

    /**
     * @var \Magezon\TransferOrder\Model\TransferRequest
     */
    protected $transferRequest;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magezon\TransferOrder\Helper\Data
     */
    protected $helperData;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magezon\TransferOrder\Model\TransferRequest $transferRequest
     * @param \Magento\Framework\Registry $registry
     * @param \Magezon\TransferOrder\Helper\Data $helperData
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magezon\TransferOrder\Model\TransferRequest $transferRequest,
        \Magento\Framework\Registry $registry,
        \Magezon\TransferOrder\Helper\Data $helperData
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->transferRequest = $transferRequest;
        $this->registry = $registry;
        $this->helperData = $helperData;
    }

    public function execute()
    {
        if(!$this->helperData->isEnabled()) return $this->_response->setRedirect($this->_redirect->getRefererUrl());
        $requestId = $this->_request->getParam('request_id');
        $transferRequest = $this->transferRequest->load($requestId);
        if(!$requestId || !$transferRequest->getId()) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $this->messageManager->addErrorMessage(__('Transfer order request does not exist.'));
            return $resultRedirect->setPath('*/*/transferrequest');
        }
        $this->registry->register('current_transfer_request', $transferRequest);
        $resultPage = $this->resultPageFactory->create();
        $this->_setActiveMenu("Magento_Sales::sales");
        $resultPage->getConfig()->getTitle()->prepend(__('Transfer Order Requests Detail'));
        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magezon_TransferOrder::transfer_order_requests_view');
    }
}

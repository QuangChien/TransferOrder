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
use Magezon\TransferOrder\Model\Config\RequestStatus;

class ApproveRequest extends \Magento\Backend\App\Action
{
    /**
     * @var \Magezon\TransferOrder\Model\TransferRequestFactory
     */
    protected $transferRequestFactory;

    /**
     * @var \Magezon\TransferOrder\Helper\Data
     */
    protected $helperData;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magezon\TransferOrder\Model\TransferRequestFactory $transferRequestFactory
     * @param \Magezon\TransferOrder\Helper\Data $helperData
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magezon\TransferOrder\Model\TransferRequestFactory $transferRequestFactory,
        \Magezon\TransferOrder\Helper\Data $helperData
    )
    {
        $this->transferRequestFactory = $transferRequestFactory;
        $this->helperData = $helperData;
        parent::__construct($context);
    }

    public function execute()
    {
        if(!$this->helperData->isEnabled()) return $this->_response->setRedirect($this->_redirect->getRefererUrl());
        $requestId = $this->_request->getParam('request_id');
        $transferRequest = $this->transferRequestFactory->create()->load($requestId);
        $resultRedirect = $this->resultRedirectFactory->create();
        if(!$requestId || !$transferRequest->getId() || $transferRequest->getStatus() == RequestStatus::CANCELED_STATUS) {
            if ($transferRequest->getStatus() == RequestStatus::CANCELED_STATUS) $this->messageManager->addErrorMessage(__('You cannot approve a cancel transfer order request.'));
            else $this->messageManager->addErrorMessage(__('Transfer order request does not exist.'));
            return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        }
        $transferRequest->setStatus(RequestStatus::APPROVED_STATUS);
        $transferRequest->setAdminUserModifyId($this->helperData->getAdminUserId());
        $transferRequest->save();
        $this->messageManager->addSuccessMessage(__('You have successfully approved a transfer order request.'));

        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magezon_TransferOrder::transfer_order_requests_approved');
    }
}
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

class TransferRequest extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     *
     */
    protected $resultPageFactory;

    /**
     * @var \Magezon\TransferOrder\Helper\Data
     */
    protected $helperData;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magezon\TransferOrder\Helper\Data $helperData
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magezon\TransferOrder\Helper\Data $helperData
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->helperData = $helperData;
    }

    public function execute()
    {
        if(!$this->helperData->isEnabled()) return $this->_response->setRedirect($this->_redirect->getRefererUrl());
        $resultPage = $this->resultPageFactory->create();
        $this->_setActiveMenu("Magento_Sales::sales");
        $resultPage->getConfig()->getTitle()->prepend(__('Transfer Order Requests'));
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

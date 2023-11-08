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

namespace Magezon\TransferOrder\Controller\Order;


class TransferRequest extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magezon\TransferOrder\Helper\Data
     */
    protected $helperData;

    /**
     * @var \Magento\Customer\Model\Url
     */
    protected $customerUrl;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param \Magezon\TransferOrder\Helper\Data $helperData
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Url $customerUrl,
        \Magezon\TransferOrder\Helper\Data $helperData
    )
    {
        $this->customerSession = $customerSession;
        $this->customerUrl = $customerUrl;
        $this->helperData = $helperData;
        parent::__construct($context);
    }

    public function execute()
    {
        if(!$this->helperData->isEnabled()) return $this->_response->setRedirect('/');
        elseif (!($this->customerSession->isLoggedIn())) return $this->_response->setRedirect($this->customerUrl->getLoginUrl());
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
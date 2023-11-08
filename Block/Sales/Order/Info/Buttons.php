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

namespace Magezon\TransferOrder\Block\Sales\Order\Info;

class Buttons extends \Magento\Sales\Block\Order\Info\Buttons
{
    /**
     * @var string
     */
    protected $_template = 'Magezon_TransferOrder::sales/order/info/buttons.phtml';

    /**
     * @var \Magezon\TransferOrder\Helper\Data
     */
    protected $helperData;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magezon\TransferOrder\Helper\Data $helperData
     * @param array $data\
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magezon\TransferOrder\Helper\Data $helperData,
        array $data = []
    )
    {
        $this->helperData = $helperData;
        parent::__construct($context, $registry, $httpContext, $data);
    }

    /**
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('sales/order/transfer', ['_secure' => true]);
    }

    /**
     * @return array|mixed
     */
    public function isEnabled()
    {
        return $this->helperData->isEnabled();
    }
}

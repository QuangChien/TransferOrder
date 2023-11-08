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

namespace Magezon\TransferOrder\Block\Adminhtml\Sales\Order\Transfer\Buttons;

use Magento\Framework\Exception\LocalizedException;
use Magezon\TransferOrder\Model\Config\RequestStatus;

class Generic implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    /**
     * @var \Magento\Framework\View\Element\UiComponent\Context
     */
    protected $context;

    /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    protected $_authorization;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrl($route, $params);
    }
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [];
    }

    /**
     * @param \Magento\Framework\View\Element\UiComponent\Context $context
     * @param \Magento\Framework\AuthorizationInterface $authorization
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\Context $context,
        \Magento\Framework\AuthorizationInterface $authorization,
        \Magento\Framework\Registry $registry
    ) {
        $this->context = $context;
        $this->_authorization = $authorization;
        $this->registry = $registry;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * @param  array $params
     * @return array
     */
    public function getButtonAttribute($params = [])
    {
        $attributes = [
            'mage-init' => [
                'Magento_Ui/js/form/button-adapter' => [
                    'actions' => [
                        [
                            'targetName' => 'sales_order_transfer_request_form.sales_order_transfer_request_form',
                            'actionName' => 'save',
                            'params' => $params
                        ]
                    ]
                ]
            ]
        ];

        return $attributes;
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
     * @param int $status
     * @return bool
     * @throws LocalizedException
     */
    public function isShowButton(int $status)
    {
        if($this->getTransferRequest()->getStatus() == RequestStatus::APPROVED_STATUS) return false;
        elseif ($this->getTransferRequest()->getStatus() == RequestStatus::CANCELED_STATUS) return false;
        else return true;
    }
}

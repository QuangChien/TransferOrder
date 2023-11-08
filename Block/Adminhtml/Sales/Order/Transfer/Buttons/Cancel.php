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
use Magezon\TransferOrder\Model\Config\RequestStatus;

class Cancel extends Generic
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->_isAllowedAction('Magezon_TransferOrder::transfer_order_requests_cancel') && $this->isShowButton(RequestStatus::CANCELED_STATUS)) {
            $data = [
                'label'    => __('Cancel'),
                'class'    => 'cancel',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want cancel this transfer order request?'
                    ) . '\', \'' . $this->getUrl('*/*/cancelrequest', ['request_id' => $this->context->getRequestParam('request_id')]) . '\')',
                'sort_order' => 20
            ];
        }
        return $data;
    }
}
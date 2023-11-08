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

class Approved extends Generic
{
    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->_isAllowedAction('Magezon_TransferOrder::transfer_order_requests_approve') && $this->isShowButton(RequestStatus::APPROVED_STATUS)) {
            $data = [
                'label'    => __('Approve'),
                'class'    => 'approve primary',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want approve this transfer order request?'
                    ) . '\', \'' . $this->getUrl('*/*/approverequest', ['request_id' => $this->context->getRequestParam('request_id')]) . '\')',
                'sort_order' => 10
            ];
        }
        return $data;
    }
}
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

namespace Magezon\TransferOrder\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class TransferRequest extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'mgz_transfer_order_request_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('mgz_transfer_order_request', 'request_id');
        $this->_useIsObjectNew = true;
    }
}

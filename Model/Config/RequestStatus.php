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

namespace Magezon\TransferOrder\Model\Config;

class RequestStatus
{
    /**
     * Pending status
     */
    const PENDING_STATUS = 0;

    /**
     * Approved status
     */
    const APPROVED_STATUS = 1;

    /**
     * Canceled status
     */
    const CANCELED_STATUS = 2;

    /**
     * Pending text
     */
    const PENDING_TEXT = 'Pending';

    /**
     * Approved text
     */
    const APPROVED_TEXT = 'Approved';

    /**
     * Canceled text
     */
    const CANCELED_TEXT = 'Canceled';
}
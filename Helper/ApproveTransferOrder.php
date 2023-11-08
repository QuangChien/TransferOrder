<?php

namespace Magezon\TransferOrder\Helper;

class ApproveTransferOrder extends  \Magento\Framework\App\Helper\AbstractHelper{

    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    )
    {
        parent::__construct($context);
    }
}
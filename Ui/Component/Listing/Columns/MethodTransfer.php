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

namespace Magezon\TransferOrder\Ui\Component\Listing\Columns;

use Magento\Framework\Exception\LocalizedException;

class MethodTransfer extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Transfer request by customer
     */
    const TRANSFER_REQUEST_BY_CUSTOMER = 1;

    /**
     * Transfer request by admin
     */
    const TRANSFER_REQUEST_BY_ADMIN = 2;
    /**
     * @var \Magento\Framework\UrlInterface|UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     * @throws LocalizedException
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['request_id'])) {
                    $item[$this->getData('name')] = ($item['method_transfer'] == self::TRANSFER_REQUEST_BY_CUSTOMER) ? __('Customer')
                        : (($item['method_transfer'] == self::TRANSFER_REQUEST_BY_ADMIN) ? __('Admin') : '');
                }
            }
        }
        return $dataSource;
    }
}

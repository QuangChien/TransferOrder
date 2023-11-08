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

class CustomerName extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Customer from id filed name
     */
    const CUSTOMER_FROM_ID_FIELD_NAME = 'customer_from_id';

    /**
     * @var \Magento\Framework\UrlInterface|UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magezon\TransferOrder\Helper\Data
     */
    protected $helperData;

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magezon\TransferOrder\Helper\Data $helperData
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magezon\TransferOrder\Helper\Data $helperData,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->helperData = $helperData;
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
                    $url = $this->urlBuilder->getUrl(
                        'customer/index/edit',
                        [
                            'id' => $this->getData('name') == self::CUSTOMER_FROM_ID_FIELD_NAME ? $item['customer_from_id'] : $this->helperData->getCustomerId($item['customer_to_email'])
                        ]
                    );
                    $item[$this->getData('name')] = "<a href='" . $url . "'>" .
                        $this->helperData->getCustomerName($this->getData('name') == self::CUSTOMER_FROM_ID_FIELD_NAME ? $item['customer_from_id'] : $item['customer_to_email']) . '</a>';
                }
            }
        }
        return $dataSource;
    }
}

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
use Magezon\TransferOrder\Model\Config\RequestStatus;

class Status extends \Magento\Ui\Component\Listing\Columns\Column
{
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
                    $statusText = ($item['status'] == RequestStatus::PENDING_STATUS) ? RequestStatus::PENDING_TEXT
                        : (($item['status'] == RequestStatus::APPROVED_STATUS) ? RequestStatus::APPROVED_TEXT
                            : (($item['status'] == RequestStatus::CANCELED_STATUS) ? RequestStatus::CANCELED_TEXT : ''));
                    $item[$this->getData('name')] = "<span class='transfer-request-status " . strtolower($statusText) . "'>" . __($statusText) . "</span>";
                }
            }
        }
        return $dataSource;
    }
}

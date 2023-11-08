<?php

namespace Magezon\TransferOrder\Plugin\Sales\Block\Adminhtml\Order;

use Magento\Framework\UrlInterface;
use Magento\Sales\Block\Adminhtml\Order\View;

class ViewPlugin
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param UrlInterface $url
     */
    public function __construct(
        UrlInterface $url
    ) {
        $this->urlBuilder = $url;
    }

    public function beforeSetLayout(View $view)
    {
        $view->addButton(
            'transfer_order',
            [
                'label' => __('Transfer Order'),
                'class' => 'primary',
                'data_attribute' => [
                    'mage-init' => [
                        'Magento_Ui/js/form/button-adapter' => [
                            'actions' => [
                                [
                                    'targetName' => 'sales_order_view.sales_order_view.show_customer_modal',
                                    'actionName' => 'toggleModal'
                                ],
                                [
                                    'targetName' => 'sales_order_view.sales_order_view.show_customer_modal.customer_listing',
                                    'actionName' => 'render'
                                ]
                            ]
                        ]
                    ]
                ],
                'on_click'   => ''
            ]
        );
    }
}
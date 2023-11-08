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

namespace Magezon\TransferOrder\Controller\Order;
use Magento\Framework\Exception\LocalizedException;

class Transfer extends \Magento\Framework\App\Action\Action
{
    /**
     * status to redirect url when transfer order successfully
     */
    const TRANSFER_SUCCESS_TYPE = 'success';

    /**
     * @var \Magezon\TransferOrder\Model\TransferRequestFactory
     */
    protected $transferRequestFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magezon\TransferOrder\Model\TransferRequestFactory $transferRequestFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magezon\TransferOrder\Model\TransferRequestFactory $transferRequestFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\OrderFactory $orderFactory
    )
    {
        $this->transferRequestFactory = $transferRequestFactory;
        $this->customerRepository = $customerRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->orderFactory = $orderFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->resultRedirectFactory->create()->setPath('');
        }
        try {
            if($this->isOrderBelongsToCustomer() && $this->hasCustomerTransferTo()) {
                $transferRequest = $this->transferRequestFactory->create();
                $transferRequest->setData([
                    'customer_from_id' => $this->getCustomerId(),
                    'customer_to_email' => $this->getRequest()->getParam('email'),
                    'order_increment_id' => $this->getRequest()->getParam('increment_id'),
                    'order_id' => $this->getRequest()->getParam('order_id'),
                    'method_transfer' => 1
                ]);
                $transferRequest->save();
                $this->messageManager->addSuccessMessage(__('You have successfully created a transfer order request.'));
                return $this->redirectTo(self::TRANSFER_SUCCESS_TYPE);
            } else {
                $notOrderOfCustomerMessage = __('You do not have this order.');
                $notCustomerTransferToMessage = __('There are no accounts with this email.');
                $this->messageManager->addErrorMessage(!$this->isOrderBelongsToCustomer() ? $notOrderOfCustomerMessage : $notCustomerTransferToMessage);
            }
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, $e->getMessage());
        }
        return $this->redirectTo();
    }

    /**
     * @param string $type
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function redirectTo(string $type = '')
    {
        $redirect = $this->resultRedirectFactory->create();
        if(self::TRANSFER_SUCCESS_TYPE) return $redirect->setPath('*/*/transferrequest');
        else return $redirect->setRefererUrl();
    }

    /**
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->customerSession->getCustomerId();
    }

    /**
     * @return bool
     * @throws LocalizedException
     */
    public function hasCustomerTransferTo()
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('email', $this->getRequest()->getParam('email'), 'eq')
            ->addFilter('email', $this->customerSession->getCustomer()->getEmail(), 'neq')
            ->create();
        $result = $this->customerRepository->getList($searchCriteria);
        return $result->getTotalCount() > 0;
    }

    /**
     * @return bool
     */
    public function isOrderBelongsToCustomer()
    {
        $order = $this->orderFactory->create()->loadByIncrementId($this->getRequest()->getParam('increment_id'));
        return $order->getCustomerId() == $this->customerSession->getCustomerId() && $this->getCustomerId() == $this->getRequest()->getParam('customer_id');
    }
}


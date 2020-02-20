<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_GiftWrap
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\GiftWrapGraphQl\Plugin\Model\Cart;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote;
use Mageplaza\GiftWrap\Helper\Data;
use Mageplaza\GiftWrapGraphQl\Helper\Item;
use Psr\Log\LoggerInterface;

/**
 * Class AddSimpleProductToCart
 * @package Mageplaza\GiftWrapGraphQl\Plugin\Model\Cart
 */
class AddSimpleProductToCart
{
    /**
     * @var Item
     */
    private $helper;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * AddSimpleProductToCart constructor.
     *
     * @param Item $helper
     * @param LoggerInterface $logger
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Item $helper,
        LoggerInterface $logger,
        ProductRepositoryInterface $productRepository
    ) {
        $this->helper            = $helper;
        $this->logger            = $logger;
        $this->productRepository = $productRepository;
    }

    /**
     * @param \Magento\QuoteGraphQl\Model\Cart\AddSimpleProductToCart $subject
     * @param Quote $cart
     * @param array $cartItemData
     *
     * @return array
     */
    public function beforeExecute(
        \Magento\QuoteGraphQl\Model\Cart\AddSimpleProductToCart $subject,
        Quote $cart,
        array $cartItemData
    ) {
        if (!isset($cartItemData['data']['sku'], $cartItemData['data']['mp_gift_wrap_wrap_id'])) {
            return [$cart, $cartItemData];
        }

        $cartItemData['data']['mp_gift_wrap_store_id'] = $cart->getStoreId();

        try {
            $product = $this->productRepository->get($cartItemData['data']['sku']);

            $product->addCustomOption(Data::GIFT_WRAP_DATA, $this->helper->getWrapData($cartItemData), $product);
        } catch (NoSuchEntityException $e) {
            $this->logger->critical($e);
        }

        return [$cart, $cartItemData];
    }
}

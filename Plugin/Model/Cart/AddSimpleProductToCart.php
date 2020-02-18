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
use Mageplaza\GiftWrap\Model\WrapFactory;

/**
 * Class AddSimpleProductToCart
 * @package Mageplaza\GiftWrapGraphQl\Plugin\Model\Cart
 */
class AddSimpleProductToCart
{
    /**
     * @var Data
     */
    private $helper;

    /**
     * @var WrapFactory
     */
    private $wrapFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * AddSimpleProductToCart constructor.
     *
     * @param Data $helper
     * @param WrapFactory $wrapFactory
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Data $helper,
        WrapFactory $wrapFactory,
        ProductRepositoryInterface $productRepository
    ) {
        $this->helper            = $helper;
        $this->wrapFactory       = $wrapFactory;
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

        try {
            $product = $this->productRepository->get($cartItemData['data']['sku']);

            $wrap = $this->getWrap($cartItemData);
        } catch (NoSuchEntityException $e) {
            return [$cart, $cartItemData];
        }

        $product->addCustomOption(Data::GIFT_WRAP_DATA, Data::jsonEncode($wrap), $product);

        return [$cart, $cartItemData];
    }

    /**
     * @param array $cartItemData
     *
     * @return array
     * @throws NoSuchEntityException
     */
    private function getWrap($cartItemData)
    {
        $wrap = $this->wrapFactory->create()->load($cartItemData['data']['mp_gift_wrap_wrap_id'])->getData();

        $this->helper->processWrap($wrap);

        $message = $cartItemData['data']['mp_gift_wrap_message'] ?? '';

        $wrap['gift_message']     = $message;
        $wrap['use_gift_message'] = $message !== '';

        return $wrap;
    }
}

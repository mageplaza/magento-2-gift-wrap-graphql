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

namespace Mageplaza\GiftWrapGraphQl\Plugin\Model\Resolver;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item;
use Mageplaza\GiftWrap\Helper\Data;

/**
 * Class AddSimpleProductsToCart
 * @package Mageplaza\GiftWrapGraphQl\Plugin\Model\Resolver
 */
class AddSimpleProductsToCart
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * AddSimpleProductsToCart constructor.
     *
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * @param \Magento\QuoteGraphQl\Model\Resolver\AddSimpleProductsToCart $subject
     * @param array $result
     *
     * @return array
     */
    public function afterResolve(
        \Magento\QuoteGraphQl\Model\Resolver\AddSimpleProductsToCart $subject,
        array $result
    ) {
        /** @var Quote $cart */
        $cart = $result['cart']['model'] ?? null;

        if (!$cart) {
            return [$result];
        }

        /** @var Item $item */
        foreach ($cart->getItems() as $item) {
            $option = $item->getOptionByCode(Data::GIFT_WRAP_DATA);
            if ($option && !$item->getData(Data::GIFT_WRAP_DATA)) {
                $item->setData(Data::GIFT_WRAP_DATA, $option->getValue());
            }
        }

        $this->cartRepository->save($cart);

        return [$result];
    }
}

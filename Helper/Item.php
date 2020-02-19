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
 * @package     Mageplaza_GiftWrapGraphQl
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\GiftWrapGraphQl\Helper;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Mageplaza\GiftWrap\Helper\Data;

/**
 * Class Item
 * @package Mageplaza\GiftWrapGraphQl\Helper
 */
class Item
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * Auth constructor.
     *
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * @param Quote|null $cart
     */
    public function resolveGiftWrap($cart)
    {
        if (!$cart) {
            return;
        }

        $allCartWrap = null;

        /** @var Quote\Item $item */
        foreach ($cart->getAllItems() as $item) {
            if (!$option = $item->getOptionByCode(Data::GIFT_WRAP_DATA)) {
                continue;
            }

            $giftwrapItem = $item->getParentItemId() ? $item->getParentItem() : $item;

            if (!$giftwrapItem->getData(Data::GIFT_WRAP_DATA)) {
                $giftwrapItem->setData(Data::GIFT_WRAP_DATA, $option->getValue());
            }

            $wrapData = Data::jsonDecode($option->getValue());

            if (!empty($wrapData['all_cart'])) {
                $allCartWrap = $option->getValue();
            }
        }

        if ($allCartWrap) {
            foreach ($cart->getAllVisibleItems() as $item) {
                if (!$item->getData(Data::GIFT_WRAP_DATA)) {
                    $item->setData(Data::GIFT_WRAP_DATA, $allCartWrap);
                }
            }
        }

        $this->cartRepository->save($cart);
    }
}

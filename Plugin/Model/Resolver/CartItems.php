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

use Magento\Quote\Model\Quote\Item as QuoteItem;
use Mageplaza\GiftWrap\Helper\Data;

/**
 * Class CartItems
 * @package Mageplaza\GiftWrapGraphQl\Plugin\Model\Resolver
 */
class CartItems
{
    /**
     * @param \Magento\QuoteGraphQl\Model\Resolver\CartItems $subject
     * @param array $result
     *
     * @return array
     */
    public function afterResolve(
        \Magento\QuoteGraphQl\Model\Resolver\CartItems $subject,
        array $result
    ) {
        foreach ($result as &$item) {
            /** @var QuoteItem $cartItem */
            $cartItem = $item['model'];

            if ($wrap = $cartItem->getData(Data::GIFT_WRAP_DATA)) {
                $item[Data::GIFT_WRAP_DATA] = Data::jsonDecode($cartItem->getData(Data::GIFT_WRAP_DATA));
            }
        }

        return $result;
    }
}

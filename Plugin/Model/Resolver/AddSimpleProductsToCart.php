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

use Mageplaza\GiftWrapGraphQl\Helper\Item;

/**
 * Class AddSimpleProductsToCart
 * @package Mageplaza\GiftWrapGraphQl\Plugin\Model\Resolver
 */
class AddSimpleProductsToCart
{
    /**
     * @var Item
     */
    private $helper;

    /**
     * AddSimpleProductsToCart constructor.
     *
     * @param Item $helper
     */
    public function __construct(Item $helper)
    {
        $this->helper = $helper;
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
        $this->helper->resolveGiftWrap($result['cart']['model'] ?? null);

        return $result;
    }
}

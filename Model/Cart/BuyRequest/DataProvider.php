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

declare(strict_types=1);

namespace Mageplaza\GiftWrapGraphQl\Model\Cart\BuyRequest;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Mageplaza\GiftWrap\Helper\Data;
use Mageplaza\GiftWrapGraphQl\Helper\Item;

/**
 * Class DataProvider
 * @package Mageplaza\GiftWrapGraphQl\Model\Cart\BuyRequest
 */
class DataProvider
{
    /**
     * @var Item
     */
    private $helper;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * DataProvider constructor.
     *
     * @param Item $helper
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Item $helper,
        ProductRepositoryInterface $productRepository
    ) {
        $this->helper            = $helper;
        $this->productRepository = $productRepository;
    }

    /**
     * Provide buy request data from add to cart item request
     *
     * @param array $cartItemData
     *
     * @return array
     * @throws NoSuchEntityException
     */
    public function execute(array $cartItemData): array
    {
        $product = $this->productRepository->get($cartItemData['data']['sku']);

        $product->addCustomOption(Data::GIFT_WRAP_DATA, $this->helper->getWrapData($cartItemData), $product);

        return [];
    }
}

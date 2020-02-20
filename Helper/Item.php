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

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Mageplaza\GiftWrap\Helper\Data;
use Mageplaza\GiftWrap\Model\WrapFactory;

/**
 * Class Item
 * @package Mageplaza\GiftWrapGraphQl\Helper
 */
class Item
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
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * Item constructor.
     *
     * @param Data $helper
     * @param WrapFactory $wrapFactory
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(
        Data $helper,
        WrapFactory $wrapFactory,
        CartRepositoryInterface $cartRepository
    ) {
        $this->helper         = $helper;
        $this->wrapFactory    = $wrapFactory;
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

    /**
     * @param array $cartItemData
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getWrapData($cartItemData)
    {
        $wrapObj = $this->wrapFactory->create()->load($cartItemData['data']['mp_gift_wrap_wrap_id']);

        if (!$wrapObj->getId()) {
            throw NoSuchEntityException::singleField('wrapId', $cartItemData['data']['mp_gift_wrap_wrap_id']);
        }

        $wrap = $wrapObj->getData();

        $this->helper->processWrap($wrap);

        $message = $cartItemData['data']['mp_gift_wrap_message'] ?? '';
        $storeId = $cartItemData['data']['mp_gift_wrap_store_id'] ?? 0;

        $wrap['gift_message']     = $message;
        $wrap['use_gift_message'] = $message !== '';
        if ($wrap['use_gift_message']) {
            $wrap['gift_message_fee'] = $this->helper->getGiftMessageFee(false, false, $storeId);
        }

        return Data::jsonEncode($wrap);
    }
}

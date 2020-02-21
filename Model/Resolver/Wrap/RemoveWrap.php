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

namespace Mageplaza\GiftWrapGraphQl\Model\Resolver\Wrap;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Mageplaza\GiftWrap\Api\Data\WrapItemInterface;

/**
 * Class RemoveWrap
 * @package Mageplaza\GiftWrapGraphQl\Model\Resolver\Wrap
 */
class RemoveWrap extends AbstractResolver
{
    /**
     * @param array $args
     * @param string $cartId
     *
     * @return WrapItemInterface
     * @throws GraphQlInputException
     */
    protected function handleArgs(array $args, $cartId)
    {
        try {
            return $this->quoteWrap->remove($cartId, $args['item_id']);
        } catch (NoSuchEntityException $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }
    }
}

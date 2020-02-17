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

namespace Mageplaza\GiftWrapGraphQl\Model\Resolver\Save;

use Exception;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Mageplaza\GiftWrap\Api\Data\CategoryInterface;
use Mageplaza\GiftWrap\Api\Data\WrapInterface;
use Mageplaza\GiftWrap\Api\Data\HistoryInterface;

/**
 * Class AbstractResolver
 * @package Mageplaza\GiftWrapGraphQl\Model\Resolver\Save
 */
class AbstractResolver extends \Mageplaza\GiftWrapGraphQl\Model\Resolver\AbstractResolver
{
    /**
     * @param array $args
     *
     * @return CategoryInterface|WrapInterface|HistoryInterface
     * @throws GraphQlInputException
     */
    protected function handleArgs(array $args)
    {
        try {
            return $this->filter->saveEntity($args['input'], $this->_type);
        } catch (Exception $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }
    }
}

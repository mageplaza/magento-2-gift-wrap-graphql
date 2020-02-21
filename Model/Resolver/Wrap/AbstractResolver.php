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

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface;
use Mageplaza\GiftWrap\Api\QuoteWrapInterface;
use Mageplaza\GiftWrap\Helper\Data;

/**
 * Class AbstractResolver
 * @package Mageplaza\GiftWrapGraphQl\Model\Resolver\Wrap
 */
abstract class AbstractResolver implements ResolverInterface
{
    /**
     * @var MaskedQuoteIdToQuoteIdInterface
     */
    private $maskedQuoteIdToQuoteId;

    /**
     * @var QuoteWrapInterface
     */
    protected $quoteWrap;

    /**
     * @var Data
     */
    private $helper;

    /**
     * AbstractResolver constructor.
     *
     * @param MaskedQuoteIdToQuoteIdInterface $maskedQuoteIdToQuoteId
     * @param QuoteWrapInterface $quoteWrap
     * @param Data $helper
     */
    public function __construct(
        MaskedQuoteIdToQuoteIdInterface $maskedQuoteIdToQuoteId,
        QuoteWrapInterface $quoteWrap,
        Data $helper
    ) {
        $this->maskedQuoteIdToQuoteId = $maskedQuoteIdToQuoteId;
        $this->quoteWrap              = $quoteWrap;
        $this->helper                 = $helper;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!$this->helper->isEnabled()) {
            throw new GraphQlInputException(__('The module is disabled'));
        }
        try {
            $cartId = $this->maskedQuoteIdToQuoteId->execute($args['cart_id']);
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__('Could not find a cart with ID %1', $args['cart_id']));
        }

        return $this->handleArgs($args, $cartId);
    }

    /**
     * @param array $args
     * @param string $cartId
     *
     * @return mixed
     */
    abstract protected function handleArgs(array $args, $cartId);
}

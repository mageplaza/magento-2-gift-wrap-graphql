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

namespace Mageplaza\GiftWrapGraphQl\Model\Resolver\Filter\Query;

use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder;
use Mageplaza\GiftWrap\Api\CategoryManagementInterface;
use Mageplaza\GiftWrap\Api\Data\CategoryInterface;
use Mageplaza\GiftWrap\Api\Data\WrapInterface;
use Mageplaza\GiftWrap\Api\WrapManagementInterface;

/**
 * Retrieve filtered data based off given search criteria in a format that GraphQL can interpret.
 */
class Filter
{
    /**
     * @var Builder
     */
    private $searchCriteriaBuilder;

    /**
     * @var CategoryManagementInterface
     */
    private $categoryManagement;

    /**
     * @var WrapManagementInterface
     */
    private $wrapManagement;

    /**
     * Filter constructor.
     *
     * @param Builder $searchCriteriaBuilder
     * @param CategoryManagementInterface $categoryManagement
     * @param WrapManagementInterface $wrapManagement
     */
    public function __construct(
        Builder $searchCriteriaBuilder,
        CategoryManagementInterface $categoryManagement,
        WrapManagementInterface $wrapManagement
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->categoryManagement    = $categoryManagement;
        $this->wrapManagement        = $wrapManagement;
    }

    /**
     * @param array $args
     * @param string $type
     *
     * @return SearchResultsInterface
     * @throws LocalizedException
     */
    public function getResult($args, $type)
    {
        $searchCriteria = $this->searchCriteriaBuilder->build($type, $args);
        $searchCriteria->setCurrentPage($args['currentPage']);
        $searchCriteria->setPageSize($args['pageSize']);

        switch ($type) {
            case 'mpGiftWrapCategory':
                return $this->categoryManagement->getList($searchCriteria);
            case 'mpGiftWrapWrapper':
            default:
                return $this->wrapManagement->getList($searchCriteria);
        }
    }

    /**
     * @param string $id
     * @param string $type
     *
     * @return CategoryInterface|WrapInterface
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getResultById($id, $type)
    {
        switch ($type) {
            case 'mpGiftWrapCategory':
                return $this->categoryManagement->get($id);
            case 'mpGiftWrapWrapper':
            default:
                return $this->wrapManagement->get($id);
        }
    }
}

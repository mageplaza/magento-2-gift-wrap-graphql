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

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder;
use Mageplaza\GiftWrap\Api\CategoryManagementInterface;
use Mageplaza\GiftWrap\Api\Data\CategoryInterface;
use Mageplaza\GiftWrap\Api\Data\HistoryInterface;
use Mageplaza\GiftWrap\Api\Data\WrapInterface;
use Mageplaza\GiftWrap\Api\WrapManagementInterface;
use Mageplaza\GiftWrap\Model\Category as CategoryModel;
use Mageplaza\GiftWrap\Model\History as HistoryModel;
use Mageplaza\GiftWrap\Model\Wrap as WrapModel;
use Mageplaza\GiftWrapGraphQl\Model\Resolver\Filter\SearchResult;
use Mageplaza\GiftWrapGraphQl\Model\Resolver\Filter\SearchResultFactory;

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
     * @var SearchResultFactory
     */
    private $searchResultFactory;

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
     * @param SearchResultFactory $searchResultFactory
     * @param CategoryManagementInterface $categoryManagement
     * @param WrapManagementInterface $wrapManagement
     */
    public function __construct(
        Builder $searchCriteriaBuilder,
        SearchResultFactory $searchResultFactory,
        CategoryManagementInterface $categoryManagement,
        WrapManagementInterface $wrapManagement
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->searchResultFactory   = $searchResultFactory;
        $this->categoryManagement    = $categoryManagement;
        $this->wrapManagement        = $wrapManagement;
    }

    /**
     * @param array $args
     * @param string $type
     *
     * @return SearchResult
     */
    public function getResult($args, $type): SearchResult
    {
        $searchCriteria = $this->searchCriteriaBuilder->build($type, $args);
        $searchCriteria->setCurrentPage($args['currentPage']);
        $searchCriteria->setPageSize($args['pageSize']);

        switch ($type) {
            case 'mpGiftWrapCategory':
                $list = $this->categoryManagement->getList($searchCriteria);
                break;
            case 'mpGiftWrapWrapper':
            default:
                $list = $this->wrapManagement->getList($searchCriteria);
                break;
        }

        $listArray = [];
        /** @var CategoryModel|WrapModel|HistoryModel $item */
        foreach ($list->getItems() as $item) {
            $listArray[$item->getId()]          = $item->getData();
            $listArray[$item->getId()]['model'] = $item;
        }

        return $this->searchResultFactory->create($list->getTotalCount(), $listArray);
    }

    /**
     * @param string $id
     * @param string $type
     *
     * @return CategoryInterface|WrapInterface|HistoryInterface
     * @throws NoSuchEntityException
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

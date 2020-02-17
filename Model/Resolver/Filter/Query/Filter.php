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

use Exception;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder;
use Mageplaza\GiftWrap\Api\CategoryManagementInterface;
use Mageplaza\GiftWrap\Api\Data\CategoryInterface;
use Mageplaza\GiftWrap\Api\Data\HistoryInterface;
use Mageplaza\GiftWrap\Api\Data\WrapInterface;
use Mageplaza\GiftWrap\Api\HistoryManagementInterface;
use Mageplaza\GiftWrap\Api\WrapManagementInterface;
use Mageplaza\GiftWrap\Helper\Data;
use Mageplaza\GiftWrap\Model\Category as CategoryModel;
use Mageplaza\GiftWrap\Model\CategoryFactory;
use Mageplaza\GiftWrap\Model\History as HistoryModel;
use Mageplaza\GiftWrap\Model\Wrap as WrapModel;
use Mageplaza\GiftWrap\Model\WrapFactory;
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
     * @var HistoryManagementInterface
     */
    private $historyManagement;

    /**
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * @var WrapFactory
     */
    private $wrapFactory;

    /**
     * Filter constructor.
     *
     * @param Builder $searchCriteriaBuilder
     * @param SearchResultFactory $searchResultFactory
     * @param CategoryManagementInterface $categoryManagement
     * @param WrapManagementInterface $wrapManagement
     * @param HistoryManagementInterface $historyManagement
     * @param CategoryFactory $categoryFactory
     * @param WrapFactory $wrapFactory
     */
    public function __construct(
        Builder $searchCriteriaBuilder,
        SearchResultFactory $searchResultFactory,
        CategoryManagementInterface $categoryManagement,
        WrapManagementInterface $wrapManagement,
        HistoryManagementInterface $historyManagement,
        CategoryFactory $categoryFactory,
        WrapFactory $wrapFactory
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->searchResultFactory   = $searchResultFactory;
        $this->categoryManagement    = $categoryManagement;
        $this->wrapManagement        = $wrapManagement;
        $this->historyManagement     = $historyManagement;
        $this->categoryFactory       = $categoryFactory;
        $this->wrapFactory           = $wrapFactory;
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
            case 'mpGiftCategory':
                $list = $this->categoryManagement->getList($searchCriteria);
                break;
            case 'mpGiftWrap':
                $list = $this->wrapManagement->getList($searchCriteria);
                break;
            case 'mpGiftHistory':
            default:
                $list = $this->historyManagement->getList($searchCriteria);
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
            case 'mpGiftCategory':
                return $this->categoryManagement->get($id);
            case 'mpGiftWrap':
                return $this->wrapManagement->get($id);
            case 'mpGiftHistory':
            default:
                return $this->historyManagement->get($id);
        }
    }

    /**
     * @param array $data
     * @param string $type
     *
     * @return CategoryInterface|WrapInterface|HistoryInterface
     * @throws Exception
     */
    public function saveEntity($data, $type)
    {
        if (isset($data['template_fields'])) {
            $data['template_fields'] = Data::jsonEncode($data['template_fields']);
        }

        switch ($type) {
            case 'mpGiftCategory':
                $entity = $this->categoryFactory->create()->setData($data);

                return $this->categoryManagement->save($entity);
            case 'mpGiftWrap':
            default:
                $entity = $this->wrapFactory->create()->setData($data);

                return $this->wrapManagement->save($entity);
        }
    }

    /**
     * @param string $id
     * @param string $type
     *
     * @return bool
     * @throws NoSuchEntityException
     */
    public function deleteEntity($id, $type)
    {
        switch ($type) {
            case 'mpGiftCategory':
                return $this->categoryManagement->delete($id);
            default:
            case 'mpGiftWrap':
                return $this->wrapManagement->delete($id);
        }
    }
}

<?php
/**
 * MageINIC
 * Copyright (C) 2023 MageINIC <support@mageinic.com>
 *
 * NOTICE OF LICENSE
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see https://opensource.org/licenses/gpl-3.0.html.
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category MageINIC
 * @package MageINIC_SizeChart
 * @copyright Copyright (c) 2023 MageINIC (https://www.mageinic.com/)
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MageINIC <support@mageinic.com>
 */

namespace MageINIC\SizeChart\Model;

use Exception;
use MageINIC\SizeChart\Api\Data\SizeChartSearchResultsInterfaceFactory as SearchResultsFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\Search\SearchResultFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use MageINIC\SizeChart\Api\Data\SizeChartInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use MageINIC\SizeChart\Api\SizeChartRepositoryInterface;
use MageINIC\SizeChart\Model\ResourceModel\SizeChart as ResourceSizeChart;
use MageINIC\SizeChart\Model\ResourceModel\SizeChart\CollectionFactory as SizeChartCollectionFactory;

/**
 * Class SizeChart SizeChartRepository
 */
class SizeChartRepository implements SizeChartRepositoryInterface
{
    /**
     * @var SizeChartFactory
     */
    public SizeChartFactory $SizeChartFactory;
    /**
     * @var ResourceSizeChart
     */
    protected ResourceSizeChart $resource;

    /**
     * @var DataObjectHelper
     */
    protected DataObjectHelper $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected DataObjectProcessor $dataObjectProcessor;

    /**
     * @var SearchResultFactory
     */
    public SearchResultFactory $sizeSearchResult;

    /**
     * @var CollectionProcessor
     */
    public CollectionProcessor $collectionProcessor;

    /**
     * @var SizeChartCollectionFactory
     */
    private SizeChartCollectionFactory $sizeChartCollectionFactory;

    /**
     * @var SearchResultsFactory
     */
    private SearchResultsFactory $SearchResultsFactory;

    /**
     * @param ResourceSizeChart $resource
     * @param SizeChartFactory $SizeChartFactory
     * @param SizeChartCollectionFactory $SizeChartCollectionFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param CollectionProcessor $collectionProcessor
     * @param SearchResultFactory $sizeSearchResult
     * @param SearchResultsFactory $SearchResultsFactory
     */
    public function __construct(
        ResourceSizeChart          $resource,
        SizeChartFactory           $SizeChartFactory,
        SizeChartCollectionFactory $SizeChartCollectionFactory,
        DataObjectHelper           $dataObjectHelper,
        DataObjectProcessor        $dataObjectProcessor,
        CollectionProcessor        $collectionProcessor,
        SearchResultFactory        $sizeSearchResult,
        SearchResultsFactory       $SearchResultsFactory
    ) {
        $this->resource = $resource;
        $this->SizeChartFactory = $SizeChartFactory;
        $this->sizeChartCollectionFactory = $SizeChartCollectionFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->collectionProcessor = $collectionProcessor;
        $this->sizeSearchResult = $sizeSearchResult;
        $this->SearchResultsFactory = $SearchResultsFactory;
    }

    /**
     * @inheritdoc
     */
    public function save(SizeChartInterface $sizechart): SizeChartInterface
    {
        try {
            $this->resource->save($sizechart);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $sizechart;
    }

    /**
     * @inheritdoc
     */
    public function deleteById(int $Id): bool
    {
        return $this->delete($this->getById($Id));
    }

    /**
     * @inheritdoc
     */
    public function delete(SizeChartInterface $sizechart): bool
    {
        try {
            $this->resource->delete($sizechart);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $Id): SizeChartInterface
    {
        $sizeChart = $this->SizeChartFactory->create();
        $this->resource->load($sizeChart, $Id);
        if (!$sizeChart->getId()) {
            throw new NoSuchEntityException(__('The SizeChart with the "%1" ID does\'t exist.', $Id));
        }
        return $sizeChart;
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->sizeChartCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->SearchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }
}

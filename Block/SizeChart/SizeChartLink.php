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

namespace MageINIC\SizeChart\Block\SizeChart;

use MageINIC\SizeChart\Api\Data\SizeChartInterface;
use MageINIC\SizeChart\Api\SizeChartRepositoryInterface as SizeChartRepository;
use Magento\Framework\Api\SearchCriteriaBuilder as SearchCriteria;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use MageINIC\SizeChart\Model\ResourceModel\SizeChart\CollectionFactory;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;
use MageINIC\SizeChart\Model\SizeChartFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class SizeChart AnchorLink
 */
class SizeChartLink extends Template
{
    /**
     * config path
     */
    public const ENABLE_PATH = 'sizechart/general/enable';

    /**
     * @var Registry|null
     */
    protected ?Registry $coreRegistry = null;

    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $collectionFactory;

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var SizeChartFactory
     */
    protected SizeChartFactory $sizeChartFactory;

    /**
     * @var FilterProvider
     */
    protected FilterProvider $contentProcessor;

    /**
     * @var SearchCriteria
     */
    private SearchCriteria $searchCriteria;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var SizeChartRepository
     */
    protected SizeChartRepository $chartRepository;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param CollectionFactory $collectionFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param SizeChartFactory $sizeChartFactory
     * @param FilterProvider $contentProcessor
     * @param SearchCriteria $searchCriteria
     * @param StoreManagerInterface $storeManager
     * @param SizeChartRepository $chartRepository
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        CollectionFactory $collectionFactory,
        ScopeConfigInterface $scopeConfig,
        SizeChartFactory $sizeChartFactory,
        FilterProvider $contentProcessor,
        SearchCriteria        $searchCriteria,
        StoreManagerInterface $storeManager,
        SizeChartRepository   $chartRepository,
        array $data = []
    ) {
        $this->coreRegistry      = $registry;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
        $this->sizeChartFactory = $sizeChartFactory;
        $this->contentProcessor = $contentProcessor;
        $this->searchCriteria = $searchCriteria;
        $this->storeManager = $storeManager;
        $this->chartRepository = $chartRepository;
    }

    /**
     * Get Attribute Id
     *
     * @return mixed
     */
    public function getId(): mixed
    {
        return $this->getProduct()->getData('mageinic_size_chart');
    }

    /**
     * Get Product
     *
     * @return mixed|null
     */
    public function getProduct(): mixed
    {
        return $this->coreRegistry->registry('product');
    }

    /**
     * Get SizeChart Details
     *
     * @return SizeChartInterface[]
     * @throws LocalizedException
     */
    public function getSizeChartDetails(): array
    {
        $storeId = $this->storeManager->getStore()->getId();
        $searchCriteria = $this->searchCriteria
            ->addFilter('store_id', $storeId)
            ->addFilter('status', 1)
            ->addFilter('sizechart_id', $this->getId())
            ->setPageSize(1)
            ->setCurrentPage(1)
            ->create();
        $sizechartDetails = $this->chartRepository->getList($searchCriteria);

        return $sizechartDetails->getItems();
    }

    /**
     * Enable SizeChart
     *
     * @return mixed
     */
    public function getEnable(): mixed
    {
        return $this->scopeConfig->getValue(
            self::ENABLE_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }
}

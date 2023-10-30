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

namespace MageINIC\SizeChart\Plugin;

use MageINIC\SizeChart\Api\SizeChartRepositoryInterface as ChartRepository ;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder as SearchCriteria;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use MageINIC\SizeChart\Api\Data\SizeChartInterfaceFactory;

/**
 * SizeChart Plugin Class
 */
class SizeChart
{
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var SearchCriteria
     */
    private SearchCriteria $searchCriteriaBuilder;

    /**
     * @var ChartRepository
     */
    private ChartRepository $chartRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * SizeChart Constructor
     * @param StoreManagerInterface $storeManager
     * @param SearchCriteria $searchCriteriaBuilder
     * @param ChartRepository $chartRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        SearchCriteria        $searchCriteriaBuilder,
        ChartRepository       $chartRepository,
        ProductRepositoryInterface $productRepository,
    ) {
        $this->storeManager = $storeManager;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->chartRepository = $chartRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * After Get Sku
     *
     * @param ProductRepositoryInterface $subject
     * @param ProductInterface $result
     * @return ProductInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function afterGet(ProductRepositoryInterface $subject, ProductInterface $result): ProductInterface
    {
        $storeId = $this->storeManager->getStore()->getId();
        $attributeId = $result->getData('mageinic_size_chart');
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('store_id', $storeId)
            ->addFilter('status', 1)
            ->addFilter('sizechart_id', $attributeId)
            ->setPageSize(1)
            ->create();
        $sizechartDetails = $this->chartRepository->getList($searchCriteria);
        $extensionAttributes = $result->getExtensionAttributes();
        $extensionAttributes->setSizeChart($sizechartDetails->getItems());
        $result->setExtensionAttributes($extensionAttributes);

        return $result;
    }
}

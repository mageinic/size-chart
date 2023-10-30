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

namespace MageINIC\SizeChart\Api;

use MageINIC\SizeChart\Api\Data;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface SizeChartRepositoryInterface
 */
interface SizeChartRepositoryInterface
{
    /**
     * Retrieve SizeChart By given id.
     *
     * @param int $Id
     * @return \MageINIC\SizeChart\Api\Data\SizeChartInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $Id): Data\SizeChartInterface;

    /**
     * Save SizeChart.
     *
     * @param \MageINIC\SizeChart\Api\Data\SizeChartInterface $sizechart
     * @return \MageINIC\SizeChart\Api\Data\SizeChartInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(Data\SizeChartInterface $sizechart): Data\SizeChartInterface;

    /**
     * Delete SizeChart.
     *
     * @param \MageINIC\SizeChart\Api\Data\SizeChartInterface $sizechart
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException;
     */
    public function delete(Data\SizeChartInterface $sizechart): bool;

    /**
     * Delete SizeChart by ID.
     *
     * @param int $Id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $Id): bool;

    /**
     * Retrieve blocks matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \MageINIC\SizeChart\Api\Data\SizeChartSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}

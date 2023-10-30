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

namespace MageINIC\SizeChart\Model\ResourceModel\SizeChart;

use Exception;
use MageINIC\SizeChart\Api\Data\SizeChartInterface;
use MageINIC\SizeChart\Model\ResourceModel\SizeChart as ResourceModel;
use MageINIC\SizeChart\Model\ResourceModel\AbstractCollection;
use MageINIC\SizeChart\Model\SizeChart as Model;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\Store;

/**
 * Class SizeChart Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'sizechart_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'mageinic_size_chart_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'sizechart_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(
            Model::class,
            ResourceModel::class
        );
        $this->_map['fields']['store'] = 'store_table.store_id';
        $this->_map['fields']['sizechart_id'] = 'main_table.sizechart_id';
    }

    /**
     * Add filter by store
     *
     * @param int|array|Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, bool $withAdmin = true): Collection
    {
        $this->performAddStoreFilter($store, $withAdmin);

        return $this;
    }

    /**
     * Perform operations after collection load
     *
     * @return $this
     * @throws NoSuchEntityException
     * @throws Exception
     */
    protected function _afterLoad(): Collection
    {
        $entityMetadata = $this->metadataPool->getMetadata(SizeChartInterface::class);
        $this->performAfterLoad('mageinic_size_chart_store', $entityMetadata->getLinkField());

        return parent::_afterLoad();
    }

    /**
     * Join store relation table if there is store filter
     *
     * @return void
     * @throws Exception
     */
    protected function _renderFiltersBefore(): void
    {
        $entityMetadata = $this->metadataPool->getMetadata(SizeChartInterface::class);
        $this->joinStoreRelationTable('mageinic_size_chart_store', $entityMetadata->getLinkField());
    }
}

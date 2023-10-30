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

namespace MageINIC\SizeChart\Model\Source\SizeChart;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use MageINIC\SizeChart\Model\SizeChart as SizeChartModel;
use MageINIC\SizeChart\Model\ResourceModel\SizeChart\CollectionFactory;

/**
 * Class SizeChart SizeChart
 */
class SizeChart extends AbstractSource
{
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * SizeChart constructor.
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Retrieve All Design Theme Options
     *
     * @param bool $withEmpty
     * @return array
     */
    public function getAllOptions(bool $withEmpty = true): array
    {
        $label = $withEmpty ? __('-- Please Select --') : $withEmpty;
        $sizechart[] = ['value' => '', 'label' => $label];
        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect('*');
        $collection->addFieldToFilter('status', ['eq' => SizeChartModel::STATUS_ENABLED]);
        foreach ($collection as $row) {
            $sizechart[] = [
                'value' => $row['sizechart_id'],
                'label' => $row['title']
                ];
        }
        return $this->_options = $sizechart;
    }
}

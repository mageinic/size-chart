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

namespace MageINIC\SizeChart\Setup\Patch\Data;

use MageINIC\SizeChart\Model\Source\SizeChart\SizeChart;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * SizeChart Class SizeChartAttribute
 */
class SizeChartAttribute implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private EavSetupFactory $eavSetupFactory;

    /**
     * AddAttributeItemGroup constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory          $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @inheritdoc
     */
    public function apply(): void
    {
        $data = [
            [
                'Default',
                '0,2,4,6,8,10,12,14,16,18,20,22,24,26',
                '32,33,34,35,36,37,38.5,40,42,44,46,48.5,51,53.5',
                '24.5,25.5,26.5,27.5,28.5,29.5,31,32.5,34.5,36.5,39,41.5,44,47',
                '35.5,36.5,37.5,38.5,39.5,40.5,42,43.5,45.5,47.5,49.5,52,54.5,57',
                '1'
            ],
        ];
        $columns = ['title', 'sizes', 'bust', 'waist', 'hip', 'status'];
        $this->moduleDataSetup->getConnection()->insertArray(
            $this->moduleDataSetup->getTable('mageinic_size_chart'),
            $columns,
            $data
        );
        $sizeChartId = $this->moduleDataSetup->getConnection()->lastInsertId();
        $storeData = [
            'sizechart_id' => $sizeChartId,
            'store_id' => '0',
        ];
        $this->moduleDataSetup->getConnection()->insert(
            $this->moduleDataSetup->getTable('mageinic_size_chart_store'),
            $storeData
        );
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(
            Product::ENTITY,
            'mageinic_size_chart',
            [
                'type' => 'varchar',
                'frontend' => '',
                'label' => 'Size Chart',
                'input' => 'select',
                'is_configurable' => 1,
                'backend' => ArrayBackend::class,
                'class' => '',
                'source' => SizeChart::class,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
    }
}

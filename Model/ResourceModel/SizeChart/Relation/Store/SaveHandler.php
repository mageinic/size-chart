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

namespace MageINIC\SizeChart\Model\ResourceModel\SizeChart\Relation\Store;

use Exception;
use MageINIC\SizeChart\Api\Data\SizeChartInterface;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use MageINIC\SizeChart\Model\ResourceModel\SizeChart;
use Magento\Framework\EntityManager\MetadataPool;

/**
 * Class for SaveHandler
 */
class SaveHandler implements ExtensionInterface
{
    /**
     * @var MetadataPool
     */
    protected MetadataPool $metadataPool;

    /**
     * @var SizeChart
     */
    protected SizeChart $resourcePage;

    /**
     * @param MetadataPool $metadataPool
     * @param SizeChart $resourcePage
     */
    public function __construct(
        MetadataPool $metadataPool,
        SizeChart $resourcePage
    ) {
        $this->metadataPool = $metadataPool;
        $this->resourcePage = $resourcePage;
    }

    /**
     * Perform action on relation/extension attribute
     *
     * @param object $entity
     * @param array $arguments
     * @return object
     * @throws Exception
     */
    public function execute($entity, $arguments = []): object
    {
        $entityMetadata = $this->metadataPool->getMetadata(SizeChartInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $connection = $this->resourcePage->getConnection();

        $oldStores = $this->resourcePage->lookupStoreIds((int)$entity->getId());
        $newStores = (array)$entity->getStoreId();
        if (empty($newStores)) {
            $newStores = (array)$entity->getStoreId();
        }

        $table = $this->resourcePage->getTable('mageinic_size_chart_store');

        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = [
                $linkField . ' = ?' => (int)$entity->getData($linkField),
                'store_id IN (?)' => $delete,
            ];
            $connection->delete($table, $where);
        }

        $insert = array_diff($newStores, $oldStores);
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    $linkField => (int)$entity->getData($linkField),
                    'store_id' => (int)$storeId
                ];
            }
            $connection->insertMultiple($table, $data);
        }

        return $entity;
    }
}

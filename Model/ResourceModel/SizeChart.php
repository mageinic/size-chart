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

namespace MageINIC\SizeChart\Model\ResourceModel;

use Exception;
use MageINIC\SizeChart\Api\Data\SizeChartInterface;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime;
use Magento\Store\Model\Store;
use Magento\Framework\DB\Select;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * Class SizeChart SizeChart
 */
class SizeChart extends AbstractDb
{
    /**
     * @var Store|null
     */
    protected ?Store $_store = null;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $_storeManager;

    /**
     * @var DateTime
     */
    protected DateTime $dateTime;

    /**
     * @var EntityManager
     */
    protected EntityManager $entityManager;

    /**
     * @var MetadataPool
     */
    protected MetadataPool $metadataPool;

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param DateTime $dateTime
     * @param EntityManager $entityManager
     * @param MetadataPool $metadataPool
     * @param ResourceConnection $resourceConnection
     * @param string|null $connectionName
     */
    public function __construct(
        Context               $context,
        StoreManagerInterface $storeManager,
        DateTime              $dateTime,
        EntityManager         $entityManager,
        MetadataPool          $metadataPool,
        ResourceConnection    $resourceConnection,
        string $connectionName = null
    ) {
        $this->_storeManager = $storeManager;
        $this->dateTime = $dateTime;
        $this->entityManager = $entityManager;
        $this->metadataPool = $metadataPool;
        $this->resourceConnection = $resourceConnection;
        parent::__construct($context, $connectionName);
    }

    /**
     * @inheritdoc
     */
    public function _construct(): void
    {
        $this->_init('mageinic_size_chart', 'sizechart_id');
    }

    /**
     * Load an object
     *
     * @param AbstractModel $object
     * @param mixed $value
     * @param string|null $field field to load by (defaults to model id)
     * @return $this
     * @throws Exception
     */

    public function load(AbstractModel $object, $value, $field = null): SizeChart
    {
        $id = $this->getSizeChartId($object, $value, $field);
        if ($id) {
            $this->entityManager->load($object, $id);
        }
        return $this;
    }

    /**
     * Retrieve Sizechart ID.
     *
     * @param AbstractModel $object
     * @param mixed $value
     * @param string|null $field
     * @return mixed
     * @throws Exception
     */
    private function getSizeChartId(AbstractModel $object, mixed $value, string $field = null): mixed
    {
        $entityMetadata = $this->metadataPool->getMetadata(SizeChartInterface::class);

        if (!is_numeric($value) && $field === null) {
            $field = 'sizechart_id';
        } elseif (!$field) {
            $field = $entityMetadata->getIdentifierField();
        }

        $id = $value;
        /** @var SizeChartInterface $object */
        if ($field != $entityMetadata->getIdentifierField() || $object->getStoreId()) {
            $select = $this->_getLoadSelect($field, $value, $object);
            $select->reset(Select::COLUMNS)
                ->columns($this->getMainTable() . '.' . $entityMetadata->getIdentifierField())
                ->limit(1);
            $result = $this->getConnection()->fetchCol($select);
            $id = count($result) ? $result[0] : false;
        }
        return $id;
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param AbstractModel $object
     * @return Select
     * @throws LocalizedException
     * @throws Exception
     */
    protected function _getLoadSelect($field, $value, $object): Select
    {
        $entityMetadata = $this->metadataPool->getMetadata(SizeChartInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = parent::_getLoadSelect($field, $value, $object);

        /** @var SizeChartInterface $object */
        if ($object->getStoreId()) {
            $storeIds = [
                Store::DEFAULT_STORE_ID,
                (int)$object->getStoreId(),
            ];
            $select->join(
                ['mageinic_size_chart_store' => $this->getTable('mageinic_size_chart_store')],
                $this->getMainTable() . '.' . $linkField . ' = mageinic_size_chart_store.' . $linkField,
                []
            )
                ->where('is_active = ?', 1)
                ->where('mageinic_size_chart_store.store_id IN (?)', $storeIds)
                ->order('mageinic_size_chart_store.store_id DESC')
                ->limit(1);
        }

        return $select;
    }

    /**
     * Get Connection.
     *
     * @return AdapterInterface
     * @throws Exception
     */
    public function getConnection(): AdapterInterface
    {
        return $this->resourceConnection->getConnectionByName(
            $this->metadataPool->getMetadata(SizeChartInterface::class)->getEntityConnectionName()
        );
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     * @throws LocalizedException
     * @throws Exception
     */
    public function lookupStoreIds(int $id): array
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->metadataPool->getMetadata(SizeChartInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
            ->from(['cps' => $this->getTable('mageinic_size_chart_store')], 'store_id')
            ->join(
                ['cp' => $this->getMainTable()],
                'cps.' . $linkField . ' = cp.' . $linkField,
                []
            )
            ->where('cp.' . $entityMetadata->getIdentifierField() . ' = :sizechart_id');

        return $connection->fetchCol($select, ['sizechart_id' => (int)$id]);
    }

    /**
     * Retrieve Store.
     *
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    public function getStore(): StoreInterface
    {
        return $this->_storeManager->getStore($this->_store);
    }

    /**
     * Set Store.
     *
     * @param StoreInterface $store
     * @return $this
     */
    public function setStore(StoreInterface $store): SizeChart
    {
        $this->_store = $store;
        return $this;
    }

    /**
     * Save an object.
     *
     * @param AbstractModel $object
     * @return $this
     * @throws Exception
     */
    public function save(AbstractModel $object): SizeChart
    {
        $this->entityManager->save($object);
        return $this;
    }

    /**
     * Delete the object
     *
     * @param AbstractModel $object
     * @return $this
     * @throws Exception
     */
    public function delete(AbstractModel $object): SizeChart
    {
        $this->entityManager->delete($object);
        return $this;
    }
}

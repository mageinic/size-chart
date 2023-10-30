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
use MageINIC\SizeChart\Model\ResourceModel\SizeChart;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Read Handler for StoreId.
 */
class ReadHandler implements ExtensionInterface
{
    /**
     * @var SizeChart
     */
    protected SizeChart $resource;

    /**
     * @param SizeChart $resource
     */
    public function __construct(
        SizeChart $resource
    ) {
        $this->resource = $resource;
    }

    /**
     * Perform action on relation/extension attribute.
     *
     * @param object $entity
     * @param array $arguments
     * @return object
     * @throws LocalizedException|Exception
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */

    public function execute($entity, $arguments = []): object
    {
        if ($entity->getId()) {
            $stores = $this->resource->lookupStoreIds((int)$entity->getId());
            $entity->setData('store_id', $stores);
        }
        return $entity;
    }
}

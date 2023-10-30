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

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use MageINIC\SizeChart\Api\Data\SizeChartInterface;
use MageINIC\SizeChart\Model\ResourceModel\SizeChart as ResourceModel;

/**
 * Class SizeChart SizeChart
 */
class SizeChart extends AbstractModel implements SizeChartInterface
{
    /**
     * Config path
     */
    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLED = 0;

    /**+
     * @var string
     */
    protected $_eventPrefix = 'mageinic';

    /**
     * @var string
     */
    protected $_eventObject = 'sizechart';

    /**
     * @var string
     */
    protected $_idFieldName = self::ID;

    /**
     * @inheritdoc
     */
    public function getSizechartId(): int
    {
        return $this->getData(self::ID);
    }

    /**
     * @inheritdoc
     */
    public function setSizechartId($sizeChartId): SizeChartInterface
    {
        return $this->getData(self::ID);
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): string
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritdoc
     */
    public function setTitle(string $title): SizeChartInterface
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritdoc
     */
    public function getSizes(): string
    {
        return $this->getData(self::SIZES);
    }

    /**
     * @inheritdoc
     */
    public function setSizes(string $sizes): SizeChartInterface
    {
        return $this->setSizes(self::SIZES, $sizes);
    }

    /**
     * @inheritdoc
     */
    public function getBust(): string
    {
        return $this->getData(self::BUST);
    }

    /**
     * @inheritdoc
     */
    public function setBust(string $bust): SizeChartInterface
    {
        return $this->setData(self::BUST, $bust);
    }

    /**
     * @inheritdoc
     */
    public function getWaist(): string
    {
        return $this->getData(self::WAIST);
    }

    /**
     * @inheritdoc
     */
    public function setWaist(string $waist): SizeChartInterface
    {
        return $this->setData(self::WAIST, $waist);
    }

    /**
     * @inheritdoc
     */
    public function getHip(): string
    {
        return $this->getData(self::HIP);
    }

    /**
     * @inheritdoc
     */
    public function setHip(string $hip): SizeChartInterface
    {
        return $this->setData(self::HIP, $hip);
    }

    /**
     * @inheritdoc
     */
    public function getStatus(): ?int
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritdoc
     */
    public function setStatus(int $status): SizeChartInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritdoc
     */
    public function getContent(): ?string
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * @inheritdoc
     */
    public function setContent(string $content): SizeChartInterface
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * SizeChart ResourceModel
     *
     * @return void
     */
    public function _construct(): void
    {
        $this->_init(ResourceModel ::class);
    }

    /**
     * SizeChart Id
     *
     * @param int $sizeChartId
     * @return array
     */
    public function getColumns(int $sizeChartId): array
    {
        $data = $this->load($sizeChartId)->getData();
        unset($data['sizechart_id']);
        unset($data['title']);
        $newData = [];
        foreach ($data as $value) {
            if (is_string($value)) {
                $valueString = trim($value ?? '');
                $newData[] = explode(',', $valueString ?? '');
            }
        }
        return $newData;
    }

    /**
     * Prepare SizeChart's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses(): array
    {
        return [
            self::STATUS_ENABLED => __('Enabled'),
            self::STATUS_DISABLED => __('Disabled')
        ];
    }
}

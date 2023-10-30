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

namespace MageINIC\SizeChart\Api\Data;

use Magento\Framework\Exception\LocalizedException;

/**
 * Interface SizeChartInterface
 */
interface SizeChartInterface
{
    public const ID = 'sizechart_id';
    public const TITLE = 'title';
    public const SIZES = 'sizes';
    public const BUST = 'bust';
    public const WAIST = 'waist';
    public const HIP = 'hip';
    public const STATUS = 'status';
    public const CONTENT = 'content';

    /**
     * Get Sizechart ID
     *
     * @return int
     */
    public function getSizechartId(): int;

    /**
     * Set Sizechart ID
     *
     * @param int $sizeChartId
     * @return $this
     */
    public function setSizechartId(int $sizeChartId): SizeChartInterface;

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): SizeChartInterface;

    /**
     * Get Sizes
     *
     * @return string
     */
    public function getSizes(): string;

    /**
     * Set Sizes
     *
     * @param string $sizes
     * @return $this
     */
    public function setSizes(string $sizes): SizeChartInterface;

    /**
     * Get Bust
     *
     * @return string
     */
    public function getBust(): string;

    /**
     * Set Bust
     *
     * @param string $bust
     * @return $this
     */
    public function setBust(string $bust): SizeChartInterface;

    /**
     * Get Waist
     *
     * @return string
     */
    public function getWaist(): string;

    /**
     * Set Waist
     *
     * @param string $waist
     * @return $this
     */
    public function setWaist(string $waist): SizeChartInterface;

    /**
     * Get Hip
     *
     * @return string
     */
    public function getHip(): string;

    /**
     * Set Hip
     *
     * @param string $hip
     * @return $this
     */
    public function setHip(string $hip): SizeChartInterface;

    /**
     * Get Status
     *
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * Set Status
     *
     * @param int $status
     * @return SizeChartInterface
     */
    public function setStatus(int $status): SizeChartInterface;

    /**
     * Get Content
     *
     * @return string|null
     */
    public function getContent(): ?string;

    /**
     * Set Content
     *
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): SizeChartInterface;
}

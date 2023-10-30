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

namespace MageINIC\SizeChart\Controller\Adminhtml\SizeChart;

use MageINIC\SizeChart\Api\SizeChartRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class SizeChart InlineEdit
 */
class InlineEdit extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'MageINIC_SizeChart::mass_action';

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var SizeChartRepositoryInterface
     */
    private $sizeChartRepository;

    /**
     * InlineEdit constructor.
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param SizeChartRepositoryInterface $sizeChartRepository
     */
    public function __construct(
        Context                      $context,
        JsonFactory                  $jsonFactory,
        SizeChartRepositoryInterface $sizeChartRepository
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->sizeChartRepository = $sizeChartRepository;
    }

    /**
     * Inline edit action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $modelId) {
                    try {
                        $this->saveData($modelId, $postItems);
                    } catch (\Exception $e) {
                        $messages[] = "[SizeChart ID: {$modelId}]  {$e->getMessage()}";
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Save Data
     *
     * @param int $modelId
     * @param array $postItems
     * @return void
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     */
    public function saveData($modelId, $postItems)
    {
        $model = $this->sizeChartRepository->getById($modelId);
        $model->setData(array_merge($model->getData(), $postItems[$modelId]));
        $this->sizeChartRepository->save($model);
    }
}

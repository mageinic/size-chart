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

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use MageINIC\SizeChart\Model\SizeChart;
use MageINIC\SizeChart\Model\SizeChartRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use RuntimeException;

/**
 * Class SizeChart Save
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'MageINIC_SizeChart::save';

    /**
     * @var SizeChart
     */
    protected $sizeChartModel;

    /**
     * @var SizeChartRepository
     */
    private SizeChartRepository $sizeChartRepository;

    /**
     * @param Context $context
     * @param SizeChart $sizeChartModel
     * @param SizeChartRepository $sizeChartRepository
     */
    public function __construct(
        Context             $context,
        SizeChart           $sizeChartModel,
        SizeChartRepository $sizeChartRepository
    ) {
        $this->sizeChartModel = $sizeChartModel;
        parent::__construct($context);
        $this->sizeChartRepository = $sizeChartRepository;
    }

    /**
     * Save Sizechart
     *
     * @return Redirect
     * @throws NoSuchEntityException
     */
    public function execute(): Redirect
    {
        $data = $this->getRequest()->getPostValue();

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->sizeChartModel;
            $id = $this->getRequest()->getParam('sizechart_id');
            if ($id) {
                $this->sizeChartRepository->getById($id);
            }
            $model->setData($data);
            $this->_eventManager->dispatch(
                'reporttoadmin_reasons_prepare_save',
                ['report' => $model, 'request' => $this->getRequest()]
            );

            try {
                $this->sizeChartRepository->save($model);
                $this->messageManager->addSuccessMessage(__('SizeChart Successfully saved'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['sizechart_id' => $model->getId(), '_current' => true]
                    );
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException|RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the SizeChart'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath(
                '*/*/edit',
                ['sizechart_id' => $this->getRequest()->getParam('sizechart_id')]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }
}

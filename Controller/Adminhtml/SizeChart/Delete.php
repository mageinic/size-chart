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
use MageINIC\SizeChart\Api\SizeChartRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use MageINIC\SizeChart\Controller\Adminhtml\SizeChart;
use Magento\Framework\Registry;

/**
 * Class SizeChart Delete
 */
class Delete extends SizeChart implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'MageINIC_SizeChart::delete';

    /**
     * @var SizeChartRepositoryInterface
     */
    public SizeChartRepositoryInterface $sizeChartRepository;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param SizeChartRepositoryInterface $sizeChartRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        SizeChartRepositoryInterface $sizeChartRepository
    ) {
        parent::__construct($context, $coreRegistry);
        $this->sizeChartRepository = $sizeChartRepository;
    }

    /**
     * Delete action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('sizechart_id');

        if ($id) {
            try {
                $this->sizeChartRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('You deleted the SizeChart'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath(
                    '*/*/edit',
                    ['sizechart_id' => $id]
                );
            }
        }

        $this->messageManager->addErrorMessage(__('We can\'t find a SizeChart to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}

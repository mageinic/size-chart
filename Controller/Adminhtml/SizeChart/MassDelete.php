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
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Ui\Component\MassAction\Filter;
use MageINIC\SizeChart\Model\ResourceModel\SizeChart\CollectionFactory;
use Magento\Backend\App\Action\Context;

/**
 * Class SizeChart MassDelete
 */
class MassDelete extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'MageINIC_SizeChart::delete';

    /**
     * @var Filter $filter
     */
    private $filter;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var SizeChartRepositoryInterface
     */
    private SizeChartRepositoryInterface $sizeChartRepository;

    /**
     * MassDelete constructor.
     *
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param SizeChartRepositoryInterface $sizeChartRepository
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        SizeChartRepositoryInterface $sizeChartRepository
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->sizeChartRepository = $sizeChartRepository;
        parent::__construct($context);
    }

    /**
     * MassDelete Action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        try {
            $logCollection = $this->filter->getCollection($this->collectionFactory->create());
            $itemsDeleted = 0;
            foreach ($logCollection as $item) {
                $this->sizeChartRepository->delete($item);
                $itemsDeleted++;
            }
            $this->messageManager->addSuccessMessage(__('A total of %1 SizeChart(s) were deleted.', $itemsDeleted));
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
}

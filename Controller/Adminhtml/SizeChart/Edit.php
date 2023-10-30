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
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use MageINIC\SizeChart\Controller\Adminhtml\SizeChart;
use MageINIC\SizeChart\Model\SizeChartFactory;

/**
 * Class SizeChart Edit
 */
class Edit extends SizeChart implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'MageINIC_SizeChart::edit';

    /**
     * @var PageFactory
     */
    private PageFactory $resultPageFactory;

    /**
     * @var SizeChartFactory
     */
    private SizeChartFactory $sizeChartFactory;

    /**
     * @var Registry
     */
    protected Registry $coreRegistry;

    /**
     * @var SizeChartRepositoryInterface
     */
    private SizeChartRepositoryInterface $sizeChartRepository;

    /**
     * Edit constructor.
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param SizeChartFactory $sizeChartFactory
     * @param SizeChartRepositoryInterface $sizeChartRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        SizeChartFactory $sizeChartFactory,
        SizeChartRepositoryInterface $sizeChartRepository
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->sizeChartFactory = $sizeChartFactory;
        $this->coreRegistry = $coreRegistry;
        $this->sizeChartRepository = $sizeChartRepository;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return Page|Redirect
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('sizechart_id');
        $model = $this->sizeChartFactory->create();
        if ($id) {
            $model = $this->sizeChartRepository->getById($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This SizeChart no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->coreRegistry->register('mageinic_size_chart', $model);
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit SizeChart') : __('New SizeChart'),
            $id ? __('Edit SizeChart') : __('New SizeChart')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('SizeChart'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New SizeChart'));
        return $resultPage;
    }
}

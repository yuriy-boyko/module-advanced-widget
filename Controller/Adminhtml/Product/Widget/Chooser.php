<?php
declare(strict_types=1);

namespace MageOS\AdvancedWidget\Controller\Adminhtml\Product\Widget;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Escaper;
use Magento\Framework\View\LayoutFactory;

class Chooser extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'MageOS_AdvancedWidget::widget_instance';

    /**
     * @param Context $context
     * @param RawFactory $resultRawFactory
     * @param LayoutFactory $layoutFactory
     * @param Escaper $escaper
     */
    public function __construct(
        Context $context,
        protected RawFactory $resultRawFactory,
        protected LayoutFactory $layoutFactory,
        protected Escaper $escaper
    ) {
        parent::__construct($context);
    }

    /**
     * Chooser Source action.
     *
     * @return Raw
     */
    public function execute(): Raw
    {
        $uniqId = (string)$this->getRequest()->getParam('uniq_id');
        $massAction = $this->getRequest()->getParam('use_massaction', false);
        $productTypeId = $this->getRequest()->getParam('product_type_id', null);

        $layout = $this->layoutFactory->create();
        $productsGrid = $layout->createBlock(
            \MageOS\AdvancedWidget\Block\Adminhtml\Product\Widget\Chooser::class,
            '',
            [
                'data' => [
                    'id' => $this->escaper->escapeHtml($uniqId),
                    'use_massaction' => $massAction,
                    'product_type_id' => $productTypeId,
                    'category_id' => (int)$this->getRequest()->getParam('category_id'),
                ],
            ]
        );

        $html = $productsGrid->toHtml();
        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents($html);
    }
}

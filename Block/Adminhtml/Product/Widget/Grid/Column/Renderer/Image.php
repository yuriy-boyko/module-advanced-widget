<?php
declare(strict_types=1);

namespace MageOS\AdvancedWidget\Block\Adminhtml\Product\Widget\Grid\Column\Renderer;

use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Image extends AbstractRenderer
{

    /**
     * @param Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param ImageHelper $imageHelper
     * @param array $data
     */
    public function __construct(
        private Context $context,
        private ProductRepositoryInterface $productRepository,
        private ImageHelper $imageHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Render product image in the grid
     *
     * @param DataObject $row
     * @return string
     * @throws NoSuchEntityException
     */
    public function render(DataObject $row): string
    {
        $productId = $row->getData('entity_id');
        $product = $this->productRepository->getById($productId);
        $imageUrl = $this->imageHelper->init($product, 'product_listing_thumbnail')->getUrl();
        if (!$imageUrl) {
            return '';
        }
        return '<img src="' . $imageUrl . '" width="75" height="75" alt="' . $row->getName() . '"/>';
    }
}

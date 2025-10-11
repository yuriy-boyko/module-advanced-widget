<?php
declare(strict_types=1);

namespace MageOS\AdvancedWidget\ViewModel;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image as ImageHelper;

class ProductData implements \Magento\Framework\View\Element\Block\ArgumentInterface
{

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param ImageHelper $imageHelper
     */
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected ImageHelper $imageHelper
    ) {
    }

    /**
     * @param int $id
     * @return ?ProductInterface
     */
    public function getProductById(int $id): ?ProductInterface
    {
        try {
            $product = $this->productRepository->getById($id);
            $product->setData(
                "mageos_main_image",
                $this->imageHelper->init(
                    $product,
                    'product_page_image_large'
                )->resize(700, 500)->getUrl()
            );
            $product->setData(
                "mageos_swatch_image",
                $this->imageHelper->init(
                    $product,
                    'swatch_image'
                )->resize(700, 500)->getUrl()
            );
            $product->setData(
                "mageos_small_image",
                $this->imageHelper->init(
                    $product,
                    'product_page_image_small'
                )->resize(350, 250)->getUrl()
            );
            $product->setData(
                "mageos_thumb_image",
                $this->imageHelper->init(
                    $product,
                    'product_thumbnail_image'
                )->resize(150, 107)->getUrl()
            );
        } catch (\Exception $e) {
            $product = null;
        }
        return $product;
    }
}

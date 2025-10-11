<?php
declare(strict_types=1);

namespace MageOS\AdvancedWidget\Block\Adminhtml\Product\Widget;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ResourceModel\Category;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use MageOS\AdvancedWidget\Block\Adminhtml\Product\Widget\Grid\Column\Renderer\Image;

class Chooser extends \Magento\Catalog\Block\Adminhtml\Product\Widget\Chooser
{

    /**
     * @param Context $context
     * @param Data $backendHelper
     * @param CategoryFactory $categoryFactory
     * @param CollectionFactory $collectionFactory
     * @param Category $resourceCategory
     * @param Product $resourceProduct
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        private Context $context,
        private Data $backendHelper,
        private CategoryFactory $categoryFactory,
        private CollectionFactory $collectionFactory,
        private Category $resourceCategory,
        private Product $resourceProduct,
        private StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $backendHelper,
            $categoryFactory,
            $collectionFactory,
            $resourceCategory,
            $resourceProduct,
            $data
        );
    }

    /**
     * Adds additional parameter to URL for loading only products grid
     * @return string
     */
    public function getGridUrl(): string
    {
        return $this->getUrl(
            'advanced_widget/product_widget/chooser',
            [
                'products_grid' => true,
                '_current' => true,
                'uniq_id' => $this->getId(),
                'use_massaction' => $this->getUseMassaction(),
                'product_type_id' => $this->getProductTypeId()
            ]
        );
    }

    /**
     * @param AbstractElement $element
     * @return AbstractElement
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function prepareElementHtml(AbstractElement $element): AbstractElement
    {
        $uniqId = $this->mathRandom->getUniqueHash($element->getId());
        $sourceUrl = $this->getUrl(
            'advanced_widget/product_widget/chooser',
            ['uniq_id' => $uniqId, 'use_massaction' => false]
        );

        $chooser = $this->getLayout()->createBlock(
            \Magento\Widget\Block\Adminhtml\Widget\Chooser::class
        )->setElement(
            $element
        )->setConfig(
            $this->getConfig()
        )->setFieldsetId(
            $this->getFieldsetId()
        )->setSourceUrl(
            $sourceUrl
        )->setUniqId(
            $uniqId
        );

        if ($element->getValue()) {
            $value = explode('/', $element->getValue());
            $productId = false;
            if (isset($value[0]) && isset($value[1]) && $value[0] == 'product') {
                $productId = $value[1];
            }
            $categoryId = isset($value[2]) ? $value[2] : false;
            $label = '';
            if ($categoryId) {
                $label = $this->_resourceCategory->getAttributeRawValue(
                    $categoryId,
                    'name',
                    $this->storeManager->getStore()
                ) . '/';
            }
            if ($productId) {
                $label .= $this->_resourceProduct->getAttributeRawValue(
                    $productId,
                    'name',
                    $this->storeManager->getStore()
                );
            }
            $chooser->setLabel($label);
        }

        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    /**
     * @return Extended
     * @throws \Exception
     */
    protected function _prepareColumns(): Extended
    {
        $this->addColumn(
            'chooser_thumbnail',
            [
                'header' => __('Image'),
                'index' => 'chooser_thumbnail',
                'renderer' => Image::class,
                'sortable' => false,
                'filter' => false,
                'header_css_class' => 'col-image',
                'column_css_class' => 'col-image'
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * Grid Row JS Callback
     *
     * @return string
     */
    public function getRowClickCallback(): string
    {
        if (!$this->getUseMassaction()) {
            $chooserJsObjectID = $this->getData('mageos_product_rows_id');
            return '
                function (grid, event) {
                    var trElement = Event.findElement(event, "tr");
                    var image = trElement.down("td").down("img").src;
                    var productId = trElement.down("td").next().innerHTML;
                    var ProductSku = trElement.down("td").next().next().innerHTML;
                    var productName = trElement.down("td").next().next().next().innerHTML;
                    var name = productName.replace(/^\s+|\s+$/g,"");
                    var sku = ProductSku.replace(/^\s+|\s+$/g,"");
                    var id = productId.replace(/^\s+|\s+$/g,"");
                    var selectionEvent = new CustomEvent("mageos_product_rows_selection' . $chooserJsObjectID . '", {detail: {id: id, name: name, sku: sku, image: image}});
                    window.dispatchEvent(selectionEvent);
                }
            ';
        }
    }

    /**
     * @param $id
     * @return string
     * @throws LocalizedException
     */
    public function getMainFormHtml($id): string
    {
        $chooser = $this->getLayout()->createBlock(
            Chooser::class
        )->setName(
            $this->mathRandom->getUniqueHash('mageos_products_grid')
        )->setUseMassaction(
            false
        )->setData(
            'mageos_product_rows_id',
            $id
        );

        return '<div id="mageos_modal_product_selection" style="display:none;">' .
        $chooser->toHtml() .
        '</div>';
    }
}

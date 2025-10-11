<?php
declare(strict_types=1);

namespace MageOS\AdvancedWidget\Block\Adminhtml\Renderer;

use MageOS\AdvancedWidget\Block\WidgetField\Title as ConfigTitle;
use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\LocalizedException;

class Title extends Template
{

    /**
     * @param AbstractElement $element
     * @return void
     * @throws LocalizedException
     */
    public function prepareElementHtml(AbstractElement $element): void
    {
        $element->setRenderer($this->getLayout()->createBlock(ConfigTitle::class));
    }
}

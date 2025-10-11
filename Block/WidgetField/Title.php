<?php
declare(strict_types=1);

namespace MageOS\AdvancedWidget\Block\WidgetField;

use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface as FormElementRenderer;

class Title extends Template implements FormElementRenderer
{

    /**
     * @var AbstractElement
     */
    private AbstractElement $element;

    /**
     * @var string
     */
    protected $_template = 'MageOS_AdvancedWidget::widget/field/title.phtml';

    /**
     * @param AbstractElement $element
     * @return string
     */
    public function render(
        AbstractElement $element
    ): string
    {
        $this->element = $element;
        return $this->toHtml();
    }

    /**
     * @return AbstractElement
     */
    public function getElement(): AbstractElement
    {
        return $this->element;
    }

    /**
     * @return array|mixed
     */
    public function getValues(): mixed
    {
        $values = $this->getElement()->getValue();
        return json_decode(urldecode($values), true) ?: [];
    }
}

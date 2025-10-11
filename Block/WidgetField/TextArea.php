<?php
declare(strict_types=1);

namespace MageOS\AdvancedWidget\Block\WidgetField;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Factory;

class TextArea extends \Magento\Backend\Block\Template
{

    /**
     * @param Context $context
     * @param Factory $elementFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        private Factory $elementFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @param AbstractElement $element
     * @return AbstractElement
     */
    public function prepareElementHtml(AbstractElement $element): AbstractElement
    {
        $input = $this->elementFactory->create("textarea", ['data' => $element->getData()]);
        $input->setId($element->getId());
        $input->setForm($element->getForm());
        $input->setClass("widget-option input-textarea admin__control-text");
        if ($element->getRequired()) {
            $input->addClass('required-entry');
        }

        $element->setData('after_element_html', $this->_getAfterElementHtml() . $input->getElementHtml());
        return $element;
    }

    /**
     * @return string
     */
    protected function _getAfterElementHtml(): string
    {
        $html = <<<HTML
            <style>
                .admin__field-control.control .control-value {
                    display: none !important;
                }
            </style>
        HTML;

        return $html;
    }
}

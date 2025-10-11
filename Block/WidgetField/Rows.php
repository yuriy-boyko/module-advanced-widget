<?php
declare(strict_types=1);

namespace MageOS\AdvancedWidget\Block\WidgetField;

use Magento\Backend\Block\Template\Context;
use Magento\Widget\Helper\Conditions;
use MageOS\AdvancedWidget\Block\Adminhtml\Renderer\Repeatable;
use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\LocalizedException;

class Rows extends Template
{

    /**
     * @var array
     */
    protected $rows = [];

    /**
     * @param Conditions $conditions
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        protected Conditions $conditions,
        Context $context,
        array $data = [],
    ) {
        parent::__construct($context, $data);

    }

    /**
     * @param AbstractElement $element
     * @return void
     * @throws LocalizedException
     */
    public function prepareElementHtml(AbstractElement $element): void
    {
        /** @var Repeatable $fieldRenderer */
        $fieldRenderer = $this->getLayout()->createBlock(Repeatable::class);
        if(str_contains($element->getName(),'repeatable_') && !empty($element->getValue())) {
            $element->setData('value', $this->conditions->decode($element->getValue()));
        }
        $fieldRenderer->setRows($this->rows);
        $element->setRenderer($fieldRenderer);
    }
}

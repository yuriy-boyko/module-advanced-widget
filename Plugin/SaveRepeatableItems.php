<?php
declare(strict_types=1);

namespace MageOS\AdvancedWidget\Plugin;

use Magento\Widget\Helper\Conditions;

class SaveRepeatableItems
{
    /**
     * @param Conditions $conditions
     */
    public function __construct(
        protected Conditions $conditions
    )
    {}

    /**
     * @param \Magento\Widget\Model\Widget $subject
     * @param $type
     * @param $params
     * @param $asIs
     * @return array
     */
    public function beforeGetWidgetDeclaration(
        \Magento\Widget\Model\Widget $subject,
        $type,
        $params,
        $asIs
    ): array
    {
        foreach ($params as $name => $value) {
            if (str_contains($name, 'repeatable_') && is_array($value)) {
                $params[$name] = $this->serialize($value);
            }
        }
        return [$type, $params, $asIs];
    }

    /**
     * @param array $value
     * @return string
     */
    public function serialize(array $value): string
    {
        return str_replace("\"", "|", $this->conditions->encode($value));
    }
}

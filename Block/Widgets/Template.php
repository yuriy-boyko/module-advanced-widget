<?php
declare(strict_types=1);

namespace MageOS\AdvancedWidget\Block\Widgets;

class Template extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{

    /**
     * @param array $expressions
     * @param array $params
     * @return string|null
     */
    public function resolveExpression(array $expressions, array $params): ?string
    {
        $output = [];

        foreach ($expressions as $expression => $condition) {
            if (is_string($expression)) {
                if ($condition) {
                    $params["0"] = $condition;
                }
                if (
                    $expression = $this->evaluateExpression(
                        $expression,
                        $params
                    )
                ) {
                    $output[] = $expression;
                }
            } else {
                if ($expression = $this->evaluateExpression(
                    $condition,
                    $params
                )) {
                    $output[] = $expression;
                }
            }
        }

        return $output ? join(' ', $output) : null;
    }

    /**
     * Parse expression string.
     *
     * @param string $expression
     *
     * @return array
     */
    protected function parseExpression($expression): array
    {
        static $expressions;

        if (isset($expressions[$expression])) {
            return $expressions[$expression];
        }

        $optionals = [];

        // match all optionals
        $output = preg_replace_callback(
            '/\[((?:[^\[\]]+|(?R))*)\]/',
            function ($matches) use (&$optionals) {
                return '%' . array_push($optionals, $matches[1]) . '$s';
            },
            $expression,
        );

        // match all parameters
        preg_match_all(
            '/\{\s*(@?)(!?)(\w+)\s*(?::\s*([^{}]*(?:\{(?-1)\}[^{}]*)*))?\}/',
            $output,
            $parameters,
            PREG_SET_ORDER,
        );

        return $expressions[$expression] = [$output, $parameters, $optionals];
    }

    /**
     * Evaluate expression string.
     *
     * @param string $expression
     * @param array  $params
     *
     * @return string
     */
    protected function evaluateExpression(string $expression, array $params = []): string
    {
        if (!str_contains($expression, '{')) {
            return trim($expression);
        }

        [$output, $parameters, $optionals] = $this->parseExpression($expression);

        foreach ($parameters as $match) {
            [$parameter, $empty, $negate, $name] = $match;

            $regex = isset($match[4]) ? "/^({$match[4]})$/" : '';
            $value = $params[$name] ?? '';
            $result = $regex
                ? preg_match($regex, $value)
                : $value || (is_string($value) && $value !== '');

            if ($result xor $negate) {
                $output = str_replace($parameter, $empty ? '' : $value, $output);
            } else {
                return '';
            }
        }

        if ($optionals) {
            $args = [$output];

            foreach ($optionals as $match) {
                $args[] = $this->evaluateExpression($match, $params);
            }

            $output = call_user_func_array('sprintf', $args);
        }

        return trim($output);
    }

    /**
     * @param string $data
     * @param string|null $allowableTags
     * @param bool $allowHtmlEntities
     * @return string
     */
    public function stripTags(
        $data,
        $allowableTags = '<div><h1><h2><h3><h4><h5><h6><p><ul><ol><li><img><svg><br><hr><span><strong><em><i><b><s><mark><sup><del>',
        $allowHtmlEntities = false
    ): string {
        return parent::stripTags($data, $allowableTags);
    }
}

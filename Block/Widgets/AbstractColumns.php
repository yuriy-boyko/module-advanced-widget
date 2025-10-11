<?php
declare(strict_types=1);

namespace MageOS\AdvancedWidget\Block\Widgets;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;
use Magento\Widget\Helper\Conditions;

class AbstractColumns extends Template implements BlockInterface
{

    /**
     * @param Conditions $conditionsHelper
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        private Conditions $conditionsHelper,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }


    public function getRepeatableField(string $name): array {
        $field = $this->getData($name);
        return $field ? $this->conditionsHelper->decode($field) : [];
    }

    /**
     * @param string $name
     * @return array
     * Returns an array of DataObject
     */
    public function getRepeatableFieldAsObject(string $name): array
    {
        $content = $this->getRepeatableField($name);

        if ($content && is_array($content)) {
            return array_map(
                function ($data) {
                    return new DataObject($data);
                },
                $content
            );
        }

        return $content;

    }

    /**
     * @return array
     */
    public function getConditions(): array
    {
        $conditions = $this->getData('conditions_encoded')
            ? $this->getData('conditions_encoded')
            : $this->getData('conditions');

        return $conditions ? $this->conditionsHelper->decode($conditions) : [];
    }

    /**
     * @param string $path
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImageUrlByPath(string $path): string
    {
        return $this->_storeManager
                ->getStore()
                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $path;
    }

    /**
     * @param string $imageField
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImageUrl(string $imageField = 'image'): string
    {
        $url = $this->getData($imageField);
        if (!$url) {
            return '';
        }

        if (strpos($url, $this->getMediaUrl()) !== 0) {
            return $this->getMediaUrl() . '/' . $url;
        }

        return $url;
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getMediaUrl(): string
    {
        return $this->_storeManager
            ->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * @param $urlLink
     * @return string
     */
    public function getPreparedUrl($urlLink): string
    {
        $preparedLink = $urlLink;
        if (strpos($urlLink, 'http') === false) {
            $urlLink = explode('?', $urlLink);
            $preparedLink = $this->getUrl(trim($urlLink[0], '/'));
            if (!empty($urlLink[1])) {
                $preparedLink = rtrim($preparedLink, '/') . '?' . $urlLink[1];
            }
        }
        return $preparedLink;
    }

    /**
     * @param string $descriptionField
     * @return string
     */
    public function getPreparedDescription(string $descriptionField = 'description'): string
    {
        return str_replace('\EOL', '<br />', $this->getData($descriptionField));
    }
}

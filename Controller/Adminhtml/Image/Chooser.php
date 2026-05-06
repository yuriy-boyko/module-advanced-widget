<?php
declare(strict_types=1);

namespace MageOS\AdvancedWidget\Controller\Adminhtml\Image;

use Magento\Backend\App\Action\Context;
use Magento\Cms\Model\Wysiwyg\Images\GetInsertImageContent;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Chooser extends \Magento\Cms\Controller\Adminhtml\Wysiwyg\Images\OnInsert
{
    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param RawFactory $resultRawFactory
     * @param GetInsertImageContent $getInsertImageContent
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        protected RawFactory $resultRawFactory,
        protected GetInsertImageContent $getInsertImageContent,
        protected StoreManagerInterface $storeManager
    ) {
        parent::__construct($context, $coreRegistry, $resultRawFactory, $getInsertImageContent);
    }

    /**
     * @return Raw
     * @throws NoSuchEntityException
     */
    public function execute(): Raw
    {
        $data = $this->getRequest()->getParams();
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_WEB);
        $content = $this->getInsertImageContent->execute(
            $data['filename'],
            $data['force_static_path'],
            $data['as_is'],
            isset($data['store']) ? (int) $data['store'] : null
        );
        $content = str_replace($mediaUrl, '', (string)$content);
        $content = '/' . ltrim($content, '/');
        return $this->resultRawFactory->create()->setContents($content);
    }
}

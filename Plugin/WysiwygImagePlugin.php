<?php
declare(strict_types=1);

namespace MageOS\AdvancedWidget\Plugin;

use Magento\Cms\Helper\Wysiwyg\Images as WysiwygImageHelper;
use Magento\Cms\Block\Adminhtml\Wysiwyg\Images\Content as WysiwygImageContent;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\FileSystem;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Filesystem\Directory\ReadInterface as DirectoryRead;

class WysiwygImagePlugin
{
    /**
     * @var DirectoryRead
     */
    private DirectoryRead $mediaDir;

    public function __construct(
        FileSystem $fileSystem,
        private RequestInterface $request
    ) {
        $this->mediaDir = $fileSystem->getDirectoryRead(DirectoryList::MEDIA);
    }

    /**
     * @param WysiwygImageHelper $subject
     * @param callable $proceed
     * @param string $filename
     * @param bool $renderAsTag
     * @return string
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetImageHtmlDeclaration(
        WysiwygImageHelper $subject,
        callable $proceed,
        string $filename,
        bool $renderAsTag = false
    ): string
    {
        if ($this->shouldResultBeRelativePath($renderAsTag)) {
            $absolutePath = $subject->getCurrentPath() . '/' . $filename;
            return $this->mediaDir->getRelativePath($absolutePath);
        }
        return $proceed($filename, $renderAsTag);
    }

    /**
     * @param WysiwygImageContent $subject
     * @param callable $proceed
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetOnInsertUrl(
        WysiwygImageContent $subject,
        callable $proceed
    ): string
    {
        return $subject->getUrl(
            'advanced_widget/image/chooser',
            ['widget' => $this->request->getParam('widget')]
        );
    }

    /**
     * @param bool $renderAsTag
     * @return bool
     */
    private function shouldResultBeRelativePath(bool $renderAsTag): bool
    {
        if (!$renderAsTag && $this->request->getParam('widget')) {
            return true;
        }
        return false;
    }
}

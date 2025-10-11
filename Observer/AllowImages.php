<?php
declare(strict_types=1);

namespace MageOS\AdvancedWidget\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AllowImages implements ObserverInterface
{
    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer): void
    {
        $observer->getResult()->isAllowed = true;
    }
}

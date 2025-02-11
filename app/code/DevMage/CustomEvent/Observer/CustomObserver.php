<?php

declare(strict_types=1);

namespace DevMage\CustomEvent\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;

class CustomObserver implements ObserverInterface
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $customData = $observer->getEvent()->getData('custom_data');
        $this->logger->info('Event devmage_custom_event execution. Data: ' . $customData);
    }
}

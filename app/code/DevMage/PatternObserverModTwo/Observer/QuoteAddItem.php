<?php

declare(strict_types=1);

namespace DevMage\PatternObserverModTwo\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;

class QuoteAddItem implements ObserverInterface
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
        $this->logger->debug('Executing OBSERVER 2');
    }
}

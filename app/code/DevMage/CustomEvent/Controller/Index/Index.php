<?php

declare(strict_types=1);

namespace DevMage\CustomEvent\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Index implements HttpGetActionInterface
{
    /**
     * @param ManagerInterface $eventManager
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        private readonly ManagerInterface $eventManager,
        private readonly ResultFactory $resultFactory
    ) {
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $this->eventManager->dispatch(
            'devmage_custom_event',
            ['custom_data' => 'Controller Custom event DATA.']
        );

        $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $result->setContents('<h1>Custom event triggered!</h1><p>Check Magento log.</p>');
        return $result;
    }
}

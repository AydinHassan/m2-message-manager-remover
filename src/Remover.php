<?php

declare(strict_types=1);

namespace TrashPanda\MessageManagerRemover;

use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Message\MessageInterface;

class Remover
{
    /**
     * @var ManagerInterface
     */
    private $messageManager;

    public function __construct(ManagerInterface $messageManager)
    {
        $this->messageManager = $messageManager;
    }

    public function removeAll(): int
    {
        $removed = 0;
        foreach ($this->messageManager->getMessages()->getItems() as $message) {
            $this->removeMessage($message);
            $removed++;
        }
        return $removed;
    }

    public function removeByRegex(string $pattern): int
    {
        $matches = 0;
        foreach ($this->messageManager->getMessages()->getItems() as $message) {
            if (preg_match($pattern, $message->getText())) {
                $this->removeMessage($message);
                $matches++;
            }
        }

        return $matches;
    }

    public function removeLastAddedMessage(): void
    {
        $message = $this->messageManager
            ->getMessages()
            ->getLastAddedMessage();

        if (!$message) {
            throw new \RuntimeException('No message found');
        }

        $this->removeMessage($message);
    }

    private function removeMessage(MessageInterface $message): void
    {
        $id = bin2hex(random_bytes(10));
        $message->setIdentifier($id);

        $this->messageManager
            ->getMessages()
            ->deleteMessageByIdentifier($id);
    }
}

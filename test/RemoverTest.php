<?php

declare(strict_types=1);

namespace TrashPanda\MessageManagerRemoverTest;

use Magento\Framework\Message\Collection;
use Magento\Framework\Message\Manager;
use Magento\Framework\Message\Notice;
use PHPUnit\Framework\TestCase;
use TrashPanda\MessageManagerRemover\Remover;

class RemoverTest extends TestCase
{
    /**
     * @var Collection
     */
    private $messageCollection;

    /**
     * @var Remover
     */
    private $remover;

    public function setUp(): void
    {
        $this->messageCollection = new Collection();
        $manager = $this->createMock(Manager::class);
        $manager->expects($this->any())
            ->method('getMessages')
            ->willReturn($this->messageCollection);

        $this->remover = new Remover($manager);
    }
    
    public function testRemoveLastAddedMessageThrowsExceptionIfNoMessageFound(): void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('No message found');

        $this->remover->removeLastAddedMessage();
    }

    public function testCanRemoveLastAddedMessage(): void
    {
        $this->messageCollection->addMessage(new Notice('First Message'));
        $this->messageCollection->addMessage(new Notice('Second Message'));

        self::assertEquals(2, $this->messageCollection->getCount());

        $this->remover->removeLastAddedMessage();

        self::assertEquals(1, $this->messageCollection->getCount());
    }

    public function testRemoveByRegexMatchingOne(): void
    {
        $this->messageCollection->addMessage(new Notice('First Message'));
        $this->messageCollection->addMessage(new Notice('Second Message'));

        self::assertEquals(2, $this->messageCollection->getCount());

        $numRemoved = $this->remover->removeByRegex('/First/');

        self::assertEquals(1, $numRemoved);
        self::assertEquals(1, $this->messageCollection->getCount());
    }

    public function testRemoveByRegexMatchingMultiple(): void
    {
        $this->messageCollection->addMessage(new Notice('First Message'));
        $this->messageCollection->addMessage(new Notice('Second Message'));

        self::assertEquals(2, $this->messageCollection->getCount());

        $numRemoved = $this->remover->removeByRegex('/Message/');

        self::assertEquals(2, $numRemoved);
        self::assertEquals(0, $this->messageCollection->getCount());
    }

    public function testRemoveAll(): void
    {
        $this->messageCollection->addMessage(new Notice('First Message'));
        $this->messageCollection->addMessage(new Notice('Second Message'));
        $this->messageCollection->addMessage(new Notice('Third Message'));

        self::assertEquals(3, $this->messageCollection->getCount());

        $numRemoved = $this->remover->removeAll();

        self::assertEquals(3, $numRemoved);
        self::assertEquals(0, $this->messageCollection->getCount());
    }
}

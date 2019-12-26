<h1 align="center">Magento 2 Message Manager Remover</h1>

<p align="center">
    <a href="https://travis-ci.org/AydinHassan/m2-message-manager-remover" title="Build Status" target="_blank">
        <img src="https://img.shields.io/travis/AydinHassan/m2-message-manager-remover/master.svg?style=flat-square&label=Linux" />
    </a>
</p>

<p align="center">A small utility to remove messages added to the message manager</p>

## Installation

```sh
$ composer require trash-panda/m2-message-manager-remover
$ php bin/magento setup:upgrade
```

## Usage

Inject `\TrashPanda\MessageManagerRemover\Remover` wherever you need to remove a message. The class has two public
methods for removing messages.

`removeLastAddedMessage` will remove the last added message or throw a \RuntimeException if one does not exist
`removeByRegex` will remove all message matching a given `preg_match` compatible regex. It will return the number of messages removed
'removeAll' will remove all messages from the message manager

## Examples

```php
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$messageManager = $om->create(\Magento\Framework\Message\ManagerInterface::class);

$messageManager->addNotice('First message');
$messageManager->addNotice('Second message');

$remover = new Remover($messageManager);
$remover->removeLastAddedMessage(); //only remove "Second Message"
$remover->removeByRegex('/message/'); //remove all message matching regex '/message/'
$remover->removeAll(); //remove all messages

```


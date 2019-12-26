<h1 align="center">Magento 2 OpCache Monitor</h1>

<p align="center">Monitor and control PHP OpCache from the Admin </p>

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


# App Mail

Mail support for the app using the [Mail Service](https://github.com/tobento-ch/service-mail).

## Table of Contents

- [Getting Started](#getting-started)
    - [Requirements](#requirements)
- [Documentation](#documentation)
    - [App](#app)
    - [Mail Boot](#mail-boot)
        - [Mail Config](#mail-config)
        - [Creating And Sending Messages](#creating-and-sending-messages)
        - [Message Templating](#message-templating)
        - [Queuing Messages](#queuing-messages)
- [Credits](#credits)
___

# Getting Started

Add the latest version of the app mail project running this command.

```
composer require tobento/app-mail
```

## Requirements

- PHP 8.0 or greater

# Documentation

## App

Check out the [**App Skeleton**](https://github.com/tobento-ch/app-skeleton) if you are using the skeleton.

You may also check out the [**App**](https://github.com/tobento-ch/app) to learn more about the app in general.

## Mail Boot

The mail boot does the following:

* installs and loads mail config file
* implements mail interfaces

```php
use Tobento\App\AppFactory;
use Tobento\Service\Mail\MailerInterface;
use Tobento\Service\Mail\MailersInterface;
use Tobento\Service\Mail\RendererInterface;
use Tobento\Service\Mail\MessageFactoryInterface;
use Tobento\Service\Mail\QueueHandlerInterface;
use Tobento\Service\Mail\Symfony\EmailFactoryInterface;

// Create the app
$app = (new AppFactory())->createApp();

// Add directories:
$app->dirs()
    ->dir(realpath(__DIR__.'/../'), 'root')
    ->dir(realpath(__DIR__.'/../app/'), 'app')
    ->dir($app->dir('app').'config', 'config', group: 'config')
    ->dir($app->dir('root').'public', 'public')
    ->dir($app->dir('root').'vendor', 'vendor');

// Adding boots
$app->boot(\Tobento\App\Mail\Boot\Mail::class);
$app->booting();

// Implemented interfaces:
$mailer = $app->get(MailerInterface::class);
$mailers = $app->get(MailersInterface::class);
$renderer = $app->get(RendererInterface::class);
$messageFactory = $app->get(MessageFactoryInterface::class);
$queueHandler = $app->get(QueueHandlerInterface::class);
$emailFactory = $app->get(EmailFactoryInterface::class);

// Run the app
$app->run();
```

### Mail Config

The configuration for the mail is located in the ```app/config/mail.php``` file at the default [**App Skeleton**](https://github.com/tobento-ch/app-skeleton) config location where you can specify the mailers for your application.

### Creating And Sending Messages

```php
use Tobento\Service\Mail\MailerInterface;
use Tobento\Service\Mail\Message;

class SomeService
{
    public function send(MailerInterface $mailer): void
    {
        $message = (new Message())
            // you may set a from address overwriting 
            // the defaults defined in the mail config file
            ->from('from@example.com')
            
            ->to('to@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('replyto@example.com')
            ->subject('Subject')
            //->textTemplate('welcome-text')
            //->htmlTemplate('welcome')
            //->text('Lorem Ipsum')
            ->html('<p>Lorem Ipsum</p>');

        $mailer->send($message);
    }
}
```

Check out the [Mail Service - Creating And Sending Messages](https://github.com/tobento-ch/service-mail#creating-and-sending-messages) section to learn more about it.

### Message Templating

The [Mail Boot](#mail-boot) automatically boots the [App View Boot](https://github.com/tobento-ch/app-view#view-boot) to support message templates out of the box.

Read more about templating in the [Mail Service - Templating](https://github.com/tobento-ch/service-mail#templating) section.

### Queuing Messages

Sending emails can be a time-consuming task, you may queue mail messages for background sending to mitigate this issue.

To queue mail messages, simply add the [Queue Parameter](https://github.com/tobento-ch/service-mail#queue) to your message:

**Example**

```php
use Tobento\Service\Mail\MailerInterface;
use Tobento\Service\Mail\Message;
use Tobento\Service\Mail\Parameter;

class SomeService
{
    public function send(MailerInterface $mailer): void
    {
        $message = (new Message())
            ->to('to@example.com')
            ->subject('Subject')
            ->text('Lorem Ipsum')
            ->parameter(new Parameter\Queue(
                // you may set a specific queue,
                // otherwise the default will be used.
                name: 'mails',
                // you may specify a delay in seconds:
                delay: 30,
                // you may specify how many times to retry:
                retry: 3,
                // you may specify a priority:
                priority: 100,
                // you may specify if you want to encrypt the message:
                encrypt: true,
                // you may specify if you want to render the message templates
                // before queuing:
                renderTemplates: false, // true default
            ));

        $mailer->send($message);
    }
}
```

The [Mail Boot](#mail-boot) automatically boots the [App Queue Boot](https://github.com/tobento-ch/app-queue#queue-boot) to support queing messages out of the box.

You will only need to configure your queues in the [Queue Config](https://github.com/tobento-ch/app-queue#queue-config) file.

# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)
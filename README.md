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
            - [Writing Views Using The Mail CSS](#writing-views-using-the-mail-css)
            - [Templated Message](#templated-message)
        - [Queuing Messages](#queuing-messages)
- [Credits](#credits)
___

# Getting Started

Add the latest version of the app mail project running this command.

```
composer require tobento/app-mail
```

## Requirements

- PHP 8.4 or greater

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
$app = new AppFactory()->createApp();

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
        $message = new Message()
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

Read more about general templating in the [Mail Service - Templating](https://github.com/tobento-ch/service-mail#templating) section.

#### Writing Views Using The Mail CSS

You may use the provided ```mail.css``` to quickly write simple formatted content views. The mail.css is actually the same as the [basis.css](https://docs.tobento.ch/css-basis) except being adjusted for HTML emails.

**Example Using The Content Class**

```php
<!DOCTYPE html>
<html lang="<?= $view->esc($view->get('htmlLang', 'en')) ?>">
    <head>
        <?= $view->render('mail/inc/head') ?>
    </head>
    <body>
        <!--[if mso]><table><tr><td width="580"><![endif]-->
        <div class="container">
            <!-- you may create and render a header view -->
            <?= $view->render('mail/inc/header') ?>
        
            <!-- you may use the content class -->
            <div class="content">
                <h1>First heading</h1>
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
                <h2>Second heading</h2>
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
                <ul>
                    <li>Coffee</li>
                    <li>Tea
                    <ul>
                        <li>Black tea</li>
                        <li>Green tea</li>
                    </ul>
                    </li>
                    <li>Milk</li>
                </ul>
                <h3>Third heading</h3>
                <p>Lorem ipsum dolor sit amet, <a href="#">consetetur</a> sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
                <ol>
                    <li>Coffee</li>
                    <li>Tea
                    <ol>
                        <li>Black tea</li>
                        <li>Green tea</li>
                    </ol>
                    </li>
                    <li>Milk</li>
                </ol>
                <blockquote>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</blockquote>
                <p>Lorem ipsum dolor sit amet, consetetur <em>sadipscing elitr</em>, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
                <dl>
                    <dt>Coffee</dt>
                    <dd>- black hot drink</dd>
                    <dt>Milk</dt>
                    <dd>- white cold drink</dd>
                </dl>
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
                <h4>Fourth heading</h4>
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
                <pre>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</pre>
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
                <table>
                    <thead>
                        <tr>
                            <th>One</th>
                            <th>Two</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Three</td>
                            <td>Four</td>
                        </tr>
                        <tr>
                            <td>Five</td>
                            <td>Six</td>
                        </tr>
                        <tr>
                            <td>Seven</td>
                            <td>Eight</td>
                        </tr>
                        <tr>
                            <td>Nine</td>
                            <td>Ten</td>
                        </tr>
                        <tr>
                            <td>Eleven</td>
                            <td>Twelve</td>
                        </tr>
                    </tbody>
                </table>
                <h5>Fifth heading</h5>
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
                <h6>Sixth heading</h6>
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
            </div>
            
            <!-- you may create and render a footer view -->
            <?= $view->render('mail/inc/footer') ?>
        </div>
        <!--[if mso]></td></tr></table><![endif]-->
    </body>
</html>
```

**Example Using Css Classes**

```php
<!DOCTYPE html>
<html lang="<?= $view->esc($view->get('htmlLang', 'en')) ?>">
    <head>
        <?= $view->render('mail/inc/head') ?>
    </head>
    <body>
        <!--[if mso]><table><tr><td width="580"><![endif]-->
        <div class="container">
            <!-- you may create and render a header view -->
            <?= $view->render('mail/inc/header') ?>
            
            <h2 class="title text-m mt-m">Thematic Horizontal Break</h2>
            <hr class="line-s">
            <hr class="line-m my-xl">
            <hr class="line-l">
            
            <h2 class="title text-m">Links</h2>
            <a href="#">A Tag</a>

            <h2 class="title text-m mt-m">Typography - Text Sizes</h2>
            <p class="text-xxs">text-xxs</p>
            <p class="text-xs">text-xs</p>
            <p class="text-s">text-s</p>
            <p class="text-m">text-m</p>
            <p class="text-l">text-l</p>
            <p class="text-xl">text-xl</p>
            <p class="text-xxl">text-xxl</p>
            <p class="text-body">text-body</p>

            <h2 class="title text-m mt-m">Typography - Text Weight</h2>
            <p class="text-100">thin</p>
            <p class="text-300">light</p>
            <p class="text-400">regular</p>
            <p class="text-500">medium</p>
            <p class="text-700">bold</p>
            <p class="text-900">black</p>

            <h2 class="title text-m mt-m">Typography - Text Alignment</h2>
            <p class="text-left">LEFT Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
            <p class="text-right">RIGHT Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
            <p class="text-center">CENTER Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
            <p class="text-justify">JUSTIFY Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>

            <h2 class="title text-m mt-m">Text Transformation</h2>
            <p class="text-capitalize">text-capitalize: Lorem ipsum</p>
            <p class="text-uppercase">text-uppercase: Lorem ipsum</p>
            <p class="text-lowercase">text-lowercase: Lorem ipsum</p>
            <p class="text-underline">text-underline: Lorem ipsum</p>
            <p class="text-italic">text-italic: Lorem ipsum</p>

            <h2 class="title text-m mt-m">Titles</h2>
            <h1 class="title">Lorem ipsum</h1>
            <h2 class="subtitle">Lorem ipsum</h2>

            <h2 class="title text-m mt-m">Fonts</h2>
            <p class="font-primary">font-primary: Lorem ipsum</p>
            <p class="font-secondary">font-secondary: Lorem ipsum</p>

            <h2 class="title text-m mt-m">Signal Colors</h2>
            <p class="text-success">text-success</p>
            <p class="background-success">background-success</p>
            <p class="text-warning">text-warning</p>
            <p class="background-warning">background-warning</p>
            <p class="text-error">text-error</p>
            <p class="background-error">background-error</p>
            <p class="text-info">text-info</p>
            <p class="background-info">background-info</p>
            <p class="text-highlight">text-highlight</p>
            <p class="background-highlight">background-highlight</p>

            <h2 class="title text-m mt-m">Design Colors</h2>
            <p class="text-primary">text-primary</p>
            <p class="background-primary">background-primary</p>
            <p class="text-secondary">text-secondary</p>
            <p class="background-secondary">background-secondary</p>

            <h2 class="title text-m mt-m">Button Sizes</h2>
            <a class="button text-xxs" href="#">Label</a>
            <a class="button text-xs" href="#">Label</a>
            <a class="button text-s" href="#">Label</a>
            <a class="button text-m background-success" href="#">Label</a>
            <a class="button text-l" href="#">Label</a>
            <a class="button text-xl" href="#">Label</a>
            <a class="button text-xxl" href="#">Label</a>
            <a class="button text-body" href="#">Label</a>
            <div><a class="button primary fit" href="#">Fit Label</a></div>

            <h2 class="title text-xs mt-m">Button Styles</h2>
            <a class="button text-xs" href="#">Button normal</a>
            <a class="button text-xs primary" href="#">Button primary</a>
            <a class="button text-xs raw" href="#">Button raw</a>

            <h2 class="title text-m mt-m">Bulleted List</h2>
            <ul class="bulleted text-s">
                <li>LOREM</li>
                <li>LOREM</li>
                <li>LOREM</li>
                <li>LOREM</li>
            </ul>

            <h2 class="title text-m mt-m">Numbered List</h2>
            <ol class="numbered text-s">
                <li>Coffee</li>
                <li>Tea
                <ol class="numbered">
                    <li>Black tea</li>
                    <li>Green tea</li>
                </ol>
                </li>
                <li>Milk</li>
            </ol>

            <h2 class="title text-m mt-m">Description List</h2>
            <dl class="vertical text-xs">
                <dt>Coffee</dt>
                <dd>- black hot drink</dd>
                <dd>The part of the Internet that contains websites and web pages</dd>
                <dt>Milk</dt>
                <dd>- white cold drink</dd>
            </dl>
            
            <!-- you may create and render a footer view -->
            <?= $view->render('mail/inc/footer') ?>
        </div>
        <!--[if mso]></td></tr></table><![endif]-->
    </body>
</html>
```

#### Templated Message

The ```TemplatedMessage``` class contains a few simple methods to help you build transactional email messages using content blocks.

```php
use Tobento\App\Mail\TemplatedMessage;
use Tobento\App\Mail\Block;
use Tobento\Service\Mail\MailerInterface;

class SomeService
{
    public function send(MailerInterface $mailer): void
    {
        $message = new TemplatedMessage()
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
            //->html('<p>Lorem Ipsum</p>')
            
            // Building message using content block methods:
            // Headings:
            ->h1('First heading')
            ->h2('Second heading')
            ->h3('Third heading')
            ->h4('Fourth heading')
            ->h5('Fifth heading')
            ->h6('Sixth heading')
            
            // Thematic Horizontal Break:
            ->hr(
                size: 'l', // 's', 'm' or 'l'
                attributes: ['class' => 'my-m'] // you may set margins
            )
            
            // Content:
            ->txt('Lorem ipsum')
            ->htmlBlock('<br>') // must be escaped!
            
            // Link and button:
            ->link(
                url: 'https://example.com',
                label: 'Label',
                attributes: ['class' => 'text-xl'] // you may add classes
            )
            
            ->button(
                url: 'https://example.com',
                label: 'Label',
                attributes: ['class' => 'text-xl']
            )
            
            // Image:
            ->image(                
                src: 'https://example.com/image.jpg',
                // or \Psr\Http\Message\StreamInterface;
                // or \Tobento\Service\Filesystem\File;
                alt: 'Alternative Text',
                width: 200, // you may set a width
                height: 200, // you may set a height
                mimeType: 'image/jpeg' // optional, for stream src required
            )
            
            // General:
            ->table(
                headers: ['Name', 'Address'], // optional
                rows: [
                    ['Tom', 'address'],
                    ['Tim', 'address'],
                ],
            )
            ->list([
                'Coffee',
                'Milk',
            ])
            ->keyedList([
                'Firstname' => 'Tim',
                'Lastname' => 'Meier',
            ])
            
            // or using block classes:
            ->block(
                new Block\H1('Heading'),
                new Block\Text('Lorem ipsum'),
            );

        $mailer->send($message);
    }
}
```

**Render Parameter**

Each build in block method has the ```render``` parameter which may be useful in certain cases.

```php
use Tobento\App\Mail\TemplatedMessage;

$amount = 5;

$message = new TemplatedMessage()
    ->txt("Amount paid: {$amount}", render: $amount > 0);
```

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
        $message = new Message()
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
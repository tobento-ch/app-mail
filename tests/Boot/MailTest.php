<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\App\Mail\Test\Boot;

use PHPUnit\Framework\TestCase;
use Tobento\App\Mail\Boot\Mail;
use Tobento\Service\Mail\MailerInterface;
use Tobento\Service\Mail\MailersInterface;
use Tobento\Service\Mail\RendererInterface;
use Tobento\Service\Mail\MessageFactoryInterface;
use Tobento\Service\Mail\QueueHandlerInterface;
use Tobento\Service\Mail\MessageInterface;
use Tobento\Service\Mail\LazyMailers;
use Tobento\Service\Mail\Mailers;
use Tobento\Service\Mail\NullMailer;
use Tobento\Service\Mail\Message;
use Tobento\Service\Mail\Parameter;
use Tobento\Service\Mail\Symfony;
use Tobento\Service\Queue\QueuesInterface;
use Tobento\Service\Queue\QueueException;
use Tobento\App\AppInterface;
use Tobento\App\AppFactory;
use Tobento\App\Boot;
use Tobento\Service\Filesystem\Dir;

class MailTest extends TestCase
{    
    protected function createApp(bool $deleteDir = true): AppInterface
    {
        if ($deleteDir) {
            (new Dir())->delete(__DIR__.'/../app/');
        }
        
        (new Dir())->create(__DIR__.'/../app/');
        
        $app = (new AppFactory())->createApp();
        
        $app->dirs()
            ->dir(realpath(__DIR__.'/../../'), 'root')
            ->dir(realpath(__DIR__.'/../app/'), 'app')
            ->dir($app->dir('app').'config', 'config', group: 'config', priority: 10)
            // for testing only we add public within app dir.
            ->dir($app->dir('app').'public', 'public')
            ->dir($app->dir('root').'vendor', 'vendor');
        
        return $app;
    }
    
    public static function tearDownAfterClass(): void
    {
        (new Dir())->delete(__DIR__.'/../app/');
    }
    
    public function testInterfacesAreAvailable()
    {
        $app = $this->createApp();
        $app->boot(Mail::class);
        $app->booting();
        
        $this->assertInstanceof(MailerInterface::class, $app->get(MailerInterface::class));
        $this->assertInstanceof(MailersInterface::class, $app->get(MailersInterface::class));
        $this->assertInstanceof(RendererInterface::class, $app->get(RendererInterface::class));
        $this->assertInstanceof(MessageFactoryInterface::class, $app->get(MessageFactoryInterface::class));
        $this->assertInstanceof(QueueHandlerInterface::class, $app->get(QueueHandlerInterface::class));
        $this->assertInstanceof(Symfony\EmailFactoryInterface::class, $app->get(Symfony\EmailFactoryInterface::class));
    }
    
    public function testMailersAreSetFromConfig()
    {
        $app = $this->createApp();
        $app->dirs()->dir(realpath(__DIR__.'/../config/'), 'config-test', group: 'config', priority: 20);
        $app->boot(Mail::class);
        $app->booting();
        
        $mailers = $app->get(MailersInterface::class);
        
        $this->assertInstanceof(Symfony\Mailer::class, $mailers->mailer(name: 'default'));
        $this->assertInstanceof(Symfony\Mailer::class, $mailers->mailer(name: 'foo'));
    }
    
    public function testMailerUsesLazyMailers()
    {
        $app = $this->createApp();
        $app->boot(Mail::class);
        $app->booting();
        
        $this->assertInstanceof(LazyMailers::class, $app->get(MailerInterface::class));
    }
    
    public function testMailerUsesFirstMailerFromMailersIfNotImplementingMailerInterface()
    {
        $app = $this->createApp();
        $app->boot(Mail::class);
        $app->booting();
        
        $app->on(MailersInterface::class, function (): MailersInterface {
            return new class() implements MailersInterface {
                public function send(MessageInterface ...$message): void
                {
                    //
                }

                public function mailer(string $name): null|MailerInterface
                {
                    return new NullMailer('first');
                }

                public function names(): array
                {
                    return ['first'];
                }
            };
        });
        
        $this->assertInstanceof(NullMailer::class, $app->get(MailerInterface::class));
    }
    
    public function testMailerUsesNullMailerIfMAilersNotImplementingMailerInterfaceAndIsEmpty()
    {
        $app = $this->createApp();
        $app->boot(Mail::class);
        $app->booting();
        
        $app->on(MailersInterface::class, function (): MailersInterface {
            return new class() implements MailersInterface {
                public function send(MessageInterface ...$message): void
                {
                    //
                }

                public function mailer(string $name): null|MailerInterface
                {
                    return null;
                }

                public function names(): array
                {
                    return [];
                }
            };
        });
        
        $this->assertInstanceof(NullMailer::class, $app->get(MailerInterface::class));
    }
    
    public function testSendMessage()
    {
        $app = $this->createApp();
        $app->dirs()->dir(realpath(__DIR__.'/../config/'), 'config-test', group: 'config', priority: 20);
        $app->boot(Mail::class);
        $app->booting();
        
        $mailer = $app->get(MailerInterface::class);
        
        $message = (new Message())
            ->to('to@example.com')
            ->subject('Subject')
            ->text('Lorem Ipsum');
        
        $mailer->send($message);
        
        $this->assertTrue(true);
    }
    
    public function testMessageGetsQueued()
    {
        $app = $this->createApp();
        $app->dirs()->dir(realpath(__DIR__.'/../config/'), 'config-test', group: 'config', priority: 20);
        $app->boot(Mail::class);
        $app->booting();
        
        $queue = $app->get(QueuesInterface::class)->queue('file');
        
        $this->assertSame(0, $queue->size());
        
        $mailer = $app->get(MailerInterface::class);
        
        $message = (new Message())
            ->to('to@example.com')
            ->subject('Subject')
            ->text('Lorem Ipsum')
            ->parameter(new Parameter\Queue(
                name: 'file',
            ));
        
        $mailer->send($message);
        
        $this->assertSame(1, $queue->size());
    }
    
    public function testMessageGetsQueuedUsingDefaultConfig()
    {
        // will throw as mails queue is not configured
        // but it means it will be used.
        $this->expectException(QueueException::class);
        $this->expectExceptionMessage('Queue mails not found');
        
        $app = $this->createApp();
        $app->dirs()->dir(realpath(__DIR__.'/../config/'), 'config-test', group: 'config', priority: 20);
        $app->boot(Mail::class);
        $app->booting();
        
        $mailer = $app->get(MailerInterface::class);
        
        $message = (new Message())
            ->to('to@example.com')
            ->subject('Subject')
            ->text('Lorem Ipsum')
            ->parameter(new Parameter\Queue());
        
        $mailer->send($message);
    }
    
    public function testUsesDefaultsConfig()
    {
        $app = $this->createApp();
        $app->dirs()->dir(realpath(__DIR__.'/../config/'), 'config-test', group: 'config', priority: 20);
        $app->boot(Mail::class);
        $app->booting();
        
        $emailFactory = $app->get(Symfony\EmailFactoryInterface::class);
        
        $message = (new Message())
            ->to('to@example.com')
            ->subject('Subject')
            ->text('Lorem Ipsum');
        
        $email = $emailFactory->createEmailFromMessage($message);
        $emailString = $email->toString();
        
        $this->assertStringContainsString('From: FromName <from@example.com>', $emailString);
        $this->assertStringContainsString('Return-Path: <return@example.com>', $emailString);
    }
}
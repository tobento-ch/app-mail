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
 
namespace Tobento\App\Mail\Boot;

use Tobento\App\Boot;
use Tobento\App\Boot\Config;
use Tobento\App\Migration\Boot\Migration;
use Tobento\App\View\Boot\View;
use Tobento\App\Queue\Boot\Queue;
use Tobento\Service\Mail\MailerInterface;
use Tobento\Service\Mail\MailersInterface;
use Tobento\Service\Mail\LazyMailers;
use Tobento\Service\Mail\NullMailer;
use Tobento\Service\Mail\RendererInterface;
use Tobento\Service\Mail\ViewRenderer;
use Tobento\Service\Mail\MessageFactoryInterface;
use Tobento\Service\Mail\MessageFactory;
use Tobento\Service\Mail\Symfony;
use Tobento\Service\Mail\QueueHandlerInterface;
use Tobento\Service\Mail\Queue\QueueHandler;
use Psr\Container\ContainerInterface;

/**
 * Mail
 */
class Mail extends Boot
{
    public const INFO = [
        'boot' => [
            'installs and loads mail config file',
            'implements mail interfaces',
        ],
    ];

    public const BOOT = [
        Config::class,
        Migration::class,
        View::class,
        Queue::class,
    ];

    /**
     * Boot application services.
     *
     * @param Migration $migration
     * @param Config $config
     * @return void
     */
    public function boot(Migration $migration, Config $config): void
    {
        // install migration:
        $migration->install(\Tobento\App\Mail\Migration\Mail::class);
        
        // load the mail config:
        $config = $config->load('mail.php');
        
        // interfaces:
        $this->app->set(RendererInterface::class, ViewRenderer::class);
        $this->app->set(MessageFactoryInterface::class, MessageFactory::class);
        $this->app->set(QueueHandlerInterface::class, QueueHandler::class)->with([
            'queueName' => $config['queue'] ?? null
        ]);
        
        $this->app->set(
            Symfony\EmailFactoryInterface::class,
            static function (RendererInterface $renderer) use ($config): Symfony\EmailFactoryInterface {
                return new Symfony\EmailFactory(
                    renderer: $renderer,
                    config: $config['defaults'] ?? [],
                );
            }
        );
        
        $this->app->set(
            MailersInterface::class,
            static function(ContainerInterface $container) use ($config): MailersInterface {
                return new LazyMailers(
                    container: $container,
                    mailers: $config['mailers'] ?? [],
                );
            }
        );
        
        $this->app->set(MailerInterface::class, static function (MailersInterface $mailers): MailerInterface {
            if ($mailers instanceof MailerInterface) {
                return $mailers;
            }
            
            $mailer = $mailers->mailer($mailers->names()[0] ?? '');
            
            return $mailer instanceof MailerInterface ? $mailer : new NullMailer('null');
        });
    }
}
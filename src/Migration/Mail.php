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

namespace Tobento\App\Mail\Migration;

use Tobento\Service\Migration\MigrationInterface;
use Tobento\Service\Migration\ActionsInterface;
use Tobento\Service\Migration\Actions;
use Tobento\Service\Migration\Action\FilesCopy;
use Tobento\Service\Migration\Action\FilesDelete;
use Tobento\Service\Migration\Action\DirCopy;
use Tobento\Service\Migration\Action\DirDelete;
use Tobento\Service\Dir\DirsInterface;

/**
 * Mail migration.
 */
class Mail implements MigrationInterface
{
    /**
     * @var array The config files.
     */
    protected array $configFiles;
    
    /**
     * Create a new Mail.
     *
     * @param DirsInterface $dirs
     */
    public function __construct(
        protected DirsInterface $dirs,
    ) {
        $vendor = realpath(__DIR__.'/../../');
        
        $this->configFiles = [
            $this->dirs->get('config') => [
                $vendor.'/resources/config/mail.php',
            ],
        ];
    }
    
    /**
     * Return a description of the migration.
     *
     * @return string
     */
    public function description(): string
    {
        return 'File mail config.';
    }
        
    /**
     * Return the actions to be processed on install.
     *
     * @return ActionsInterface
     */
    public function install(): ActionsInterface
    {
        $resources = realpath(__DIR__.'/../../').'/resources/';
        
        return new Actions(
            new FilesCopy(
                files: $this->configFiles,
                type: 'config',
                description: 'Mail config file.',
            ),
            new DirCopy(
                dir: $resources.'views/mail/',
                destDir: $this->dirs->get('views').'mail/',
                name: 'Mail views',
                type: 'views',
                description: 'Mail views.',
            ),
            new DirCopy(
                dir: $resources.'assets/mail/',
                destDir: $this->dirs->get('public').'assets/mail/',
                name: 'Mail asset files',
                type: 'assets',
                description: 'Mail asset files.',
            ),
        );
    }

    /**
     * Return the actions to be processed on uninstall.
     *
     * @return ActionsInterface
     */
    public function uninstall(): ActionsInterface
    {
        return new Actions(
            new FilesDelete(
                files: $this->configFiles,
                type: 'config',
                description: 'Mail config file.',
            ),
            new DirDelete(
                dir: $this->dirs->get('views').'mail/',
                name: 'Mail views',
                type: 'views',
                description: 'Mail views.',
            ),
            new DirDelete(
                dir: $this->dirs->get('public').'assets/mail/',
                name: 'Mail asset files.',
                type: 'assets',
                description: 'Mail asset files.',
            ),  
        );
    }
}
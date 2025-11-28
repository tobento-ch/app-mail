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

namespace Tobento\App\Mail;

use Tobento\App\Mail\Block\BlockInterface;
use Tobento\Service\Mail\Addresses;
use Tobento\Service\Mail\HasMessage;
use Tobento\Service\Mail\Parameters;
use Tobento\Service\Mail\MessageInterface;
use Tobento\Service\Mail\Template;
use Tobento\Service\Mail\TemplateInterface;
use Stringable;

/**
 * TemplatedMessage
 */
class TemplatedMessage implements MessageInterface
{
    use HasMessage;
    use HasBlocks;
    
    /**
     * Create a new TemplatedMessage.
     *
     * @param string $locale
     */
    public function __construct(
        protected string $locale = 'en'
    ) {
        $this->to = new Addresses();
        $this->cc = new Addresses();
        $this->bcc = new Addresses();
        $this->parameters = new Parameters();
    }
    
    /**
     * Returns the locale.
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }
    
    /**
     * Returns the html or null if none specified.
     *
     * @return null|string|TemplateInterface
     */
    public function getHtml(): null|string|TemplateInterface
    {
        if (is_null($this->html)) {
            $this->html = new Template(name: 'mail/templated', data: $this->getTemplateData());
        }
        
        return $this->html;
    }
    
    /**
     * Returns the template data.
     *
     * @return array
     */
    protected function getTemplateData(): array
    {
        return [
            'locale' => $this->getLocale(),
            'htmlLang' => str_replace('_', '-', $this->getLocale()),
            'blocks' => $this->getBlocks(),
        ];
    }
}
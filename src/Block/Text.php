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

namespace Tobento\App\Mail\Block;

use Tobento\Service\Tag\Attributes;
use Tobento\Service\View\ViewInterface;
use Stringable;

/**
 * Text
 */
class Text implements BlockInterface
{
    /**
     * Create a new Text.
     *
     * @param string|Stringable $text
     * @param bool $render
     */
    final public function __construct(
        protected string|Stringable $text,
        protected bool $render = true
    ) {}
    
    /**
     * Create a new instance.
     *
     * @param string|Stringable $text
     * @param bool $render
     * @return static
     */
    public static function new(
        string|Stringable $text,
        bool $render = true
    ): static {
        return new static($text, $render);
    }
    
    /**
     * Returns the text.
     *
     * @return string
     */
    public function text(): string
    {
        return (string)$this->text;
    }
    
    /**
     * Returns the html of the block. MUST be escaped.
     *
     * @param ViewInterface $view
     * @return string
     */
    public function render(ViewInterface $view): string
    {
        if (! $this->render) {
            return '';
        }
        
        return $view->render('mail/block/text', [
            'block' => $this,
        ]);
    }
}
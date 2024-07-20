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
use Tobento\Service\Tag\Tag;
use Tobento\Service\Tag\TagInterface;
use Tobento\Service\View\ViewInterface;
use Tobento\Service\Support\Str;
use Stringable;

/**
 * Button
 */
class Button implements BlockInterface
{
    /**
     * Create a new Button.
     *
     * @param string $url
     * @param string $label
     * @param array $attributes
     * @param bool $render
     */
    final public function __construct(
        protected string $url,
        protected string $label,
        protected array $attributes = [],
        protected bool $render = true
    ) {}
    
    /**
     * Create a new instance.
     *
     * @param string $url
     * @param string $label
     * @param array $attributes
     * @param bool $render
     * @return static
     */
    public static function new(
        string $url,
        string $label,
        array $attributes = [],
        bool $render = true
    ): static {
        return new static($url, $label, $attributes, $render);
    }

    /**
     * Returns the tag.
     *
     * @return TagInterface
     */
    public function tag(): TagInterface
    {
        $attributes = new Attributes($this->attributes);
        $attributes->set('href', $this->url);
        
        return new Tag(
            name: 'a',
            html: Str::esc($this->label),
            attributes: $attributes,
        );
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
        
        return $view->render('mail/block/button', [
            'block' => $this,
        ]);
    }
}
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

/**
 * Hr
 */
class Hr implements BlockInterface
{
    /**
     * Create a new Hr.
     *
     * @param string $size
     * @param array $attributes
     * @param bool $render
     */
    final public function __construct(
        protected string $size = 's',
        protected array $attributes = [],
        protected bool $render = true
    ) {}

    /**
     * Returns the hr tag.
     *
     * @return TagInterface
     */
    public function tag(): TagInterface
    {
        $sizes = ['s' => 'line-s', 'm' => 'line-m', 'l' => 'line-l'];
        $sizeClass = $sizes[$this->size] ?? 'line-s';
        
        $attributes = new Attributes($this->attributes);
        $attributes->add('class', $sizeClass);
        
        return new Tag(
            name: 'hr',
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
        
        return $view->render('mail/block/hr', [
            'block' => $this,
        ]);
    }
}
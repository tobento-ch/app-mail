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

use Tobento\Service\Tag\Tag;
use Tobento\Service\Tag\TagInterface;
use Tobento\Service\View\ViewInterface;
use Tobento\Service\Support\Str;
use Stringable;

/**
 * H1
 */
class H1 extends Text
{
    /**
     * @var string
     */
    protected string $tagName = 'h1';
    
    /**
     * Returns the tag.
     *
     * @return TagInterface
     */
    public function tag(): TagInterface
    {
        return new Tag(
            name: $this->tagName,
            html: Str::esc($this->text()),
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
        
        return $view->render('mail/block/heading', [
            'block' => $this,
        ]);
    }
}
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

use Tobento\Service\View\ViewInterface;
use Stringable;

/**
 * Html
 */
class Html implements BlockInterface
{
    /**
     * Create a new Html.
     *
     * @param string|Stringable $html Must be escaped.
     * @param bool $render
     */
    final public function __construct(
        protected string|Stringable $html,
        protected bool $render = true
    ) {}
    
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
        
        return (string)$this->html;
    }
}
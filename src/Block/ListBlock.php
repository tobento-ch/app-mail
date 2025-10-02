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
use Tobento\Service\Collection\Collection;
use Tobento\Service\Support\Str;
use JsonException;
use Stringable;

/**
 * ListBlock
 */
class ListBlock implements BlockInterface
{
    /**
     * Create a new ListBlock.
     *
     * @param array $items
     * @param bool $render
     */
    final public function __construct(
        protected array $items = [],
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
        
        return $view->render('mail/block/list', ['list' => $this]);
    }
    
    /**
     * Returns the items.
     *
     * @return array
     */
    public function items(): array
    {
        return $this->items;
    }
    
    /**
     * Returns true if list is empty, otherwise false.
     *
     * @return bool
     */
    public function empty(): bool
    {
        return empty($this->items());
    }
    
    /**
     * Render a value.
     *
     * @param ViewInterface $view
     * @param mixed $value
     * @return string
     */
    public function renderValue(ViewInterface $view, mixed $value): string
    {
        if (is_array($value)) {
            try {
                $value = json_encode(
                    json_decode((new Collection($value))->toJson(), true, 512, JSON_THROW_ON_ERROR),
                    JSON_PRETTY_PRINT
                );
                return Str::esc($value);
            } catch (JsonException $e) {
                return '';
            }
        }

        if ($value instanceof BlockInterface) {
            return $value->render($view);
        }
        
        if (is_string($value) || $value instanceof Stringable) {
            return Str::esc((string)$value);
        }
        
        if (is_numeric($value) || is_bool($value)) {
            return Str::esc((string)$value);
        }
        
        if (is_null($value)) {
            return 'null';
        }
        
        return '';
    }
}
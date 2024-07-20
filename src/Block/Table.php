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
 * Table
 */
class Table implements BlockInterface
{
    /**
     * Create a new Table.
     *
     * @param array $headers
     * @param array $rows
     * @param bool $render
     */
    final public function __construct(
        protected array $headers = [],
        protected array $rows = [],
        protected bool $render = true
    ) {}
    
    /**
     * Create a new instance.
     *
     * @param array $headers
     * @param array $rows
     * @param bool $render
     * @return static
     */
    public static function new(
        array $headers = [],
        array $rows = [],
        bool $render = true
    ): static {
        return new static($headers, $rows, $render);
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
        
        return $view->render('mail/block/table', ['table' => $this]);
    }
    
    /**
     * Returns the headers.
     *
     * @return array
     */
    public function headers(): array
    {
        return $this->headers;
    }
    
    /**
     * Returns the rows.
     *
     * @return array
     */
    public function rows(): array
    {
        return $this->rows;
    }
    
    /**
     * Returns true if table is empty, otherwise false.
     *
     * @return bool
     */
    public function empty(): bool
    {
        return empty($this->headers()) && empty($this->rows());
    }

    /**
     * Verify a row.
     *
     * @param mixed $row
     * @return array
     */
    public function verifyRow(mixed $row): array
    {
        if (is_array($row)) {
            return $row;
        }
        
        return (new Collection($row))->toArray();
    }
    
    /**
     * Render a value.
     *
     * @param ViewInterface $view
     * @param mixed $value
     * @param string|int $name
     * @return string
     */
    public function renderValue(ViewInterface $view, mixed $value, string|int $name): string
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
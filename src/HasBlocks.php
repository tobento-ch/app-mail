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

use Psr\Http\Message\StreamInterface;
use Tobento\App\Mail\Block\BlockInterface;
use Tobento\Service\Filesystem\File;
use Stringable;

/**
 * TemplatedMessage
 */
trait HasBlocks
{
    /**
     * @var array<array-key, BlockInterface>
     */
    protected array $blocks = [];

    /**
     * Add a block or multiple.
     *
     * @param BlockInterface ...$blocks
     * @return static $this
     */
    public function block(BlockInterface ...$blocks): static
    {
        foreach($blocks as $block) {
            $this->blocks[] = $block;
        }
        
        return $this;
    }
    
    /**
     * Returns the blocks.
     *
     * @return array<array-key, BlockInterface>
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }
    
    /**
     * Add a button.
     *
     * @param string $url
     * @param string $label
     * @param array $attributes
     * @param bool $render
     * @return static $this
     */
    public function button(string $url, string $label, array $attributes = [], bool $render = true): static
    {
        $this->blocks[] = new Block\Button($url, $label, $attributes, $render);
        return $this;
    }

    /**
     * Add a h1.
     *
     * @param string|Stringable $text
     * @param bool $render
     * @return static $this
     */
    public function h1(string|Stringable $text, bool $render = true): static
    {
        $this->blocks[] = new Block\H1($text, $render);
        return $this;
    }
    
    /**
     * Add a h2.
     *
     * @param string|Stringable $text
     * @param bool $render
     * @return static $this
     */
    public function h2(string|Stringable $text, bool $render = true): static
    {
        $this->blocks[] = new Block\H2($text, $render);
        return $this;
    }
    
    /**
     * Add a h3.
     *
     * @param string|Stringable $text
     * @param bool $render
     * @return static $this
     */
    public function h3(string|Stringable $text, bool $render = true): static
    {
        $this->blocks[] = new Block\H3($text, $render);
        return $this;
    }
    
    /**
     * Add a h4.
     *
     * @param string|Stringable $text
     * @param bool $render
     * @return static $this
     */
    public function h4(string|Stringable $text, bool $render = true): static
    {
        $this->blocks[] = new Block\H4($text, $render);
        return $this;
    }
    
    /**
     * Add a h5.
     *
     * @param string|Stringable $text
     * @param bool $render
     * @return static $this
     */
    public function h5(string|Stringable $text, bool $render = true): static
    {
        $this->blocks[] = new Block\H5($text, $render);
        return $this;
    }
    
    /**
     * Add a h6.
     *
     * @param string|Stringable $text
     * @param bool $render
     * @return static $this
     */
    public function h6(string|Stringable $text, bool $render = true): static
    {
        $this->blocks[] = new Block\H6($text, $render);
        return $this;
    }
    
    /**
     * Add a hr.
     *
     * @param string $size
     * @param array $attributes
     * @param bool $render
     * @return static $this
     */
    public function hr(string $size = 's', array $attributes = [], bool $render = true): static
    {
        $this->blocks[] = new Block\Hr($size, $attributes, $render);
        return $this;
    }

    /**
     * Add a html.
     *
     * @param string|Stringable $html Must be escaped.
     * @param bool $render
     * @return static $this
     */
    public function htmlBlock(string|Stringable $html, bool $render = true): static
    {
        $this->blocks[] = new Block\Html($html, $render);
        return $this;
    }
    
    /**
     * Add an image.
     *
     * @param string|File|StreamInterface $src
     * @param string $alt
     * @param null|int $width
     * @param null|int $height
     * @param null|string $mimeType
     * @param bool $render
     * @return static $this
     */
    public function image(
        string|File|StreamInterface $src,
        string $alt = '',
        null|int $width = null,
        null|int $height = null,
        null|string $mimeType = null,
        bool $render = true
    ): static {
        $this->blocks[] = new Block\Image($src, $alt, $width, $height, $mimeType, $render);
        return $this;
    }

    /**
     * Add a keyed list.
     *
     * @param array $items
     * @param bool $render
     * @return static $this
     */
    public function keyedList(array $items = [], bool $render = true): static
    {
        $this->blocks[] = new Block\KeyedList($items, $render);
        return $this;
    }
    
    /**
     * Add a link.
     *
     * @param string $url
     * @param string $label
     * @param array $attributes
     * @param bool $render
     * @return static $this
     */
    public function link(string $url, string $label, array $attributes = [], bool $render = true): static
    {
        $this->blocks[] = new Block\Link($url, $label, $attributes, $render);
        return $this;
    }
    
    /**
     * Add a list.
     *
     * @param array $items
     * @param bool $render
     * @return static $this
     */
    public function list(array $items = [], bool $render = true): static
    {
        $this->blocks[] = new Block\ListBlock($items, $render);
        return $this;
    }

    /**
     * Add a table.
     *
     * @param array $headers
     * @param array $rows
     * @return static $this
     */
    public function table(array $headers = [], array $rows = [], bool $render = true): static
    {
        $this->blocks[] = new Block\Table($headers, $rows, $render);
        return $this;
    }
    
    /**
     * Add a text.
     *
     * @param string|Stringable $text
     * @param bool $render
     * @return static $this
     */
    public function txt(string|Stringable $text, bool $render = true): static
    {
        $this->blocks[] = new Block\Text($text, $render);
        return $this;
    }
}
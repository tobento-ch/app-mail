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

use Psr\Http\Message\StreamInterface;
use Tobento\Service\Filesystem\File;
use Tobento\Service\Mail\TemplateMessageInterface;
use Tobento\Service\Tag\Attributes;
use Tobento\Service\Tag\Tag;
use Tobento\Service\Tag\TagInterface;
use Tobento\Service\View\ViewInterface;

/**
 * Image
 */
class Image implements BlockInterface
{
    /**
     * Create a new Image.
     *
     * @param string|File|StreamInterface $src
     * @param string $alt
     * @param null|int $width
     * @param null|int $height
     * @param null|string $mimeType
     * @param bool $render
     */
    final public function __construct(
        protected string|File|StreamInterface $src,
        protected string $alt = '',
        protected null|int $width = null,
        protected null|int $height = null,
        protected null|string $mimeType = null,
        protected bool $render = true
    ) {}
    
    /**
     * Create a new instance.
     *
     * @param string $url
     * @param string $alt
     * @param null|int $width
     * @param null|int $height
     * @param null|string $mimeType
     * @param bool $render
     * @return static
     */
    public static function new(
        string|File|StreamInterface $src,
        string $alt = '',
        null|int $width = null,
        null|int $height = null,
        null|string $mimeType = null,
        bool $render = true
    ): static {
        return new static($src, $alt, $width, $height, $mimeType, $render);
    }

    /**
     * Returns the tag.
     *
     * @return TagInterface
     */
    public function tag(): TagInterface
    {
        $attributes = new Attributes();
        $attributes->set('alt', $this->alt);
        
        if ($this->width > 0) {
            $attributes->set('width', (string)$this->width);
        }
        
        if ($this->height > 0) {
            $attributes->set('height', (string)$this->height);
        }
        
        return new Tag(
            name: 'img',
            attributes: $attributes,
        );
    }
    
    /**
     * Returns the src.
     *
     * @return string|File|StreamInterface
     */
    public function src(): string|File|StreamInterface
    {
        return $this->src;
    }
    
    /**
     * Returns the mime type.
     *
     * @return null|string
     */
    public function mimeType(): null|string
    {
        return $this->mimeType;
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
        
        return $view->render('mail/block/image', [
            'image' => $this,
        ]);
    }
}
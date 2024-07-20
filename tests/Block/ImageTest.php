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

namespace Tobento\App\Mail\Test\Block;

use PHPUnit\Framework\TestCase;
use Tobento\App\Mail\Block\BlockInterface;
use Tobento\App\Mail\Block\Image;
use Tobento\App\Mail\Test\Factory;
use Tobento\Service\Mail\TemplateMessage;

class ImageTest extends TestCase
{
    public function testThatImplementsBlockInterface()
    {
        $block = Image::new(src: 'image.jpg');
        
        $this->assertInstanceof(BlockInterface::class, $block);
    }
    
    public function testRenderMethod()
    {
        $block = Image::new(src: 'https://example.com/image.jpg', alt: 'foo');
        $view = Factory::createView();
        $view->with(name: 'message', value: new TemplateMessage('subject'));
        
        $this->assertStringContainsString(
            '<img alt="foo" src="https://example.com/image.jpg">',
            $block->render($view)
        );
    }
    
    public function testRenderMethodWithWidthAndHeight()
    {
        $block = Image::new(src: 'https://example.com/image.jpg', alt: 'foo', width: 200, height: 300);
        $view = Factory::createView();
        $view->with(name: 'message', value: new TemplateMessage('subject'));
        
        $this->assertStringContainsString(
            '<img alt="foo" width="200" height="300" src="https://example.com/image.jpg">',
            $block->render($view)
        );
    }
    
    public function testRenderMethodWithRenderReturnsEmptyString()
    {
        $block = Image::new(src: 'https://example.com/image.jpg', render: false);
        $view = Factory::createView();
        $view->with(name: 'message', value: new TemplateMessage('subject'));
        
        $this->assertSame('', $block->render($view));
    }
}
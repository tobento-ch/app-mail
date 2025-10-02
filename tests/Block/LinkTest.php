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
use Tobento\App\Mail\Block\Link;
use Tobento\App\Mail\Test\Factory;

class LinkTest extends TestCase
{
    public function testThatImplementsBlockInterface()
    {
        $block = new Link(url: 'url', label: 'label');
        
        $this->assertInstanceof(BlockInterface::class, $block);
    }
    
    public function testRenderMethod()
    {
        $block = new Link(url: 'url', label: 'label');
        
        $this->assertStringContainsString(
            '<a href="url" target="_blank" rel="noopener">label</a>',
            $block->render(Factory::createView())
        );
    }
    
    public function testRenderMethodWithAttributes()
    {
        $block = new Link(url: 'url', label: 'label', attributes: ['class' => 'foo']);
        
        $this->assertStringContainsString(
            '<a class="foo" href="url" target="_blank" rel="noopener">label</a>',
            $block->render(Factory::createView())
        );
    }
    
    public function testRenderMethodWithRenderReturnsEmptyString()
    {
        $block = new Link(url: 'url', label: 'label', render: false);
        
        $this->assertSame('', $block->render(Factory::createView()));
    }
}
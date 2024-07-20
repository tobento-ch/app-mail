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
use Tobento\App\Mail\Block\ListBlock;
use Tobento\App\Mail\Test\Factory;

class ListBlockTest extends TestCase
{
    public function testThatImplementsBlockInterface()
    {
        $block = ListBlock::new(items: ['foo', 'bar']);
        
        $this->assertInstanceof(BlockInterface::class, $block);
    }
    
    public function testRenderMethod()
    {
        $block = ListBlock::new(items: ['foo', 'bar']);
        $html = $block->render(Factory::createView());
        
        $this->assertStringContainsString('<li>foo</li>', $html);
        $this->assertStringContainsString('<li>bar</li>', $html);
    }
    
    public function testRenderMethodWithRenderReturnsEmptyString()
    {
        $block = ListBlock::new(items: ['foo', 'bar'], render: false);
        
        $this->assertSame('', $block->render(Factory::createView()));
    }
}
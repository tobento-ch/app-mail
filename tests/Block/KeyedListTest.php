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
use Tobento\App\Mail\Block\KeyedList;
use Tobento\App\Mail\Test\Factory;

class KeyedListTest extends TestCase
{
    public function testThatImplementsBlockInterface()
    {
        $block = KeyedList::new(items: []);
        
        $this->assertInstanceof(BlockInterface::class, $block);
    }
    
    public function testRenderMethod()
    {
        $block = KeyedList::new(items: ['foo' => 'Foo', 'bar' => 'Bar']);
        $html = $block->render(Factory::createView());
        
        $this->assertStringContainsString('foo', $html);
        $this->assertStringContainsString('Foo', $html);
        $this->assertStringContainsString('bar', $html);
        $this->assertStringContainsString('Bar', $html);
    }
    
    public function testRenderMethodWithRenderReturnsEmptyString()
    {
        $block = KeyedList::new(items: ['foo' => 'Foo', 'bar' => 'Bar'], render: false);
        
        $this->assertSame('', $block->render(Factory::createView()));
    }
}
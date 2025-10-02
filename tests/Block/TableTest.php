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
use Tobento\App\Mail\Block\Table;
use Tobento\App\Mail\Test\Factory;

class TableTest extends TestCase
{
    public function testThatImplementsBlockInterface()
    {
        $block = new Table();
        
        $this->assertInstanceof(BlockInterface::class, $block);
    }
    
    public function testRenderMethod()
    {
        $block = new Table(headers: ['foo'], rows: [['Foo']]);
        
        $this->assertSame(
            '<table><tr><th>foo</th></tr><tr><td>Foo</td></tr></table>',
            preg_replace('/\s+/', '', $block->render(Factory::createView()))
        );
    }
    
    public function testRenderMethodWithHeadersOnly()
    {
        $block = new Table(headers: ['foo', 'bar']);
        
        $this->assertSame(
            '<table><tr><th>foo</th><th>bar</th></tr></table>',
            preg_replace('/\s+/', '', $block->render(Factory::createView()))
        );
    }
    
    public function testRenderMethodWithRowsOnly()
    {
        $block = new Table(rows: [['Foo', 'Bar'], ['Baz', 'Lor']]);
        
        $this->assertSame(
            '<table><tr><td>Foo</td><td>Bar</td></tr><tr><td>Baz</td><td>Lor</td></tr></table>',
            preg_replace('/\s+/', '', $block->render(Factory::createView()))
        );
    }
    
    public function testRenderMethodWithRenderReturnsEmptyString()
    {
        $block = new Table(headers: ['foo', 'bar'], render: false);
        
        $this->assertSame('', $block->render(Factory::createView()));
    }
}
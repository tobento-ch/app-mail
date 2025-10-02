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
use Tobento\App\Mail\Block\H6;
use Tobento\App\Mail\Test\Factory;

class H6Test extends TestCase
{
    public function testThatImplementsBlockInterface()
    {
        $block = new H6(text: 'foo');
        
        $this->assertInstanceof(BlockInterface::class, $block);
    }
    
    public function testRenderMethod()
    {
        $block = new H6(text: 'foo');
        
        $this->assertSame('<h6>foo</h6>', $block->render(Factory::createView()));
    }
    
    public function testRenderMethodWithRenderReturnsEmptyString()
    {
        $block = new H6(text: 'foo', render: false);
        
        $this->assertSame('', $block->render(Factory::createView()));
    }
}
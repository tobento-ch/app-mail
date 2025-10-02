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
use Tobento\App\Mail\Block\Text;
use Tobento\App\Mail\Test\Factory;

class TextTest extends TestCase
{
    public function testThatImplementsBlockInterface()
    {
        $block = new Text(text: 'foo');
        
        $this->assertInstanceof(BlockInterface::class, $block);
    }
    
    public function testRenderMethod()
    {
        $block = new Text(text: 'foo');
        
        $this->assertSame('<p>foo</p>', $block->render(Factory::createView()));
    }
    
    public function testRenderMethodWithRenderReturnsEmptyString()
    {
        $block = new Text(text: 'foo', render: false);
        
        $this->assertSame('', $block->render(Factory::createView()));
    }
}
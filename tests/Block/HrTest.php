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
use Tobento\App\Mail\Block\Hr;
use Tobento\App\Mail\Test\Factory;

class HrTest extends TestCase
{
    public function testThatImplementsBlockInterface()
    {
        $block = new Hr();
        
        $this->assertInstanceof(BlockInterface::class, $block);
    }
    
    public function testRenderMethod()
    {
        $block = new Hr();
        
        $this->assertSame('<hr class="line-s">', $block->render(Factory::createView()));
    }
    
    public function testRenderMethodWithSize()
    {
        $this->assertSame('<hr class="line-s">', new Hr(size: 's')->render(Factory::createView()));
        $this->assertSame('<hr class="line-m">', new Hr(size: 'm')->render(Factory::createView()));
        $this->assertSame('<hr class="line-l">', new Hr(size: 'l')->render(Factory::createView()));
        $this->assertSame('<hr class="line-s">', new Hr(size: 'unknown')->render(Factory::createView()));
    }
    
    public function testRenderMethodWithAttributes()
    {
        $block = new Hr(attributes: ['class' => 'foo']);
        
        $this->assertSame('<hr class="foo line-s">', $block->render(Factory::createView()));
    }
    
    public function testRenderMethodWithRenderReturnsEmptyString()
    {
        $block = new Hr(render: false);
        
        $this->assertSame('', $block->render(Factory::createView()));
    }
}
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
use Tobento\App\Mail\Block\Html;
use Tobento\App\Mail\Test\Factory;

class HtmlTest extends TestCase
{
    public function testThatImplementsBlockInterface()
    {
        $block = new Html(html: '<p>lorem</p>');
        
        $this->assertInstanceof(BlockInterface::class, $block);
    }
    
    public function testRenderMethod()
    {
        $block = new Html(html: '<p>lorem</p>');
        
        $this->assertSame('<p>lorem</p>', $block->render(Factory::createView()));
    }
    
    public function testRenderMethodWithRenderReturnsEmptyString()
    {
        $block = new Html(html: '<p>lorem</p>', render: false);
        
        $this->assertSame('', $block->render(Factory::createView()));
    }
}
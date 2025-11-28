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

namespace Tobento\App\Mail\Test;

use PHPUnit\Framework\TestCase;
use Tobento\App\Mail\TemplatedMessage;
use Tobento\Service\Mail\MessageInterface;
use Tobento\Service\Mail\Address;
use Tobento\Service\Mail\AddressInterface;
use Tobento\Service\Mail\AddressesInterface;
use Tobento\Service\Mail\Parameter;
use Tobento\Service\Mail\ParametersInterface;
use Tobento\Service\Mail\Template;
use Tobento\Service\Mail\TemplateInterface;

class TemplatedMessageTest extends TestCase
{
    public function testEmptyMessage()
    {
        $message = new TemplatedMessage();
        
        $this->assertInstanceof(MessageInterface::class, $message);
        $this->assertSame(null, $message->getFrom());
        $this->assertInstanceof(AddressesInterface::class, $message->getTo());
        $this->assertInstanceof(AddressesInterface::class, $message->getCc());
        $this->assertInstanceof(AddressesInterface::class, $message->getBcc());
        $this->assertSame(null, $message->getReplyTo());
        $this->assertSame('', $message->getSubject());
        $this->assertSame(null, $message->getText());
        $this->assertInstanceof(TemplateInterface::class, $message->getHtml());
        $this->assertInstanceof(ParametersInterface::class, $message->parameters());
    }
    
    public function testFromMethod()
    {
        $message = (new TemplatedMessage())->from('foo@example.com');
        
        $this->assertInstanceof(AddressInterface::class, $message->getFrom());
        $this->assertSame('foo@example.com', $message->getFrom()->email());
        $this->assertSame(null, $message->getFrom()->name());
        
        $message = (new TemplatedMessage())->from(new Address('foo@example.com'));
        
        $this->assertInstanceof(AddressInterface::class, $message->getFrom());
        $this->assertSame('foo@example.com', $message->getFrom()->email());
        $this->assertSame(null, $message->getFrom()->name());
    }
    
    public function testToMethod()
    {
        $message = (new TemplatedMessage())->to('foo@example.com', new Address('bar@example.com'));
        
        $this->assertInstanceof(AddressesInterface::class, $message->getTo());
        
        $emails = $message->getTo()->map(function(AddressInterface $address): string {
            return $address->email();
        })->all();
        
        $this->assertSame(['foo@example.com', 'bar@example.com'], $emails);
    }
    
    public function testCcMethod()
    {
        $message = (new TemplatedMessage())->cc('foo@example.com', new Address('bar@example.com'));
        
        $this->assertInstanceof(AddressesInterface::class, $message->getTo());
        
        $emails = $message->getCc()->map(function(AddressInterface $address): string {
            return $address->email();
        })->all();
        
        $this->assertSame(['foo@example.com', 'bar@example.com'], $emails);
    }
    
    public function testBccMethod()
    {
        $message = (new TemplatedMessage())->bcc('foo@example.com', new Address('bar@example.com'));
        
        $this->assertInstanceof(AddressesInterface::class, $message->getTo());
        
        $emails = $message->getBcc()->map(function(AddressInterface $address): string {
            return $address->email();
        })->all();
        
        $this->assertSame(['foo@example.com', 'bar@example.com'], $emails);
    }
    
    public function testReplyToMethod()
    {
        $message = (new TemplatedMessage())->replyTo('foo@example.com');
        
        $this->assertInstanceof(AddressInterface::class, $message->getReplyTo());
        $this->assertSame('foo@example.com', $message->getReplyTo()->email());
        $this->assertSame(null, $message->getReplyTo()->name());
        
        $message = (new TemplatedMessage())->replyTo(new Address('foo@example.com'));
        
        $this->assertInstanceof(AddressInterface::class, $message->getReplyTo());
        $this->assertSame('foo@example.com', $message->getReplyTo()->email());
        $this->assertSame(null, $message->getReplyTo()->name());
    }
    
    public function testSubjectMethod()
    {
        $message = (new TemplatedMessage())->subject('lorem');

        $this->assertSame('lorem', $message->getSubject());
    }
    
    public function testTextMethod()
    {
        $message = (new TemplatedMessage())->text('lorem');
        $this->assertSame('lorem', $message->getText());
        
        $message = (new TemplatedMessage())->text(new Template('name', []));
        $this->assertInstanceof(TemplateInterface::class, $message->getText());
    }
    
    public function testTextTemplateMethod()
    {
        $message = (new TemplatedMessage())->textTemplate(name: 'name', data: ['key' => 'value']);

        $this->assertInstanceof(TemplateInterface::class, $message->getText());
        $this->assertSame('name', $message->getText()->name());
        $this->assertSame(['key' => 'value'], $message->getText()->data());
    }
    
    public function testHtmlMethod()
    {
        $message = (new TemplatedMessage())->html('<p>lorem</p>');
        $this->assertSame('<p>lorem</p>', $message->getHtml());
        
        $message = (new TemplatedMessage())->html(new Template('name', []));
        $this->assertInstanceof(TemplateInterface::class, $message->getHtml());
        
        //
        $message = new TemplatedMessage();
        $this->assertInstanceof(TemplateInterface::class, $message->getHtml());
    }
    
    public function testHtmlMethodReturnsTemplateWithEmptyBlocks()
    {
        $message = new TemplatedMessage();
        $this->assertInstanceof(TemplateInterface::class, $message->getHtml());
        $this->assertSame('mail/templated', $message->getHtml()->name());
        $this->assertSame(['locale' => 'en', 'htmlLang' => 'en', 'blocks' => []], $message->getHtml()->data());
    }
    
    public function testHtmlMethodReturnsTemplateWithSpecifiedLocale()
    {
        $message = new TemplatedMessage(locale: 'de_CH');
        $this->assertInstanceof(TemplateInterface::class, $message->getHtml());
        $this->assertSame('mail/templated', $message->getHtml()->name());
        $this->assertSame(['locale' => 'de_CH', 'htmlLang' => 'de-CH', 'blocks' => []], $message->getHtml()->data());
    }
    
    public function testHtmlMethodReturnsTemplateWithAddedBlocks()
    {
        $message = (new TemplatedMessage())
            ->h1('H1')
            ->txt('Text');
        
        $blocks = $message->getHtml()->data()['blocks'] ?? [];
        $this->assertSame('<h1>H1</h1>', $blocks[0]->tag()->render());
        $this->assertSame('Text', $blocks[1]->text());
    }
    
    public function testHtmlTemplateMethod()
    {
        $message = (new TemplatedMessage())->htmlTemplate(name: 'name', data: ['key' => 'value']);

        $this->assertInstanceof(TemplateInterface::class, $message->getHtml());
        $this->assertSame('name', $message->getHtml()->name());
        $this->assertSame(['key' => 'value'], $message->getHtml()->data());
    }
    
    public function testParameterMethod()
    {
        $foo = new Parameter\TextHeader(name: 'foo', value: 'foo value');
        $bar = new Parameter\TextHeader(name: 'bar', value: 'bar value');
        
        $message = (new TemplatedMessage())->parameter($foo)->parameter($bar);
        
        $this->assertTrue($foo === ($message->parameters()->all()[0] ?? null));
        $this->assertTrue($bar === ($message->parameters()->all()[1] ?? null));
    }
    
    public function testJsonSerializeMethod()
    {
        $message = (new TemplatedMessage())
            ->to('to@example.com')
            ->subject('Subject')
            ->html('<p>Lorem Ipsum</p>');
        
        $this->assertSame(
            [
                'from' => null,
                'to' => [
                    ['email' => 'to@example.com', 'name' => null],
                ],
                'cc' => [],
                'bcc' => [],
                'replyTo' => null,
                'subject' => 'Subject',
                'text' => null,
                'html' => '<p>Lorem Ipsum</p>',
                'parameters' => [],
            ],
            $message->jsonSerialize()
        );
    }
    
    public function testJsonSerializeMethodWithBlocks()
    {
        $message = (new TemplatedMessage())
            ->to('to@example.com')
            ->subject('Subject');
        
        $this->assertSame(
            [
                'from' => null,
                'to' => [
                    ['email' => 'to@example.com', 'name' => null],
                ],
                'cc' => [],
                'bcc' => [],
                'replyTo' => null,
                'subject' => 'Subject',
                'text' => null,
                'html' => [
                    'name' => 'mail/templated',
                    'data' => [
                        'locale' => 'en',
                        'htmlLang' => 'en',
                        'blocks' => [],
                    ],
                ],
                'parameters' => [],
            ],
            $message->jsonSerialize()
        );
    }
    
    public function testToStringMethod()
    {
        $message = (new TemplatedMessage())
            ->to('to@example.com')
            ->subject('Subject')
            ->html('<p>Lorem Ipsum</p>');

        $this->assertSame(
            '{"from":null,"to":[{"email":"to@example.com","name":null}],"cc":[],"bcc":[],"replyTo":null,"subject":"Subject","text":null,"html":"<p>Lorem Ipsum<\/p>","parameters":[]}',
            $message->__toString()
        );
    }
    
    public function testBlockMethods()
    {
        $message = (new TemplatedMessage())
            ->button(url: 'url', label: 'label')
            ->h1('H1')
            ->h2('H2')
            ->h3('H3')
            ->h4('H4')
            ->h5('H5')
            ->h6('H6')
            ->hr()
            ->htmlBlock('<p>lorem</p>')
            ->image(src: 'image.jpg')
            ->keyedList(items: ['foo'])
            ->link(url: 'url', label: 'label')
            ->list(items: ['foo'])
            ->table(
                headers: ['foo', 'bar'],
                rows: [['Foo', 'Bar']],
            )
            ->txt('Text');
        
        $this->assertSame(15, count($message->getBlocks()));
    }
}
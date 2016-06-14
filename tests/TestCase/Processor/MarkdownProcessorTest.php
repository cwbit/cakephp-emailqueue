<?php

namespace EmailQueue\Test\TestCase\Processor;

use EmailQueue\Processor\MarkdownProcessor as Processor;
use Cake\TestSuite\TestCase;

class MarkdownProcessorTest extends TestCase
{
  public function setUp()
  {
    parent::setUp();
    $this->Processor = new Processor;
  }
  public function testProcess()
  {
    $raw = [
      'viewVars' => [
        '_message_html' => "#Header",
      ]
    ];
    $expected = [
      'viewVars' => [
        '_message_html' => "<h1>Header</h1>",
      ]
    ];
    $this->Processor->process($raw);
    $this->assertEquals($expected, $raw);
  }
}

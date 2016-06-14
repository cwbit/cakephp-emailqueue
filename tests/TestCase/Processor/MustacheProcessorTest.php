<?php

namespace EmailQueue\Test\TestCase\Processor;

use EmailQueue\Processor\MustacheProcessor as Processor;
use Cake\TestSuite\TestCase;

class MustacheProcessorTest extends TestCase
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
        '_message_html' => "Hello, {{name}}!",
        'name'=>'World',
      ]
    ];
    $expected = "Hello, World!";
    $this->Processor->process($raw);
    $this->assertEquals($expected, $raw['viewVars']['_message_html']);
  }
}

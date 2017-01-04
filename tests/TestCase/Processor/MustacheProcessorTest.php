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
    $processed = $this->Processor->process($raw);
    $this->assertEquals($expected, $processed['viewVars']['_message_html']);
  }

  # demonstrate how to set things like `to_addr` programmatically
  public function testProcessToAddr()
  {
    $raw = [
      'to' => [ 0 => "{{email}}"],
      'viewVars' => [
        'email' => 'user@example.com'
      ]
    ];
    $expected = [ 0 => "user@example.com"];
    $fields = $this->Processor->setFields(['to.0']);
    $processed = $this->Processor->process($raw);
    $this->assertEquals($expected, $processed['to']);
  }

}

<?php

namespace EmailQueue\Processor;

/**
 * Main Processor class
 * Processors are classes that take in an array of email config settings and manipulate it byRef
 * The intended use of a Processor is, for example, to take a message block and parse it through Mustache and then convert it using Markdown and pass it back
 */
class Processor
{
  public function process(array $config)
  {
    return $config;
  }
}

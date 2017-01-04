<?php

namespace EmailQueue\Processor;

/**
 * Main Processor class
 * Processors are classes that take in an array of email config settings and manipulate it byRef
 * The intended use of a Processor is, for example, to take a message block and parse it through Mustache and then convert it using Markdown and pass it back
 */
abstract class Processor
{

  /**
   * @var array of fields to process
   * @todo currently only supports fields inside viewVars
   */
  protected $_fields;

  /**
   * Loop through each $_processField and call _process against it
   * @param array $config the email config data we're processing
   */
  public function process(array $config)
  {
    foreach ($this->_fields as $key => $field):
      $config = $this->_process($field, $config);
    endforeach;

    return $config;
  }

  /**
   * Process the given field optionally using the config data
   * @param string $field the field to process
   * @param array $config the config data
   */
  protected function _process($field, array $config)
  {
    return $config;
  }
}

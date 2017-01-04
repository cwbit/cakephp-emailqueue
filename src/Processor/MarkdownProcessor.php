<?php

namespace EmailQueue\Processor;

use EmailQueue\Processor\Processor;
use Markdown\Engine\Markdown;
use Cake\Utility\Hash;

/**
 * Add the MarkdownProcessor to the list of processors for your email to convert a markdown message body to HTML
 */
class MarkdownProcessor extends Processor
{
  /**
   * @var array of fields to process
   */
    protected $_fields = [
      'viewVars._message_html',
    ];

    /**
     * Runs Markdown HTML conversion against $field
     * @param string $field field to process
     * @param array $config data to use when processing
     */
    protected function _process($field, array $config)
    {
      if (Hash::check($config, $field)) :
        $config = Hash::insert(
          $config,
          $field,
          Markdown::toHtml(Hash::get($config, $field))
        );
      endif;

      return $config;
    }
}

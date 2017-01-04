<?php

namespace EmailQueue\Processor;

use EmailQueue\Processor\Processor;
use Markdown\Engine\Markdown;

/**
 * Add the MarkdownProcessor to the list of processors for your email to convert a markdown message body to HTML
 */
class MarkdownProcessor extends Processor
{
  /**
   * @var array of fields to process
   * @todo currently only supports fields inside viewVars
   */
    protected $_fields = [
      '_message_html',
    ];

    /**
     * Runs Markdown HTML conversion against $field
     * @param string $field field to process
     * @param array $config data to use when processing
     * @todo currently doesnt support anything outside of viewVars[$field]
     */
    protected function _process($field, array $config)
    {
      if (isset($config['viewVars'][$field])) :
        $config['viewVars'][$field] = Markdown::toHtml($config['viewVars'][$field]);
      endif;

      return $config;
    }
}

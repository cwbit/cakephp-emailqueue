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
     * Converts viewVars._message_html to HTML
     */
    public function process(array $config)
    {
      # run _message_html thru Markdown
      if (isset($config['viewVars']['_message_html'])) :
        $config['viewVars']['_message_html'] = Markdown::toHtml($config['viewVars']['_message_html']);
      endif;

      return $config;
    }
}

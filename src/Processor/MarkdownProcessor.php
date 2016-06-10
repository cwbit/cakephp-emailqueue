<?php

namespace EmailQueue\Processor;

use EmailQueue\Processor\Processor;
use Markdown\Lib\Markdown;

class MarkdownProcessor extends Processor
{
    /**
     * Converts viewVars._message_html to HTML
     */
    public function process(array &$config)
    {
      if (isset($config['viewVars']['_message_html'])) :
        $config['viewVars']['_message_html'] = Markdown::toHtml($config['viewVars']['_message_html']);
      endif;
    }
}

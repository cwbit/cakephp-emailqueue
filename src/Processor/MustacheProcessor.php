<?php

namespace EmailQueue\Processor;

use EmailQueue\Processor\Processor;
use Mustache\Mustache_Engine as Mustache;

class MarkdownProcessor extends Processor
{
    /**
     * Converts viewVars._message_html and viewVars._message_text
     */
    public function process(array &$config)
    {

      if (isset($config['viewVars']['_message_html'])) :
        $config['viewVars']['_message_html'] = (new Mustache)->render($config['viewVars']['_message_html'], $config['viewVars']);
      endif;
      if (isset($config['viewVars']['_message_text'])) :
        $config['viewVars']['_message_text'] = (new Mustache)->render($config['viewVars']['_message_text'], $config['viewVars']);
      endif;
    }
}

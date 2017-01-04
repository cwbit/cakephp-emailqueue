<?php

namespace EmailQueue\Processor;

use EmailQueue\Processor\Processor;
use Mustache_Engine as Mustache;

/**
 * Add the MustacheProcessor to your email's list of processors to parse the message bodies thru the Mustache Templateing Engine
 * To see a list of supported styles, ..
 * @see https://github.com/bobthecow/mustache.php/wiki
 */
class MustacheProcessor extends Processor
{
    /**
     * Converts viewVars._message_html and viewVars._message_text
     */
    public function process(array $config)
    {
      # run _message_html thru Mustache
      if (isset($config['viewVars']['_message_html'])) :
        $config['viewVars']['_message_html'] = (new Mustache)->render($config['viewVars']['_message_html'], $config['viewVars']);
      endif;

      # run _message_text thru Mustache
      if (isset($config['viewVars']['_message_text'])) :
        $config['viewVars']['_message_text'] = (new Mustache)->render($config['viewVars']['_message_text'], $config['viewVars']);
      endif;

      return $config;
    }
}

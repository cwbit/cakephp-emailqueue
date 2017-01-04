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
   * @var array of fields to process
   * @todo currently only supports fields inside viewVars
   */
    protected $_fields = [
      '_message_html',
      '_message_text',
    ];

    /**
     * Runs Mustache templating against $field given $config['viewVars']
     * @param string $field field to process
     * @param array $config data to use when processing
     * @todo currently doesnt support anything outside of viewVars[$field]
     */
    protected function _process($field, array $config)
    {
      if (isset($config['viewVars'][$field])) :
        $config['viewVars'][$field] = (new Mustache)->render($config['viewVars'][$field], $config['viewVars']);
      endif;

      return $config;
    }

    /**
     * setter for where we can find the data for Mustache to insert
     * @param string $dataPath to process
     */
    public function setDataPath(string $dataPath)
    {
      $this->_dataPath = $dataPath;
    }

    /**
     * getter for where we can find the data for Mustache to insert
     * @return string $dataPath to process
     */
    public function getDataPath()
    {
      return $this->_dataPath;
    }
}

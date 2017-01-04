<?php

namespace EmailQueue\Processor;

use EmailQueue\Processor\Processor;
use Mustache_Engine as Mustache;
use Cake\Utility\Hash;

/**
 * Add the MustacheProcessor to your email's list of processors to parse the message bodies thru the Mustache Templateing Engine
 * To see a list of supported styles, ..
 * @see https://github.com/bobthecow/mustache.php/wiki
 */
class MustacheProcessor extends Processor
{

  /**
   * @var array of fields to process
   */
    protected $_fields = [
      'viewVars._message_html',
      'viewVars._message_text',
    ];

    /**
     * Hash path for the data to be passed to Mustache
     */
    protected $_dataPath = 'viewVars';

    /**
     * Runs Mustache templating against $field given $config['viewVars']
     * @param string $field field to process
     * @param array $config data to use when processing
     */
    protected function _process($field, array $config)
    {
      if (Hash::check($config, $field)) :
        return Hash::insert(
            $config,
            $field,
            (new Mustache)->render(
              Hash::get($config, $field),
              Hash::get($config, $this->getDataPath()))
            );
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

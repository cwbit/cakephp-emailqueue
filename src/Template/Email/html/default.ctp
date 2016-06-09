<?php
  /**
   * This is a dynamic template file that uses a reserved value of $_message ($viewVars['_message'])
   * To use this, set EmailTemplate.message_html to any block of text and it will print out Here
   * Ideally, set EmailTemplate.message_html to a block of Markdown text and it will get rendered into HTML
   */
?>
<?= $this->Markdown->toHtml($_message_html); ?>

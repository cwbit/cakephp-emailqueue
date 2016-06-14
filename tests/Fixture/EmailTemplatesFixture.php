<?php
namespace EmailQueue\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EmailTemplatesFixture
 *
 */
class EmailTemplatesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'email_type' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'subject' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'message_html' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'message_text' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'from_addr' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'sender_addr' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'to_addr' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'cc_addr' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'bcc_addr' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'replyTo' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'readReceipt' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'returnPath' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'messageId' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'headers' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'viewRender' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'template' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => 'EmailQueue.default', 'comment' => '', 'precision' => null, 'fixed' => null],
        'theme' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'layout' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => 'EmailQueue.default', 'comment' => '', 'precision' => null, 'fixed' => null],
        'viewVars' => ['type' => 'text', 'length' => 16777215, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'attachments' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'emailFormat' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => 'both', 'comment' => '', 'precision' => null, 'fixed' => null],
        'transport' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => 'default', 'comment' => '', 'precision' => null, 'fixed' => null],
        'processor' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'log' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'helpers' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 'd9590e95-b4ee-4635-b17d-598907aed88b',
            'email_type' => 'Lorem ipsum dolor sit amet',
            'subject' => 'Lorem ipsum dolor sit amet',
            'message_html' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'message_text' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'from_addr' => 'Lorem ipsum dolor sit amet',
            'sender_addr' => 'Lorem ipsum dolor sit amet',
            'to_addr' => 'Lorem ipsum dolor sit amet',
            'cc_addr' => 'Lorem ipsum dolor sit amet',
            'bcc_addr' => 'Lorem ipsum dolor sit amet',
            'replyTo' => 'Lorem ipsum dolor sit amet',
            'readReceipt' => 'Lorem ipsum dolor sit amet',
            'returnPath' => 'Lorem ipsum dolor sit amet',
            'messageId' => 'Lorem ipsum dolor sit amet',
            'headers' => 'Lorem ipsum dolor sit amet',
            'viewRender' => 'Lorem ipsum dolor sit amet',
            'template' => 'Lorem ipsum dolor sit amet',
            'theme' => 'Lorem ipsum dolor sit amet',
            'layout' => 'Lorem ipsum dolor sit amet',
            'viewVars' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'attachments' => 'Lorem ipsum dolor sit amet',
            'emailFormat' => 'Lorem ipsum dolor sit amet',
            'transport' => 'Lorem ipsum dolor sit amet',
            'processor' => 'Lorem ipsum dolor sit amet',
            'log' => 'Lorem ipsum dolor sit amet',
            'helpers' => 'Lorem ipsum dolor sit amet',
            'created' => '2016-06-14 16:27:58',
            'modified' => '2016-06-14 16:27:58'
        ],
    ];
}

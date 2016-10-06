<?php
use Phinx\Seed\AbstractSeed;
use Cake\Core\Configure;
use Cake\Utility\Text;
/**
 * EmailTemplates seed.
 */
class EmailTemplatesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [[
          'id'          => Text::uuid(),
          'email_type'  => 'contact',
          'subject'     => 'New Contact Request',
          'to_addr'     => json_encode(Configure::read('Company.email')),
          'viewVars'    => json_encode([
            'name'      => 'not specified', # these will be overridden with actual values from the form
            'email'     => 'not specified',
            'phone'     => 'not specified',
            'message'   => 'not specified',
          ], JSON_PRETTY_PRINT),
          'message_html' =>"
# New Contact Request

Hi there! Someone just filled out the contact form on your site.

**Name:**
> {{name}}

**Email:**
> {{email}}

**Phone:**
> {{phone}}

**Message:**
> {{message}}
          ",
          'message_text' => "
New Contact Request\n
\n
Hi there. Someone just filled out the contact form on your site.\n
\n
Name: {{name}}\n
Email: {{email}}\n
Phone: {{phone}}\n
Message:\n
{{message}}\n
          ",
        ], # end of type `contact`

        ];

        $table = $this->table('email_templates');
        $table->insert($data)->save();
    }
}

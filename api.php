<?php

$_provides['pluginClasses'] = array(
    'jojo_plugin_jaijaz_emailer'        => 'Jaijaz Emailer - Parent',
    'jojo_plugin_jaijaz_emailer_events' => 'Jaijaz Emailer - Event Handler'
);

$_options[] = array(
    'id'          => 'emailer_throttle',
    'category'    => 'Emailer',
    'label'       => 'Email send throttle',
    'description' => 'How many messages to send per minute',
    'type'        => 'text',
    'default'     => '100',
    'options'     => '',
    'plugin'      => 'jaijaz_emailer'
);

$_options[] = array(
    'id'          => 'emailer_bounce',
    'category'    => 'Emailer',
    'label'       => 'Bounce address',
    'description' => 'Where bounce emails to',
    'type'        => 'text',
    'default'     => '',
    'options'     => '',
    'plugin'      => 'jaijaz_emailer'
);

$_options[] = array(
    'id'          => 'emailer_template',
    'category'    => 'Emailer',
    'label'       => 'Base HTML Template',
    'description' => 'the name of a TPL file that has been created on the server',
    'type'        => 'text',
    'default'     => '',
    'options'     => '',
    'plugin'      => 'jaijaz_emailer'
);

$_options[] = array(
    'id'          => 'emailer_smtpserver',
    'category'    => 'Emailer',
    'label'       => 'SMTP Server',
    'description' => 'SMTP Server to use with Emailer plugin',
    'type'        => 'text',
    'default'     => '',
    'options'     => '',
    'plugin'      => 'jaijaz_emailer'
);

$_options[] = array(
    'id'          => 'emailer_smtpuser',
    'category'    => 'Emailer',
    'label'       => 'SMTP User',
    'description' => 'SMTP User name to use with Emailer plugin',
    'type'        => 'text',
    'default'     => '',
    'options'     => '',
    'plugin'      => 'jaijaz_emailer'
);

$_options[] = array(
    'id'          => 'emailer_smtppwd',
    'category'    => 'Emailer',
    'label'       => 'SMTP Password',
    'description' => 'SMTP Password to use with Emailer plugin',
    'type'        => 'text',
    'default'     => '',
    'options'     => '',
    'plugin'      => 'jaijaz_emailer'
);

$_options[] = array(
    'id'          => 'emailer_smtpport',
    'category'    => 'Emailer',
    'label'       => 'SMTP Port',
    'description' => 'SMTP Port to use with Emailer plugin',
    'type'        => 'text',
    'default'     => '25',
    'options'     => '',
    'plugin'      => 'jaijaz_emailer'
);

$_options[] = array(
    'id'          => 'emailer_sendgrid_smtpapi',
    'category'    => 'Emailer',
    'label'       => 'Use Send Grid SMTP API',
    'description' => 'Use Send Grid SMTP API in outgoing emails',
    'type'        => 'radio',
    'default'     => 'no',
    'options'     => 'yes,no',
    'plugin'      => 'jaijaz_emailer'
);

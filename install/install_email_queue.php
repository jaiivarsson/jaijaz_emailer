<?php

$table = 'email_queue';
$query = "
    CREATE TABLE {email_queue} (
        `email_queueid` int(11) NOT NULL AUTO_INCREMENT,
        `receiverid` int(11) NOT NULL DEFAULT '0',
        `messageid` int(11) NOT NULL DEFAULT '0',
        `plugin` varchar(255) NOT NULL DEFAULT '',
        `to_address` varchar(255) NOT NULL DEFAULT '',
        `to_name` varchar(255) NOT NULL DEFAULT '',
        `from_address` varchar(255) NOT NULL DEFAULT '',
        `from_name` varchar(255) NOT NULL DEFAULT '',
        `template` int(11) NOT NULL DEFAULT '0',
        `subject` varchar(255) NOT NULL DEFAULT '',
        `text` int(11) NOT NULL DEFAULT '0',
        `merge_fields` text NOT NULL DEFAULT '',
        `smtpapi` text NOT NULL DEFAULT '',
        `send_embargo` bigint(20) NOT NULL DEFAULT '0',
        `send_status` enum('queued','sent','failed') NOT NULL DEFAULT 'queued',
        `sent_date` bigint(20) NOT NULL DEFAULT '0',
        `email_read` enum('yes','no') NOT NULL DEFAULT 'no',
        PRIMARY KEY (`email_queueid`),
        KEY `receiverid` (`receiverid`),
        KEY `messageid` (`messageid`),
        KEY `text` (`text`),
        KEY `plugin` (`plugin`),
        KEY `send_embargo` (`send_embargo`),
        KEY `send_status` (`send_status`),
        KEY `sent_date` (`sent_date`),
        KEY `email_read` (`email_read`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8";

/* Check table structure */
$result = Jojo::checkTable($table, $query);

/* Output result */
if (isset($result['created'])) {
    echo sprintf("email_queue: Table <b>%s</b> Does not exist - created empty table.<br />", $table);
}

if (isset($result['added'])) {
    foreach ($result['added'] as $col => $v) {
        echo sprintf("email_queue: Table <b>%s</b> column <b>%s</b> Does not exist - added.<br />", $table, $col);
    }
}

if (isset($result['different'])) Jojo::printTableDifference($table,$result['different']);



/* to help keep the database size in check the body of messages are kept in an un merged state in this table. This way newsletters for example can have 1000's of entries in the queue but just one entry in this table */
$table = 'email_text';
$query = "
    CREATE TABLE {email_text} (
        `email_textid` int(11) NOT NULL AUTO_INCREMENT,
        `text_html` text NOT NULL DEFAULT '',
        `text_text` text NOT NULL DEFAULT '',
        PRIMARY KEY (`email_textid`),
        FULLTEXT KEY (`text_html`),
        FULLTEXT KEY (`text_text`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8";

/* Check table structure */
$result = Jojo::checkTable($table, $query);

/* Output result */
if (isset($result['created'])) {
    echo sprintf("email_text: Table <b>%s</b> Does not exist - created empty table.<br />", $table);
}

if (isset($result['added'])) {
    foreach ($result['added'] as $col => $v) {
        echo sprintf("email_text: Table <b>%s</b> column <b>%s</b> Does not exist - added.<br />", $table, $col);
    }
}

if (isset($result['different'])) Jojo::printTableDifference($table,$result['different']);


/* this table logs all event notifications from Send Grid */
$table = 'email_eventlog';
$query = "
    CREATE TABLE {email_eventlog} (
        `email_eventlogid` int(11) NOT NULL AUTO_INCREMENT,
        `recipient` varchar(255) NOT NULL DEFAULT '',
        `event_type` varchar(255) NOT NULL DEFAULT '',
        `category` varchar(255) NOT NULL DEFAULT '',
        `fields_other` text NOT NULL DEFAULT '',
        PRIMARY KEY (`email_eventlogid`),
        KEY `recipient` (`recipient`),
        KEY `event_type` (`event_type`),
        KEY `category` (`category`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8";

/* Check table structure */
$result = Jojo::checkTable($table, $query);

/* Output result */
if (isset($result['created'])) {
    echo sprintf("email_eventlog: Table <b>%s</b> Does not exist - created empty table.<br />", $table);
}

if (isset($result['added'])) {
    foreach ($result['added'] as $col => $v) {
        echo sprintf("email_eventlog: Table <b>%s</b> column <b>%s</b> Does not exist - added.<br />", $table, $col);
    }
}

if (isset($result['different'])) Jojo::printTableDifference($table,$result['different']);


/* to help keep the database size in check the body of messages are kept in an un merged state in this table. This way newsletters for example can have 1000's of entries in the queue but just one entry in this table */
$table = 'email_template';
$query = "
    CREATE TABLE {email_template} (
        `email_templateid` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL DEFAULT '',
        `tpl_filename` varchar(255) NOT NULL DEFAULT '',
        PRIMARY KEY (`email_templateid`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8";

/* Check table structure */
$result = Jojo::checkTable($table, $query);

/* Output result */
if (isset($result['created'])) {
    echo sprintf("email_text: Table <b>%s</b> Does not exist - created empty table.<br />", $table);
}

if (isset($result['added'])) {
    foreach ($result['added'] as $col => $v) {
        echo sprintf("email_text: Table <b>%s</b> column <b>%s</b> Does not exist - added.<br />", $table, $col);
    }
}

if (isset($result['different'])) Jojo::printTableDifference($table,$result['different']);

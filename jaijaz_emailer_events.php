<?php
/**
 *                     Jaijaz Emailer module
 *                ===============================
 *
 * Copyright 2010 Jaijaz <info@jaijaz.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Jai Ivarsson <jai@jaijaz.co.nz>
 * @link    http://www.jaijaz.co.nz
 */

class Jojo_Plugin_Jaijaz_emailer_events extends Jojo_Plugin
{
    
    function _getContent()
    {
        global $smarty;
        $content = array();
        Jojo::noFormInjection();
        $eventData = $_POST;
        if ($eventData['email_queueid'] == 0) {
            /* TODO: send back a 404 message */
        }
        if ($eventData['email_address'] == '') {
            /* TODO: send back a 404 message */
        }
        $email = Jojo::selectRow("SELECT * FROM {email_queue} WHERE email_queueid = ?", $eventData['email_queueid']);
        if (!$email) {
            /* TODO: send back a 404 message */
        }
        $res = Jojo::insertQuery("INSERT INTO {email_eventlog} SET recipient = ?, email_queueid = ?, event_type = ?, plugin = ?, fields_other = ?", array($eventData['email'], $eventData['email_queueid'], $eventData['event'], $eventData['plugin'], serialize($eventData)));
        
        $eventData['justOpened'] = false;
        if ($email['email_read'] == 'no' && ($eventData['event'] == 'open' || $eventData['event'] == 'click')) {
            Jojo::updateQuery("UPDATE {email_queue} SET email_read = ? WHERE email_queueid = ?", array('yes', $eventData['email_queueid']));
            $eventData['justOpened'] = true;
        }

        if (isset($eventData['plugin'])) {
            $activeplugin = 'Jojo_Plugin_' . $eventData['plugin'];
            if (class_exists($activeplugin)) {
                call_user_func(array($activeplugin, 'processEvent'), $eventData);
            }
        }

        $content['content']  = 'event received';
        return $content;
    }

    function getCorrectUrl()
    {
        //Assume the URL is correct
        return _PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }

    
}

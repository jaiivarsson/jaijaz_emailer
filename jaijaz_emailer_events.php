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
        $email_queueid = Jojo::getFormData('email_queueid',0);
        if ($email_queueid == 0) {
            /* TODO: send back a 404 message */
        }
        $email_address = Jojo::getFormData('email','');
        if ($email_address == '') {
            /* TODO: send back a 404 message */
        }
        $email = Jojo::selectRow("SELECT * FROM {email_queue} WHERE email_queueid = ?", $email_queueid);
        if (!$email) {
            /* TODO: send back a 404 message */
        }
        $plugin = Jojo::getFormData('plugin','');
        $event = Jojo::getFormData('event','');
        $data = array();
        foreach ($_POST as $key => $value) {
            $data[$key] = $value;
        }
        $res = Jojo::insertQuery("INSERT INTO {email_eventlog} SET recipient = ?, email_queueid = ?, event_type = ?, plugin = ?, fields_other = ?", array($email_address, $email['email_queueid'], $event, $plugin, serialize($data)));
        
        
        if ($email['email_read'] == 'no' && ($event == 'open' || $event == 'click')) {
            Jojo::updateQuery("UPDATE {email_queue} SET email_read = ? WHERE email_queueid = ?", array('yes', $email_queueid));
        }

        $activeplugin = 'Jojo_Plugin_' . $plugin;
        if (class_exists($activeplugin)) {
            call_user_func(array($activeplugin, 'processEvent'), $data);
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

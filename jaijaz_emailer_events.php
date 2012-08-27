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


        $result = call_user_func(array($activeplugin, 'process'));
        jojo::runhook('jaijaz_emailer_event', array($plugin, $data));

        $content['title']      = 'title here';     //optional title, will be displayed as the h1 heading, amongst other uses. defaults to whatever was entered in the admin section.
        $content['seotitle']   = 'seo title here'; //optional seo title, will be displayed as the main title for the page, and in google results. defaults to whatever was entered in the admin section.
        $content['css']        = '';               //need some css code just for this page? add the code to this variable and it will be included in the document head, just for this page. <style> tags are not required.
        $content['javascript'] = '';               //same as for css - <script> tags are not required.
        $content['content']  = $smarty->fetch('empty_plugin.tpl');
        return $content;
    }

    function getCorrectUrl()
    {
        //Assume the URL is correct
        return _PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }

    
}

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

class Jojo_Plugin_Jaijaz_emailer extends Jojo_Plugin
{
    /*
function _getContent()
    {
        global $smarty;
        $content = array();

        //$content['title']      = 'TITLE HERE';     //optional title, will be displayed as the H1 heading, amongst other uses. Defaults to whatever was entered in the admin section.
        //$content['seotitle']   = 'SEO TITLE HERE'; //optional SEO title, will be displayed as the main title for the page, and in Google results. Defaults to whatever was entered in the admin section.
        //$content['css']        = '';               //need some CSS code just for this page? Add the code to this variable and it will be included in the document head, just for this page. <style> tags are not required.
        //$content['javascript'] = '';               //Same as for CSS - <script> tags are not required.
        $content['content']  = $smarty->fetch('empty_plugin.tpl');
        return $content;
    }

    function getCorrectUrl()
    {
        //Assume the URL is correct
        return _PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }
*/
    
    function sendQueuedEmails() {
        $emails = Jojo::selectQuery("SELECT email_queueid FROM {email_queue} WHERE send_status = ? AND send_embargo < ?", array('queued', time());
        if (!$emails)
            return true;
        
        // get the emailer class
        foreach (Jojo::listPlugins('classes/jaijaz_emailer_email.class.php') as $pluginfile) {
            require_once($pluginfile);
            break;
        }
        
        // create the emailer object for each email and send it
        foreach ($emails as $e => $email) {
            $email = new Jaijaz_Emailer_Email($message['email_queueid']);
    
            $result = $email->sendEmail();
        }
        return true;
    }
}
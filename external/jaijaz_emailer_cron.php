<?php

/*
 * To be called by a cron job to get send the queued emails
 * 
 * @author Jai Ivarsson
 */

// get the list of messages to send
$messages = Jojo::selectQuery("SELECT email_queueid FROM {email_queue} WHERE send_embargo < ? AND send_status = ?", array(time(), 'queued'));

// if no messages to send exit
if (!$messages)
    exit;

echo "Found " . count($messages) . "messages to send<br />";

// get the emailer class
foreach (Jojo::listPlugins('classes/jaijaz_emailer_email.class.php') as $pluginfile) {
    require_once($pluginfile);
    break;
}
$sentCount = 0;
$failedCount = 0;
$newslettersSent = array();
foreach ($messages as $m => $message) {
    $email = new Jaijaz_Emailer_Email($message['email_queueid']);
    
    $result = $email->sendEmail();
    if ($result) {
        echo "Success: Email " . $email->email_queueid . " to " . $email->to_name . " sent<br />";
        $sentCount++;
        if ($email->plugin == 'jaijaz_newsletter' && !in_array($email->messageid, $newslettersSent)) {
            $newslettersSent[] = $email->messageid;
        }
    } else {
        echo "FAILED: Email " . $email->email_queueid . " to " . $email->to_name . " sent<br />";
        $failedCount++;
    }
}
if (!empty($newslettersSent)) {
    foreach ($newslettersSent as $newletter) {
        Jojo::insertQuery("UPDATE {newsletter_messages} SET status = ?, send_date = ? WHERE newsletter_messageid = ?", array('sent', time(), $newletter));
    }
}

echo "Sent " . $sentCount . " messages and failed on " . $failedCount . " message of a total " . count($messages);

exit;
<?php
/*
 *      Emailer Email Class
 * Creates an email object to be saved to the database and to be sent using Swiftmailer
 * 
 * @author Jai Ivarsson <jai@jaijaz.co.nz>
 * @copyright Jaijaz Ltd www.jaijaz.co.nz
 * 
 * @package Jaijaz_Emailer_Email
 */

class Jaijaz_Emailer_Email {

    private $email_queueid = 0;
    private $receiverid;
    private $messageid = 0;
    private $plugin;
    private $to_address;
    private $to_name;
    private $from_address;
    private $from_name;
    private $templateid = 0;
    private $template_filename;
    private $subject;
    private $textid;
    private $message_html;
    private $message_text;
    private $merge_fields;
    private $smtpapi;
    private $send_embargo;
    private $send_status;
    private $sent_date = 0;
    private $email_read = 'no';
    
    
    /**
     * If an id is passed then it builds from the database else creates a new object
     * 
     * @param int $id
     */
    public function __construct($id = false) {
        if ($id) {
            $data = Jojo::selectRow("SELECT e.*, t.text_html, t.text_text FROM {email_queue} e, {email_text} t WHERE e.text = t.email_textid AND e.email_queueid = ?", $id);
            $this->email_queueid        = $id;
            $this->receiverid           = $data['receiverid'];
            $this->messageid            = $data['messageid'];
            $this->plugin               = $data['plugin'];
            $this->to_address           = $data['to_address'];
            $this->to_name              = $data['to_name'];
            $this->from_address         = Jojo::either($data['from_address'], _FROMADDRESS);
            $this->from_name            = Jojo::either($data['from_name'], _FROMNAME);
            $this->templateid           = $data['template'];
            $this->subject              = $data['subject'];
            $this->textid               = $data['text'];
            $this->message_html         = $data['text_html'];
            $this->message_text         = $data['text_text'];
            $this->merge_fields         = unserialize($data['merge_fields']);
            $this->smtpapi              = unserialize($data['smtpapi']);
            $this->send_embargo         = $data['send_embargo'];
            $this->send_status          = $data['send_status'];
            $this->sent_date            = $data['sent_date'];
            $this->email_read           = $data['email_read'];
            
            if ($data['template'] != 0) {
                $template = Jojo::selectRow("SELECT * FROM {email_template} WHERE email_templateid = ?", $data['template']);
                $this->template_filename = $template['tpl_filename'];
            }
        } else {
            $this->send_embargo = time();
            $this->send_status = 'queued';
            $this->message_text = '';
            $this->message_html = '';
        }
    }

    /**
     * Global getter
     * 
     * @param type $property
     * @return type 
     */
    public function __get($property) {
        return $this->$property;
    }

    /**
     * Global setter
     * 
     * @param type $property
     * @param type $value 
     */
    public function __set($property, $value) {
        $this->$property = $value;
    }
    
    /**
     * loops through the merge fields and string replaces the text content
     * 
     * @return string $replaceContent
     */
    private function replaceMergeFields($text = '') {
        foreach ($this->merge_fields as $key => $value) {
            if ($key == 'unsubscribe') {
                $user = Jojo::selectRow("SELECT token FROM {newsletter_subscribers} WHERE newsletter_subscriberid = ?", $this->receiverid);
                $unsubscribeLink = _SITEURL . "/unsubscribe/" . $this->messageid . "/" . $user['token'];
                $value = 'If you\'d like to unsubscribe and stop receiving these email <a href="' . $unsubscribeLink . '">click here</a>';
                $text = str_replace('[[unsubscribe]]', $value, $text);
            } else {
                $search = "[[" . $key . "]]";
                $text = str_replace($search, $value, $text);
            }
        }
        return $text;
    }
    
    /**
     * returns the merged html content
     * 
     * @return string $mergedHtml
     */
    private function mergedHtml() {
        return $this->replaceMergeFields($this->message_html);
    }
    
    /**
     * returns the merged text content
     * 
     * @return string $mergedText
     */
    private function mergedText() {
        return $this->replaceMergeFields($this->message_text);
    }
    
    /**
     * creates the json array for Send Grid's X-SMTPAPI
     * 
     * @return string $json
     */
    private function setXSmtpApi() {
        /* build the array */
        $smtpApiArray = array();
        $smtpApiArray['unique_args'] = array();
        if ($this->email_queueid == 0) {
            $this->saveToDb();
        }
        $smtpApiArray['unique_args']['email_queueid'] = $this->email_queueid;
        if ($this->receiverid) {
            $smtpApiArray['unique_args']['receiverid'] = $this->receiverid;
        }
        if ($this->plugin != '') {
            $smtpApiArray['unique_args']['plugin'] = $this->plugin;
        }
        /* loop through the smtpapi fields and add each unique field */
        foreach ($this->smtpapi as $key => $value) {
            $smtpApiArray['unique_args'][$key] = $value;
        }
        /* json encode the array */
        $json = json_encode($smtpApiArray);
        // Add spaces so that the field can be folded
        $json = preg_replace('/(["\]}])([,:])(["\[{])/', '$1$2 $3', $json);
        return $json;
    }
    
    /**
     * adds the content to the email template
     * 
     * @global type $smarty
     * @return string $contentedTemplate
     */
    private function addContentToTemplate() {
        global $smarty;
        $smarty->assign('content', $this->mergedHtml());
        $smarty->assign('subject', $this->subject);
        
        // if there isn't a template_name the get it if there is a template
        if (!$this->template_filename && $this->templateid) {
            $this->template_filename = $this->getTemplateFileName();
        }
        
        $template = Jojo::either($this->template_filename, Jojo::getOption('emailer_template'), 'emailer_basic_template_html.tpl');
        $textOutput = $this->replaceMergeFields($smarty->fetch($template));
        return $textOutput;
    }
    
    /**
     * returns the template file name for the templateid
     * 
     * @return string $templateFileName
     */
    private function getTemplateFileName()
    {
        $template = Jojo::selectRow("SELECT * FROM {email_template} WHERE email_templateid = ?", $this->templateid);
        return $template['tpl_filename'];
    }
    
    /**
     * optional check to make sure that this message hasn't been sent before to this receiver.
     * requires that a messageid from the plugin be passed.
     * 
     * @return boolean true if not duplicated
     */
    public function checkNotDuplicate() {
        if (!$this->messageid)
            return true;
        
        $existing = Jojo::selectRow("SELECT * FROM {email_queue} WHERE plugin = ? AND messageid = ? AND (receiverid = ? OR to_address = ?)", array($this->plugin, $this->messageid, $this->receiverid, $this->to_address));
        if ($existing) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * checks to see if the text has be used before, if it has will return that saves the html and text into it's table 
     * 
     * @return int $textid
     */
    private function saveText() {
        /* see if this exact message text has been saved before */
        $messageText = Jojo::selectRow("SELECT * FROM {email_text} WHERE text_html = ? AND text_text = ?", 
                                            array($this->message_html, $this->message_text));
        if ($messageText) {
            $textid = $messageText['email_textid'];
        } else {
            $textid = Jojo::insertQuery("INSERT INTO {email_text} SET text_html = ?, text_text = ?", array($this->message_html, $this->message_text));
        }
        return $textid;
    }
    
    /**
     * creates an array ready to be saved into the database
     * 
     * @return array $data
     */
    private function createSaveArray() {
        $data = array();
        $data['receiverid']     = ($this->receiverid) ? $this->receiverid : 0;
        $data['messageid']      = $this->messageid;
        $data['plugin']         = $this->plugin;
        $data['to_address']     = $this->to_address;
        $data['to_name']        = $this->to_name;
        $data['from_address']   = $this->from_address;
        $data['from_name']      = $this->from_name;
        $data['template']       = $this->templateid;
        $data['subject']        = $this->subject;
        $data['text']           = $this->saveText();
        $data['merge_fields']   = serialize($this->merge_fields);
        $data['smtpapi']        = serialize($this->smtpapi);
        $data['send_embargo']   = $this->send_embargo;
        $data['send_status']    = $this->send_status;
        $data['sent_date']      = $this->sent_date;
        $data['email_read']     = $this->email_read;
        if ($this->email_queueid) {
            $data['email_queueid']  = $this->email_queueid;
        }
        
        return $data;
    }
    
    /**
     * Saves the message object to the database queue
     * @return boolean 
     */
    public function saveToDb()
    {
        $this->text = $this->saveText();
        
        $data = $this->createSaveArray();
        
        $query  = "";
        $query .= ($this->email_queueid) ? "UPDATE " : "INSERT INTO ";
        $query .= "{email_queue} SET 
                        receiverid = ?,
                        messageid = ?,
                        plugin = ?,
                        to_address = ?,
                        to_name = ?,
                        from_address = ?,
                        from_name = ?,
                        template = ?,
                        subject = ?,
                        text = ?,
                        merge_fields = ?,
                        smtpapi = ?,
                        send_embargo = ?,
                        send_status = ?,
                        sent_date = ?,
                        email_read = ?";
        
        if ($this->email_queueid) {
            $query .= " WHERE email_queueid = ?";
            $res = Jojo::updateQuery($query, $data);
        } else {
            $res = $this->email_queueid = Jojo::insertQuery($query, $data);
        }
        
        return $res;
    }
    
    /**
     * sends the email
     * 
     * @return boolean $result
     */
    public function sendEmail() {
        /* make sure we have a database entry for it */
        if (!$this->email_queueid) {
            $this->saveToDb();
        }
        
        /* check to see if it OK to send it now */
        if ($this->send_embargo > time())
            return false;
        
        /* include the swiftmailer classes */
        foreach (Jojo::listPlugins('external/swiftmailer/lib/swift_required.php') as $pluginfile) {
            require_once($pluginfile);
            break;
        }
        
        /* build the swiftmailer transport object */
        $hostname = Jojo::either(Jojo::getOption('emailer_smtpserver'), Jojo::getOption('smtp_mail_host'));
        $hostport = Jojo::either(Jojo::getOption('emailer_smtpport'), Jojo::getOption('smtp_mail_port', 25));
        $hostuser = Jojo::either(Jojo::getOption('emailer_smtpuser'), Jojo::getOption('smtp_mail_user'));
        $hostpass = Jojo::either(Jojo::getOption('emailer_smtppwd'), Jojo::getOption('smtp_mail_pass'));
        
        $transport = Swift_SmtpTransport::newInstance($hostname, $hostport, 'ssl')
                ->setUsername($hostuser)
                ->setPassword($hostpass)
        ;
        
        /* create the mailer */
        $mailer = Swift_Mailer::newInstance($transport);
        $mailer->registerPlugin(new Swift_Plugins_ThrottlerPlugin(100, Swift_Plugins_ThrottlerPlugin::MESSAGES_PER_MINUTE));
        
        /* set the bounce path */
        $bounce = Jojo::getOption('emailer_bounce', _WEBMASTERADDRESS);
        
        /* build message */
        $email = Swift_Message::newInstance()
            ->setSubject($this->subject)
            ->setFrom(array($this->from_address => $this->from_name))
            ->setTo(array($this->to_address => $this->to_name))
            ->setBody($this->addContentToTemplate(), 'text/html')
            ->addPart($this->mergedText(), 'text/plain')
            ->setReturnPath($bounce)
            ;
        
        /* add the smtpapi headers */
        $email->getHeaders()->addTextHeader('X-SMTPAPI', $this->setXSmtpApi());
        
        /* send the email */
        $result = $mailer->send($email);
        //var_dump($result);
        /* update the database with result */
        if ($result) {
            $this->send_status = 'sent';
        } else {
            $log = new Jojo_Eventlog();
            $log->code = 'jaijaz_emailer';
            $log->importance = 'very low';
            $backtrace = debug_backtrace();
            $log->shortdesc = 'EmailID ' . $this->email_queueid . ' failed to send';
            $log->desc = 'from '. $backtrace[0]['file'] .' line '.$backtrace[0]['line'];
            $log->savetodb();
            unset($log);
            $this->send_status = 'failed';
        }
        $this->sent_date = time();
        $this->saveToDb();
        
        /* return the result */
        return $result;
    }
}

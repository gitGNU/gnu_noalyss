<?php


/**
 * Description of class_sendmail
 *
 * @author dany
 */
require_once 'class_filetosend.php';

class Sendmail
{

    private $mailto;
    private $afile;
    private $subject;
    private $message;
    private $from;
    private $content;

    /**
     * set the from
     * @parameter $p_from has the form name <info@phpcompta.eu>
     */
    function set_from($p_from)
    {
        $this->from = $p_from;
    }

    /**
     * 
     * @param $p_subject set the subject
     */
    function set_subject($p_subject)
    {
        $this->subject = $p_subject;
    }

    /**
     * set the recipient
     * @param type $p_mailto has the form name <email@email.com>
     */
    function mailto($p_mailto)
    {
        $this->mailto = $p_mailto;
    }

    /**
     * body of the message (utf8)
     * @param type $p_message
     */
    function set_message($p_message)
    {
        $this->message = $p_message;
    }

    /**
     * Add file to the message
     * @param FileToSend $file file to add to the message
     */
    function add_file(FileToSend $file)
    {
        $this->afile[] = $file;
    }

    /**
     *  verify that the message is ready to go
     * @throws Exception
     */
    function verify()
    {
        $array = explode(",", "from,subject,mailto,message");
        for ($i = 0; $i < count($array); $i++)
        {
            $name = $array[$i];
            if (trim($this->$name) == "")
            {
                throw new Exception("$name est vide");
            }
        }
    }
    /**
    * create the message before sending
    */
    function compose()
    {
        $this->verify();
        $uid = md5(uniqid(time()));

        // a random hash will be necessary to send mixed content
        $separator = md5(time());

        // carriage return type (we use a PHP end of line constant)
        $eol = PHP_EOL;

        // main header (multipart mandatory)
        $headers = "From: " . $this->from . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
        $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
        $headers .= "This is a MIME encoded message." . $eol . $eol;
        $headers .= $eol . $eol;

        // message
        $headers .= "--" . $separator . $eol;
        $headers .= "Content-Type: text/plain; charset=\"utf-8\"" . $eol;
        $headers .= "Content-Transfer-Encoding: 7bit" . $eol . $eol;
        $headers .= $this->message . $eol . $eol;

        // attachment
        for ($i = 0; $i < count($this->afile); $i++)
        {
            $file = $this->afile[$i];
            $file_size = filesize($file->full_name);
            $handle = fopen($file->full_name, "r");
            $content = fread($handle, $file_size);
            fclose($handle);
            $content = chunk_split(base64_encode($content));
            $headers .= "--" . $separator . $eol;
            $headers .= "Content-Type: " . $file->type . "; name=\"" . $file->filename . "\"" . $eol;
            $headers .= "Content-Disposition: attachment; filename=\"" . $file->filename . "\"" . $eol;
            $headers .= "Content-Transfer-Encoding: base64" . $eol;
            $header.=$eol;
            $headers .= $content . $eol . $eol;
        }
        $headers .= "--" . $separator . "--";
        $this->content = $headers;
    }
    /**
     * Send the message 
     * @throws Exception
     */
    function send()
    {
        //SEND Mail
        if (!mail($this->mailto, $this->subject, "", $this->content))
        {
            throw new Exception('send failed');
        }
    }

}

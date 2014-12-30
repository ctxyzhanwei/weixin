<?php
final class Email {
    static function sendEmail($subject="",$content="",$to=array()) {
        $emailuser = $info['emailuser'];
        $emailpassword = $info['emailpassword'];
        $smtpserver = C('email_server'); 
        $port = C('email_port');
        $smtpuser = C('email_user');
        $smtppwd = C('email_pwd');
        $mailtype = "TXT";
        $sender = C('email_user');
        $smtp = new Smtp($smtpserver,$port,true,$smtpuser,$smtppwd,$sender);
        $subject = $subject;
        $body = $content;
        $to = implode(",", $to);
        //$body = iconv('UTF-8','gb2312',$fetchcontent);inv
        $send=$smtp->sendmail($to,$sender,$subject,$body,$mailtype);
        
    }
}
?>
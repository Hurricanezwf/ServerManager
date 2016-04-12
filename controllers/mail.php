<?php
require_once 'email.class.php';

//$mailto     = '1094646850@qq.com';              //收件人
//$subject    = "test mail";                      //邮件主题
//$body       = date('时间: Y年m月d日 H:i:s');    //邮件内容
//sendmailto($mailto, $subject, $body);


function sendmailto($mailto, $mailsub, $mailbd) {
    if (!file_exists("../conf/cfg.ini")) {
        return false;
    }
    $ini = parse_ini_file("../conf/cfg.ini", true);
    $passwd = $ini['mail']['smtp_user_passwd'];
     
    // --------------------------- smtp config ----------------------------
    $smtpserver         = "smtp.163.com";             //SMTP服务器
    $smtpserverport     = 25;                         //SMTP服务器端口
    $smtpusermail       = $ini['mail']['smtp_user_email'];    //SMTP服务器的用户邮箱
    $smtpemailto        = $mailto;
    $smtpuser           = $ini['mail']['smtp_user_account'];  //SMTP服务器的用户账号
    $smtppass           = base64_decode($passwd);     //SMTP服务器的用户密码
    $mailsubject        = $mailsub;                   //邮件主题
    $mailbody           = $mailbd;                    //邮件内容
    $mailtype           = "HTML";                     //邮件格式
    // --------------------------------------------------------------------
    

    $smtp        = new smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass); //这里面的一个true表示身份验证, 否则不使用身份验证
    $smtp->debug = FALSE; //是否显示发送的调试信息
    $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
    if ($state == "") {
        return false;
    } else {
        return true;
    }
}
?>

<?php
class Mail
{	
	function sendmail($to, $name, $subject = '', $body = '', $attachment = null){
		 vendor('PHPMailer.class#phpmailer'); //从PHPMailer目录导class.phpmailer.php类文件
		 $mail             = new PHPMailer(); //PHPMailer对象
		 $mail->CharSet    = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
		 $mail->IsSMTP();  // 设定使用SMTP服务
		 $mail->SMTPDebug  = 0;                     // 关闭SMTP调试功能
		                                            // 1 = errors and messages
							    // 2 = messages only
		 $mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
		 $mail->SMTPSecure = '';                 // 使用安全协议
		 $mail->Host       = C('email_server');  // SMTP 服务器
		 $mail->Port       = C('email_port');  // SMTP服务器的端口号
		 $mail->Username   = C('email_user');  // SMTP服务器用户名
		 $mail->Password   = C('email_pwd');  // SMTP服务器密码
		 $mail->SetFrom(C('email_user'), C('pwd_email_title'));
		 $mail->AddReplyTo(C('email_user'), C('pwd_email_title'));
		 $mail->Subject    = $subject;
		 $mail->MsgHTML($body);
		 $mail->AddAddress($to, $name);
		 if(is_array($attachment)){ // 添加附件
			 foreach ($attachment as $file){
				 is_file($file) && $mail->AddAttachment($file);
			 }
		 }
		 return $mail->Send() ? true : $mail->ErrorInfo;
	}
}
?>
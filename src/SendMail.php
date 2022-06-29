<?php
namespace penguin_syan\auth_login_use_only_email;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__.'/../vendor/autoload.php';

class SendMail{
   private $mailer, $config;

   function __construct(string $conf_filepass) {
      $this->config = include($conf_filepass);
      $this->mailer = new PHPMailer(true);

      try{
         $this->mailer->CharSet = "UTF-8";
         $this->mailer->SMTPDebug = 0;
         $this->mailer->isSMTP();
         $this->mailer->Host = $this->config['mailer_host'];
         $this->mailer->SMTPAuth = true;
         $this->mailer->Username = $this->config['mailer_username'];
         $this->mailer->Password = $this->config['mailer_password'];
         $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
         $this->mailer->Port = $this->config['mailer_port'];
         $this->mailer->setFrom($this->config['mailer_setFrom'], $this->config['mailer_setFromHeadler']);
      }
      catch(Exception $e){
         return false;
      }
   }

   function set_login_key(int $login_key) {
      $this->mailer->isHTML(false);
      $this->mailer->Subject = '['.$this->config['service_name'].']Login code';
      $this->mailer->Body = "Your login code:[$login_key]";
      $this->mailer->AltBody = "Your login code:[$login_key]";
   }
   
   function send(string $to_address) {
      try {
         $this->mailer->addAddress($to_address);
         $this->mailer->send();
         return true;
      }
      catch(Exception $e){
         return false;
      }
   }

}


<?php
namespace penguin_syan\auth_login_use_only_email;

use penguin_syan\auth_login_use_only_email\SendMail;
use penguin_syan\auth_login_use_only_email\ConnectDB;

require_once __DIR__.'/SendMail.php';
require_once __DIR__.'/ConnectDB.php';

class Agent {

    public static function token(bool $admit_newuser, string $email, string $conf_filepass) {
        $user_mail = htmlspecialchars($email);
        $onetime_mailer = new SendMail($conf_filepass);

        if($admit_newuser) {
            $onetime_pass = mt_rand(100000, 999999);
            $db = new ConnectDB($conf_filepass);
            $db->add_onetime_password($user_mail, $onetime_pass);
            $onetime_mailer->set_login_key($onetime_pass);
        } else {

        }

        $onetime_mailer->send(htmlspecialchars($user_mail));
    }

}

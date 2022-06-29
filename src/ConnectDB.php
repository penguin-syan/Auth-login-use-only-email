<?php
namespace penguin_syan\auth_login_use_only_email;

use PDO;

class ConnectDB {
    private $pdo;

    function __construct(string $conf_filepass) {
        $config = include($conf_filepass);
        $this->pdo = new PDO(
            $config['database_dsn'],
            $config['database_username'],
            $config['database_password']
        );
    }

    function add_onetime_password(string $user_mail, string $onetime_password) {
        $del_datetime = date('Y-m-d H:i:s',strtotime("now  +30 min"));
        $user_mail = password_hash($user_mail, PASSWORD_DEFAULT);
        $sql_command = "insert into one_pass values('".$user_mail."', $onetime_password, '$del_datetime');";
        $sql = $this->pdo->prepare($sql_command);
        $sql->execute();
    }

    function login_authentication(string $user_mail, string $onetime_password) {
        $sql_command = "select hash_mail as mail from one_pass where onetime_password = $onetime_password and invalidate_time > now();";
        $sql = $this->pdo->prepare($sql_command);
        $sql->execute();

        foreach ($sql as $value) {
            if(password_verify($user_mail, $value['mail']))
                return true;
        }

        return false;
    }

}

/*
テーブル構造
TABLENAME one_pass
  column hash_mail string 255
  column onetime_password integer
  column invalidate_time datetime
*/
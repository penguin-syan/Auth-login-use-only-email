<?php
namespace penguin_syan\auth_login_use_only_email;

use PDO;

class ConnectDB {
    private $pdo;

    function __construct(string $conf_filepass) {
        $config = include($conf_filepass);
        $this->pdo = new PDO(
            $conf_filepass['database_dns'],
            $conf_filepass['database_username'],
            $conf_filepass['database_password']
        );        
    }

    function add_onetime_password(string $user_mail, string $onetime_password) {
        $sql_command = "insert into one_pass values('".password_hash($user_mail, PASSWORD_DEFAULT)."', $onetime_password);";
        $sql = $this->pdo->prepare($sql_command);
        $sql->execute();
    }

}

/*
テーブル構造
TABLENAME one_pass
  column hash_mail string 255
  column onetime_password integer
*/
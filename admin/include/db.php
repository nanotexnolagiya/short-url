<?php 
require('config.php');
class DB extends PDO

{

    public function last_row_count()

    {

        return $this->query("SELECT FOUND_ROWS()")->fetchColumn();

    }

}

try{

        $db = new DB("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASS);

        $db->exec("SET NAMES 'utf8'");

    }catch(PDOException $e){

        die('Подключение не удалось: ' . $e->getMessage());

    }
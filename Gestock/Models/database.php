<?php
class DB{
    public static function connect(){
        $host     = getenv('MYSQL_SERVER');
        $db       = getenv('MYSQL_DATABASE');
        $user     = getenv('MYSQL_USER'); 
        $password = getenv('MYSQL_USER_PASSWORD');
        $charset  = getenv('MYSQL_CHARSET');
        try{
            
            $connection = "mysql:host=" . $host . ";dbname=" . $db . ";charset=" . $charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($connection, $user, $password, $options);
            return $pdo;

        }catch(PDOException $e){
            print_r('Error connection: ' . $e->getMessage());
        }   
    }
}
?>
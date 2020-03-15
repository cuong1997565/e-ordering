<?php
$connected = false;
$count = 10;
while(!$connected || $count < 10) {
    try{
        echo $count;
        $count++;
        $dbh = new pdo(
            'mysql:host=mysql:3306;dbname=e_ordering', 'root', 'admin',
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
        $connected = true;
        echo "Connected to MySQL \n";
    }
    catch(PDOException $ex){
        echo "Count not connect to MYSQL";
        error_log("Could not connect to MySQL");
        error_log($ex->getMessage());
        error_log("Waiting for MySQL Connection.");
        sleep(1);
    }
}
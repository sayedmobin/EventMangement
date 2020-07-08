<?php 
$_SERVER['DB_SERVER']= 'localhost';
$_SERVER['DB'] = 'sayed';
$_SERVER['DB_USER'] = 'root';
$_SERVER['DB_PASSWORD'] ='';
// var_dump($_SERVER);
/*Handles DB interactions using a PDO driver. 
    This class is extended for specific means by other pages but provides the basics needed across all pages*/
class PDODB {
    protected $dbh;

    function __construct() {
        try {
            $this->dbh = new PDO("mysql:host={$_SERVER['DB_SERVER']};dbname={$_SERVER['DB']}",
                $_SERVER['DB_USER'], $_SERVER['DB_PASSWORD']);

            //change error reporting
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $ex) {
            die("There was a problem");
        }
    }
}
?>
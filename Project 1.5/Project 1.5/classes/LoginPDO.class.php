<?php 
    /*Handles DB interactions for the login page*/

    require_once "PDODB.class.php";

    class LoginPDO extends PDODB {

        function authenticateLogin($name, $pw) {
            try {
                $encPW = hash("sha256", $pw);
                $stmt = $this->dbh->prepare("SELECT * FROM attendee WHERE name = :name");
                $stmt->execute(array("name"=>strtolower($name)));
                $stmt->setFetchMode(PDO::FETCH_ASSOC);

                $user = $stmt->fetchAll();
                $userPw = $user[0]["password"];
                if($userPw === $encPW) {
                   return $data = ["authenticated" => true, "userID" => $user[0]["idattendee"]];
                }
                return $data = ["authenticated" => false, "userID" => null];

            } catch(PDOException $ex) {
                echo $ex;
                die("There was a problem");
            }
        }

        
    }
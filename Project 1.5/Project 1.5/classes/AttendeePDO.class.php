<?php 
    /*Handles DB interactions for the attendees*/

    require_once "PDODB.class.php";
    require_once "Attendee.class.php";

    class AttendeePDO extends PDODB {

        function getCurrentUser($userID) {
            try{
            
                $stmt = $this->dbh->prepare("SELECT * FROM attendee WHERE idattendee = :id");
                $stmt->execute(array("id"=>$userID));
                $stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");

                return $stmt->fetch();//get first row
            }
            catch(PDOException $ex) {
                die("There was a problem");
            }
        }

        function insertAttendee($name, $password, $role) {
            try {
                $stmt = $this->dbh->prepare("INSERT INTO attendee (name, password, role) VALUES (:name, :password, :role)");
                $stmt->execute(array("name"=>$name, "password"=>$password, "role"=>$role));

                return $stmt->rowCount();//get first row
            }
            catch(PDOException $ex) {
                die("There was a problem");
            }
        }

        function updateAttendee($id,$name, $password, $role) {
            try {
                $stmt = $this->dbh->prepare("UPDATE attendee SET name=:name, password=:password, role=:role WHERE idattendee=:id");
                $stmt->execute(array("name"=>$name, "password"=>$password, "role"=>$role, "id"=>$id));

                return $stmt->rowCount();//get first row
            }
            catch(PDOException $ex) {
                die("There was a problem");
            }
        }

        function deleteAttendee($id) {
            try {
                $stmt = $this->dbh->prepare("DELETE FROM attendee WHERE idattendee=:id");
                $stmt->execute(array("id"=>$id));

                return $stmt->rowCount();//get first row
            }
            catch(PDOException $ex) {
                die("There was a problem");
            }
        }

        function getAllEventsByID($userID) {
            require_once "Event.class.php";
            try {
                $stmt = $this->dbh->prepare("SELECT * FROM event JOIN attendee_event ON event.idevent = attendee_event.event WHERE attendee_event.attendee = :id");
                $stmt->execute(array("id"=>$userID));
                $stmt->setFetchMode(PDO::FETCH_CLASS, "Event");

                return $stmt->fetchAll();//get first row
            }
            catch(PDOException $ex) {
                die("There was a problem");
            }
        }

        function getAllSessionsByID($userID) {
            require_once "Session.class.php";
            try {
                $stmt = $this->dbh->prepare("SELECT * FROM session JOIN attendee_session ON session.idsession = attendee_session.session WHERE attendee_session.attendee = :id");
                $stmt->execute(array("id"=>$userID));
                $stmt->setFetchMode(PDO::FETCH_CLASS, "Session");

                return $stmt->fetchAll();//get first row
            }
            catch(PDOException $ex) {
                die("There was a problem");
            }
        }

        function unregisterEventByUserID($eventID, $attendeeID) {
            try {
                $stmt = $this->dbh->prepare("DELETE FROM attendee_event WHERE event = :eventID AND attendee = :attendeeID");
                $stmt->execute(array("eventID"=>$eventID, "attendeeID"=>$attendeeID));
                return $stmt->rowCount();//get first row
            }
            catch(PDOException $ex) {
                var_dump($ex);
                die("There was a problem");
            }
        }

        function unregisterSessionByUserID($sessionID, $attendeeID) {
            try {
                $stmt = $this->dbh->prepare("DELETE FROM attendee_session WHERE session = :sessionID AND attendee = :attendeeID");
                $stmt->execute(array("sessionID"=>$sessionID, "attendeeID"=>$attendeeID));

                return $stmt->rowCount();//get first row
            }
            catch(PDOException $ex) {
                die("There was a problem");
            }
        }

        function registerSessionByUserID($sessionID, $attendeeID) {
            try {
                $stmt = $this->dbh->prepare("INSERT INTO attendee_session VALUES (:sessionID, :attendeeID)");
                $stmt->execute(array("sessionID"=>$sessionID, "attendeeID"=>$attendeeID));

                return $stmt->rowCount();//get first row
            }
            catch(PDOException $ex) {
                die("There was a problem");
            }
        }

        function registerEventByUserID($eventID, $attendeeID) {
            try {
                $stmt = $this->dbh->prepare("INSERT INTO attendee_event VALUES (:eventID, :attendeeID, 1)");
                $stmt->execute(array("eventID"=>$eventID, "attendeeID"=>$attendeeID));

                return $stmt->rowCount();//get first row
            }
            catch(PDOException $ex) {
                //attempted to insert duplicate row
                if($ex->getCode() == 23000){
                    return 0;
                } else {
                die("There was a problem");
            }

            }
        }

        function getAllUsers() {
            try{
                /*Get all our sessions for the events*/
                $stmt = $this->dbh->query("SELECT * FROM attendee");
                $stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");
    
                /*Store all sessions*/
                $users = $stmt->fetchAll();
                return $users;
            }
            catch(PDOException $ex) {
                die("There was a problem");
            }
        }

        function getManagedEvents($userID) {
            try{
                require_once "Event.class.php";
                /*Get all our sessions for the events*/
                $stmt = $this->dbh->prepare("SELECT * FROM event JOIN manager_event ON event.idevent = manager_event.event WHERE manager = :userID");
                $stmt->execute(array("userID"=>$userID));
                $stmt->setFetchMode(PDO::FETCH_CLASS, "Event");
    
                /*Store all sessions*/
                $events = $stmt->fetchAll();
                return $events;
            }
            catch(PDOException $ex) {
                die("There was a problem");
            }
        }
        
    }
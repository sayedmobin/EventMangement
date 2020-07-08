<?php 
/*Handles DB interactions using a PDO driver*/
require_once "PDODB.class.php";
require_once "Event.class.php";
require_once "Session.class.php";

class EventsPDO extends PDODB {

    function getAllEvents() {
        try{
            $stmt = $this->dbh->query("SELECT * FROM event");
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Event");

            /*Store all our events */
            $events = $stmt->fetchAll();
            return $events;
        }
        catch(PDOException $ex) {
            die("There was a problem");
        }

    }

    function getEventName($eventID) {
        try{
            $stmt = $this->dbh->prepare("SELECT name FROM event WHERE idevent = :id");
            $stmt->execute(array("id"=>$eventID));
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $eventName = $stmt->fetch();//get first row
            return $eventName;
        }
        catch(PDOException $ex) {
            die("There was a problem");
        }
    }

    function getEventByID($eventID) {
        try{
            $stmt = $this->dbh->prepare("SELECT * FROM event WHERE idevent = :id");
            $stmt->execute(array("id"=>$eventID));
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Event");

            $event = $stmt->fetch();//get first row
            return $event;
        }
        catch(PDOException $ex) {
            die("There was a problem");
        }
    }

    function getAllAttendeesForEvent($eventID) {
        try{
            $stmt = $this->dbh->prepare("SELECT * FROM attendee JOIN attendee_event ON attendee.idattendee = attendee_event.attendee WHERE attendee_event.event = :id");
            $stmt->execute(array("id"=>$eventID));
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");

            $event = $stmt->fetchAll();//get first row
            return $event;
        }
        catch(PDOException $ex) {
            die("There was a problem");
        }
    }

    function getAllEventManagers() {
        try{
            $stmt = $this->dbh->query("SELECT * FROM attendee WHERE role < 3");
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");

            $event = $stmt->fetchAll();//get first row
            return $event;
        }
        catch(PDOException $ex) {
            die("There was a problem");
        }
    }

    function updateManager($eventID, $managerID) {
        try{
            $stmt = $this->dbh->prepare("UPDATE manager_event SET manager=:manager WHERE event = :event");
            $stmt->execute(array("manager"=>$managerID, "event"=>$eventID));
            $rows = $stmt->rowCount();//get rows affected
            return $rows;
        }
        catch(PDOException $ex) {
            die("There was a problem");
        }
    }

    function insertEvent($name, $venue, $start, $end, $capacity) {
        try{
            $stmt = $this->dbh->prepare("INSERT INTO event (name, datestart, dateend, numberallowed, venue) VALUES (:name, :start, :end, :capacity, :venue)");
            $stmt->execute(array("name"=>$name, "start"=>$start, "end"=>$end, "capacity"=>$capacity, "venue"=>$venue));
            $id = $this->dbh->lastInsertId();//get rows affected
            return $id;
        }
        catch(PDOException $ex) {
            die("There was a problem");
        }
    }

    function updateEvent($id,$name, $venue, $start, $end, $capacity) {
        try{
            $stmt = $this->dbh->prepare("UPDATE event SET name=:name, venue=:venue, datestart=:start,dateend=:end,numberallowed=:capacity WHERE idevent = :id");
            $stmt->execute(array("id"=>$id,"name"=>$name, "start"=>$start, "end"=>$end, "capacity"=>$capacity, "venue"=>$venue));
            $rows = $stmt->rowCount();//get rows affected
            return $rows;
        }
        catch(PDOException $ex) {
            die("There was a problem");
        }
    }

    function insertManager($eventID, $managerID) {
        try{
            $stmt = $this->dbh->prepare("INSERT INTO manager_event VALUES (:event, :manager)");
            $stmt->execute(array("event"=>$eventID, "manager"=>$managerID));
            $rows = $stmt->rowCount();//get rows affected
            return $rows;
        }
        catch(PDOException $ex) {
            die("There was a problem");
        }
    }

    function deleteEvent($eventID) {
        try{
            $stmt = $this->dbh->prepare("DELETE FROM event WHERE idevent = :event");
            $stmt->execute(array("event"=>$eventID));
            $rows = $stmt->rowCount();//get rows affected
            return $rows;
        }
        catch(PDOException $ex) {
            die("There was a problem");
        }
    }

    function deleteManagerEvent($eventID) {
        try{
            $stmt = $this->dbh->prepare("DELETE FROM manager_event WHERE event = :event");
            $stmt->execute(array("event"=>$eventID));
            $rows = $stmt->rowCount();//get rows affected
            return $rows;
        }
        catch(PDOException $ex) {
            die("There was a problem");
        }
    }

    function deleteAllSessionsForEvent($eventID) {
        try{
            $stmt = $this->dbh->prepare("DELETE FROM session WHERE event = :event");
            $stmt->execute(array("event"=>$eventID));
            $rows = $stmt->rowCount();//get rows affected
            return $rows;
        }
        catch(PDOException $ex) {
            var_dump($ex);
            die("There was a problem");
        }
    }

    function deleteFromAttendeeEvent($eventID) {
        try{
            $stmt = $this->dbh->prepare("DELETE FROM attendee_event WHERE event = :event");
            $stmt->execute(array("event"=>$eventID));
            $rows = $stmt->rowCount();//get rows affected
            return $rows;
        }
        catch(PDOException $ex) {
            die("There was a problem");
        }
    }

    function getAttendeeCount($eventID) {
        try{
            $stmt = $this->dbh->prepare("SELECT COUNT(attendee) FROM attendee_event WHERE event = :event");
            $stmt->execute(array("event"=>$eventID));
            $num = $stmt->fetch();//get rows affected
            return $num;
        }
        catch(PDOException $ex) {
            die("There was a problem");
        }
    }

}
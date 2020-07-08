<?php
require_once "PDODB.class.php";

    class VenuePDO extends PDODB {

        function getVenueName($venueID) {
            try{
                /*Get all our sessions for the events*/
                $stmt = $this->dbh->prepare("SELECT name FROM venue WHERE idvenue = :id");
                $stmt->execute(array("id"=>$venueID));
    
                /*Store all sessions*/
                $venueName = $stmt->fetch();
                return $venueName[0];
            }
            catch(PDOException $ex) {
                die("There was a problem");
            }
        }

        function getAllVenues() {
            try{
                /*Get all our sessions for the events*/
                $stmt = $this->dbh->query("SELECT * FROM venue");
                $stmt->setFetchMode(PDO::FETCH_CLASS, "Venue");

                return $stmt->fetchAll();
            }
            catch(PDOException $ex) {
                die("There was a problem");
            }
        }

        //add a venue
        function addVenue($name, $capacity) {
            try{
                
                $stmt = $this->dbh->prepare("INSERT INTO venue (name, capacity) VALUES (:name, :capacity)");
                $stmt->execute(array("name"=>$name, "capacity"=>$capacity));
    
                $rows = $stmt->rowCount();
                return $rows;
            }
            catch(PDOException $ex) {
                die("There was a problem");
            }
        }
         
        //update a venue
        function updateVenue($id, $name, $capacity) {
            try{
                
                $stmt = $this->dbh->prepare("UPDATE venue SET name=:name, capacity=:capacity WHERE idvenue=:id");
                $stmt->execute(array("id"=>$id, "name"=>$name, "capacity"=>$capacity));
    
                $rows = $stmt->rowCount();
                return $rows;
            }
            catch(PDOException $ex) {
                die("There was a problem");
            }
        }

        //delete a venue
        function deleteVenue($id) {
            try{
                
                $stmt = $this->dbh->prepare("DELETE FROM venue WHERE idvenue=:id");
                $stmt->execute(array("id"=>$id));
    
                $rows = $stmt->rowCount();
                return $rows;
            }
            catch(PDOException $ex) {
                die("There was a problem");
            }
        }

    }
?>
<?php
    require_once "AttendeePDO.class.php";
    class Attendee {
        
        private $idattendee;
        private $name;
        private $password;
        private $role;

        public static function newAttendee($name, $pw, $attendeeRole, $id=null) {
            $attendee = new self;
            $attendee->idattendee = $id;
            $attendee->name = $name;
            $attendee->password = $pw;
            $attendee->role = $attendeeRole;
            return $attendee;
        }

        function getID() {
            return $this->idattendee;
        }

        function getName() {
            return $this->name;
        }

        function getPassword() {
            return $this->password;
        }

        function getRole() {
            return $this->role;
        }

        function Post() {
            $db = new AttendeePDO();
            return $db->insertAttendee($this->name, $this->password, $this->role);
        }

        function Put() {
            $db = new AttendeePDO();
            return $db->updateAttendee($this->idattendee,$this->name, $this->password, $this->role);
        }

        public static function delete($id) {
            $db = new AttendeePDO();
            return $db->deleteAttendee($id);
        }

        function getAsTableRow() {
            $eventCount = count($this->getAlleventsForUser());
            $sessionCount = count($this->getAllSessionsForUser());
            $roleName = $this->getRoleByName();
            return "<tr>
            <td>{$this->idattendee}</td>
            <td>{$this->name}</td>
            <td>$roleName</td>
            <td>$eventCount</td>
            <td>$sessionCount</td>
            </tr>";
        }

        function getAllEventsForUser($attendeeID=null) {
            $db = new AttendeePDO();
            $attendeeID = $attendeeID === null ? $this->idattendee : $attendeeID;
            return $db->getAllEventsByID($attendeeID);
        }

        function getAllSessionsForUser($attendeeID=null) {
            $db = new AttendeePDO();
            $attendeeID = $attendeeID === null ? $this->idattendee : $attendeeID;
            return $db->getAllSessionsbyID($attendeeID);
        }

        function unregisterEvent($eventID, $attendeeID=null) {
            $db = new AttendeePDO();
            $attendeeID = $attendeeID === null ? $this->idattendee : $attendeeID;
            return $db->unregisterEventByUserID($eventID, $attendeeID);
        }

        function unregisterSession($sessionID, $attendeeID=null) {
            $db = new AttendeePDO();
            $attendeeID = $attendeeID === null ? $this->idattendee : $attendeeID;
            return $db->unregisterSessionByUserID($sessionID, $attendeeID);
        }

        function registerSession($sessionID, $attendeeID=null) {
            $db = new AttendeePDO();
            $attendeeID = $attendeeID === null ? $this->idattendee : $attendeeID;
            return $db->registerSessionByUserID($sessionID, $attendeeID);
        }

        function registerEvent($eventID, $attendeeID=null) {
            $db = new AttendeePDO();
            $attendeeID = $attendeeID === null ? $this->idattendee : $attendeeID;//work around for default paramter since you can't do $this->idattendee in the args
            return $db->registerEventByUserID($eventID, $attendeeID);
        }

        public static function getAllUsers() {
            $db = new AttendeePDO();
            return $db->getAllUsers();
        }

        function getManagedEvents($attendeeID=null) {
            $db = new AttendeePDO();
            $attendeeID = $attendeeID === null ? $this->idattendee : $attendeeID;
            return $db->getManagedEvents($attendeeID);
        }

        function getRoleByName() {
            switch($this->getRole()) {
                
                case 1:
                return "Admin";
                break;

                case 2:
                return "Event Manager";
                break;

                case 3:
                return "Attendee";
                break;
            }
        }
    }
?>
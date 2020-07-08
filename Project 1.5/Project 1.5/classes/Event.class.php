<?php
    class Event {

        private $idevent;
        private $name;
        private $datestart;
        private $dateend;
        private $numberallowed;
        private $venue;

        public static function newEvent($name, $venue, $datestart, $dateend, $numberallowed, $id = null) {
            $event = new self;
            $event->idevent = $id;
            $event->name = $name;
            $event->venue = $venue;
            $event->datestart = $datestart;
            $event->dateend = $dateend;
            $event->numberallowed = $numberallowed;
            return $event;
        }

        function getID() {
            return $this->idevent;
        }

        function getName() {
            return $this->name;
        }

        function getStartDate() {
            return $this->datestart;
        }

        function getEndDate() {
            return $this->dateend;
        }

        function getNumberAllowed() {
            return $this->numberallowed;
        }

        function getVenue() {
            return $this->venue;
        }

        function Post() {
            require_once "EventsPDO.class.php";
            $db = new EventsPDO();
            $this->idevent = $db->insertEvent($this->name,$this->venue,$this->datestart,$this->dateend,$this->numberallowed);//insetred id is returned
        }

        function Put() {
            require_once "EventsPDO.class.php";
            $db = new EventsPDO();
            return $db->updateEvent($this->idevent,$this->name,$this->venue,$this->datestart,$this->dateend,$this->numberallowed);
        }

        function addManager($managerID) {
            require_once "EventsPDO.class.php";
            $db = new EventsPDO();
            return $db->insertManager($this->idevent,$managerID);
        }

        function getAsTableRow() {
            require_once "Venue.class.php";
            $venueName = Venue::getVenueByName($this->venue);
            return "<tr data-event='{$this->idevent}'>
            <td>{$this->idevent}</td>
            <td>{$this->name}</td>
            <td>$venueName</td>
            <td>{$this->datestart}</td>
            <td>{$this->dateend}</td>
            <td>{$this->numberallowed}</td>
            <td>{$this->getAttendeeCount()[0]}</td>
            </tr>";
        }

        function updateManager($managerID) {
            require_once "EventsPDO.class.php";
            $db = new EventsPDO();
            return $db->updateManager($this->idevent, $managerID);
        }

        function getAttendeeCount() {
            require_once "EventsPDO.class.php";
            $db = new EventsPDO();
            return $db->getAttendeeCount($this->idevent);
        }

        public static function getAllEvents() {
            require_once "EventsPDO.class.php";
            $db = new EventsPDO();
            return $db->getAllEvents();
        }

        public static function getEventByID($eventID) {
            require_once "EventsPDO.class.php";
            $db = new EventsPDO();
            return $db->getEventByID($eventID);
        }

        public static function getAllAttendeesForEvent($eventID) {
            require_once "EventsPDO.class.php";
            $db = new EventsPDO();
            return $db->getAllAttendeesForEvent($eventID);
        }

        public static function getAllEventManagers() {
            require_once "EventsPDO.class.php";
            $db = new EventsPDO();
            return $db->getAllEventManagers();
        }

        public static function deleteEvent($eventID) {
            require_once "EventsPDO.class.php";
            $db = new EventsPDO();
            return $db->deleteEvent($eventID);
        }

        public static function deleteManagerEvent($eventID) {
            require_once "EventsPDO.class.php";
            $db = new EventsPDO();
            return $db->deleteManagerEvent($eventID);
        }

        public static function deleteAllSessionsForEvent($eventID) {
            require_once "EventsPDO.class.php";
            $db = new EventsPDO();
            return $db->deleteAllSessionsForEvent($eventID);
        }

        public static function deleteFromAttendeeEvent($eventID) {
            require_once "EventsPDO.class.php";
            $db = new EventsPDO();
            return $db->deleteFromAttendeeEvent($eventID);
        }
        
    }

?>
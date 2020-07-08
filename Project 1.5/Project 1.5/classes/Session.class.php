<?php

    class Session {
        private $idsession;
        private $name;
        private $numberallowed;
        private $event;
        private $startdate;
        private $enddate;

        public static function newSession($sessionName, $allowed, $event, $start, $end, $id=null) {
            $session = new self;
            $session->idsession = $id;
            $session->name = $sessionName;
            $session->numberallowed = $allowed;
            $session->event = $event;
            $session->startdate = $start;
            $session->enddate = $end;

            return $session;
        }

        function getID() {
            return $this->idsession;
        }

        function getName() {
            return $this->name;
        }

        function getStartDate() {
            return $this->startdate;
        }

        function getEndDate() {
            return $this->enddate;
        }

        function getNumberAllowed() {
            return $this->numberallowed;
        }

        function getEvent() {
            return $this->event;
        }

        function getEventName() {
            require_once "EventsPDO.class.php";
            $db = new EventsPDO();
            return $db->getEventName($this->event)["name"];
        }

        function getSessionAsRow() {
            return "<tr data-session='{$this->idsession}'>
            <td>{$this->idsession}</td>
            <td>{$this->name}</td>
            <td>{$this->getEventName()}</td>
            <td>{$this->startdate}</td>
            <td>{$this->enddate}</td>
            <td>{$this->numberallowed}</td>
            <td>{$this->getAttendeeCount()[0]}</td>
            </tr>";
        }

        function Post() {
            require_once "SessionPDO.class.php";
            $db = new SessionPDO();
            return $db->insertSession($this->name, $this->event, $this->startdate, $this->enddate, $this->numberallowed);
        }

        function Put() {
            require_once "SessionPDO.class.php";
            $db = new SessionPDO();
            return $db->updateSession($this->idsession, $this->name, $this->event, $this->startdate, $this->enddate, $this->numberallowed);
        }

        function getAttendeeCount() {
            require_once "SessionPDO.class.php";
            $db = new SessionPDO();
            return $db->getAttendeeCount($this->idsession);
        }

        public static function getAllSessions() {
            require "SessionPDO.class.php";
            $db = new SessionPDO();
            return $db->getAllSessions();
        }

        public static function getSessionByID($sessionID) {
            require "SessionPDO.class.php";
            $db = new SessionPDO();
            return $db->getSessionByID($sessionID);
        }

        public static function getSessionsByEventID($eventID) {
            require_once "SessionPDO.class.php";
            $db = new SessionPDO();
            return $db->getSessionsByEventID($eventID);
        }

        public static function deleteSession($sessionID) {
            require_once "SessionPDO.class.php";
            $db = new SessionPDO();
            return $db->deleteSession($sessionID);
        }

        public static function deleteSessionAttendee($sessionID) {
            require_once "SessionPDO.class.php";
            $db = new SessionPDO();
            return $db->deleteSessionAttendee($sessionID);
        }

    }

?>
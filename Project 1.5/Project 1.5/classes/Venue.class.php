<?php
    class Venue {

        private $idvenue;
        private $name;
        private $capacity;

        static function newVenue($name, $capacity, $id=null) {
            $venue = new self;
            $venue->idvenue = $id;
            $venue->name = $name;
            $venue->capacity = $capacity;
            return $venue;
        }

        static function getVenueByName($venueID) {
            require_once "VenuePDO.class.php";
            $venueDB = new VenuePDO();
            return $venueDB->getVenueName($venueID);
        }

        static function getAllVenues() {
            require_once "VenuePDO.class.php";
            $venueDB = new VenuePDO();
            return $venueDB->getAllVenues();
        }

        public function getName() {
            return $this->name;
        }

        public function getID() {
            return $this->idvenue;
        }

        public function getCapacity() {
            return $this->capacity;
        }

        public function getAsTableRow() {
            return "<tr>
            <td>{$this->idvenue}</td>
            <td>{$this->name}</td>
            <td>{$this->capacity}</td>
            </tr>";
        }

        function Post() {
            require_once "VenuePDO.class.php";
            $venueDB = new VenuePDO();
            return $venueDB->addVenue($this->name, $this->capacity);
        }

        function Put() {
            require_once "VenuePDO.class.php";
            $venueDB = new VenuePDO();
            return $venueDB->updateVenue($this->idvenue, $this->name, $this->capacity);
        }

        static function delete($id) {
            require_once "VenuePDO.class.php";
            $venueDB = new VenuePDO();
            return $venueDB->deleteVenue($id);
        }

    }

?>
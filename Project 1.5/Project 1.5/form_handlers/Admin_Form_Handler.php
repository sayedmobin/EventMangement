<?php
  
    require_once "./classes/Attendee.class.php";

    if(isset($_GET["feature"]) && !empty($_GET["feature"])) {
        switch ($_GET["feature"]) {
            
            case "event":
            eventSubmissionHandler();
            break;

            case "session":
            sessionSubmissionHandler();
            break;

            case "user":
            userSubmissionHandler();
            break;

            case "venue":
            venueSubmissionHandler();
            break;

        }
    }

    /*Handle event form submissions*/
    function eventSubmissionHandler() {
        if(isset($_POST["action"]) && !empty($_POST["action"])) {
            switch($_POST["action"]) {

                case "add":
                addEvent();
                break;

                case "update":
                updateEvent();
                break;

                case "delete":
                deleteEvent();
                break;

                case "add-user":
                addEventUser();
                break;

                case "remove-user":
                removeEventUser();
                break;

            }
        }
    }

    /*Handle session form submissions*/
    function sessionSubmissionHandler() {
        if(isset($_POST["action"]) && !empty($_POST["action"])) {
            switch($_POST["action"]) {

                case "add":
                addSession();
                break;

                case "update":
                updateSession();
                break;

                case "delete":
                deleteSession();
                break;

                case "add-user":
                addSessionUser();
                break;

                case "remove-user":
                removeSessionUser();
                break;

            }
        }
    }

    /*User crud handling */
    function userSubmissionHandler() {
        if(isset($_POST["action"]) && !empty($_POST["action"])) {
            switch($_POST["action"]) {

                case "add":
                addUser();
                break;

                case "update":
                updateUser();
                break;

                case "delete":
                deleteUser();
                break;

            }
        }
    }

    /*Venue crud handling */
    function venueSubmissionHandler() {
        if(isset($_POST["action"]) && !empty($_POST["action"])) {
            switch($_POST["action"]) {

                case "add":
                addVenue();
                break;

                case "update":
                updateVenue();
                break;

                case "delete":
                deleteVenue();
                break;

            }
        }
    }

    function addEvent() {
        //make sure all needed variables are set
        if(checkIfAllSet([$_POST["eventName-add"],$_POST["venue-add"],$_POST["eventStartDate-add"],$_POST["eventEndDate-add"],$_POST["eventCapacity-add"]])) {
            $managerInsert = 0;
            //validate and sanitize all data
            if(alphaNumericSpace($_POST["eventName-add"]) && alphaNumericSpace($_POST["venue-add"]) && dateYYYYMMDD($_POST["eventStartDate-add"])
             && dateYYYYMMDD($_POST["eventEndDate-add"]) && numeric($_POST["eventCapacity-add"])) {
                $name = sanitizeInputData($_POST["eventName-add"]);
                $venue = sanitizeInputData($_POST["venue-add"]);
                $start = sanitizeInputData($_POST["eventStartDate-add"]);
                $end = sanitizeInputData($_POST["eventEndDate-add"]);
                $cap = sanitizeInputData($_POST["eventCapacity-add"]);

    
            $event = Event::newEvent($name,$venue,$start,$end,$cap);
            $event->Post();
     
            if(isset($_POST["manager-add"]) && !empty($_POST["manager-add"]) && $event->getID() && numeric($_POST["manager-add"])) {
                $manager = sanitizeInputData($_POST["manager-add"]);
                $managerInsert = $event->addManager($manager);
            }
            else if($event->getID() != null){
                $managerInsert = $event->addManager($_SESSION["currentUser"]->getID());
            }
        }

            return $managerInsert;
        } else {
            return 0;//no rows insterted
        }
    }

    function updateEvent() {
        if(checkIfAllSet([$_POST["event-update"],$_POST["eventName-update"],$_POST["venue-update"],$_POST["eventStartDate-update"],$_POST["eventEndDate-update"],$_POST["eventCapacity-update"]])) {
            //validate and sanitize
            if(numeric($event = $_POST["event-update"]) && alphabeticSpace($_POST["eventName-update"]) && numeric($_POST["venue-update"]) &&
                dateYYYYMMDD($_POST["eventStartDate-update"]) && dateYYYYMMDD($_POST["eventEndDate-update"]) && numeric($_POST["eventCapacity-update"])) {
                $eventID = sanitizeInputData($_POST["event-update"]);
                $eventName = sanitizeInputData($_POST["eventName-update"]);
                $venue = sanitizeInputData($_POST["venue-update"]);
                $start = sanitizeInputData($_POST["eventStartDate-update"]);
                $end = sanitizeInputData($_POST["eventEndDate-update"]);
                $capacity = sanitizeInputData($_POST["eventCapacity-update"]);

                $event = Event::newEvent($eventName,$venue,$start,$end,$capacity,$eventID);
                $row = $event->Put();

                /*If admin chooses event manager */
                if(isset($_POST["manager-update"]) && !empty($_POST["manager-update"]) && numeric($_POST["manager-update"])) {
                    $manager = sanitizeInputData($_POST["manager-update"]);
                    $event->updateManager($manager);
                }
                return $row;
            }
        } else {
            return 0;
        }
    }

    /*Delete an event - will also delete traces of the event from all event tables in the DB*/
    function deleteEvent() {
        if(checkIfAllSet([$_POST["event-delete"]])) {
            if(numeric($_POST["event-delete"])) {
                $eventID = sanitizeInputData($_POST["event-delete"]);

                $rowsAffected = Event::deleteEvent($eventID);

                if($rowsAffected > 0) {

                    Event::deleteManagerEvent($eventID);
                    Event::deleteFromAttendeeEvent($eventID);
                    $sessionsToRemove = Session::getSessionsByEventID($eventID);

                    foreach($sessionsToRemove as $session) {
                        Session::deleteSessionAttendee($session->getID());
                    }

                    Event::deleteAllSessionsForEvent($eventID);

                }

                return $rowsAffected;
            }
        } else {
            return 0;
        }
    }

    /*Register a user from an event*/
    function addEventUser() {
        if(checkIfAllSet([$_POST["selectedUser"], $_POST["event-user"]])) {
            if(numeric($_POST["selectedUser"]) && numeric($_POST["event-user"])) {
                $user = sanitizeInputData($_POST["selectedUser"]);
                $event = sanitizeInputData($_POST["event-user"]);

                $rowsAffected = (new Attendee)->registerEvent($event,$user);
            }
        }
    }

    /*Unregister a user from an event*/
    function removeEventUser() {
        if(checkIfAllSet([$_POST["selectedUser"], $_POST["event-user"]])) {
            if(numeric($_POST["selectedUser"]) && numeric($_POST["event-user"])) {
                $user = sanitizeInputData($_POST["selectedUser"]);
                $event = sanitizeInputData($_POST["event-user"]);

                $rowsAffected = (new Attendee)->unregisterEvent($event,$user);
            }
        }
    }

    function addSession() {
        //validation and sanitization
        if(checkIfAllSet([$_POST["sessionName-add"],$_POST["event-session-1"],$_POST["sessionStartDate-add"],$_POST["sessionEndDate-add"],$_POST["sessionCapacity-add"]])) {
            if(alphaNumericSpace($_POST["sessionName-add"] && numeric($_POST["event-session-1"]) && dateYYYYMMDD($_POST["sessionStartDate-add"]) && dateYYYYMMDD($_POST["sessionEndDate-add"])&& numeric($_POST["sessionCapacity-add"]))) {
                $name = sanitizeInputData($_POST["sessionName-add"]);
                $event = sanitizeInputData($_POST["event-session-1"]);
                $start = sanitizeInputData($_POST["sessionStartDate-add"]);
                $end = sanitizeInputData($_POST["sessionEndDate-add"]);
                $capacity = sanitizeInputData($_POST["sessionCapacity-add"]);

                $session = Session::newSession($name, $capacity, $event, $start, $end);
                $rowsAffected = $session->Post();
                return $rowsAffected;
            }
        }
    }

    /*Update session - All fields need to be filled out to work*/
    function updateSession() {
        if(checkIfAllSet([$_POST["session-update"],$_POST["sessionName-update"],$_POST["event-session-2"],$_POST["sessionStartDate-update"],$_POST["sessionEndDate-update"],$_POST["sessionCapacity-update"]])) {
            if(numeric($_POST["session-update"]) && alphaNumericSpace($_POST["sessionName-update"] && numeric($_POST["event-session-2"]) && dateYYYYMMDD($_POST["sessionStartDate-update"]) && dateYYYYMMDD($_POST["sessionEndDate-update"]) && numeric($_POST["sessionCapacity-update"]))) {
                $sessionID = sanitizeInputData($_POST["session-update"]);
                $name = sanitizeInputData($_POST["sessionName-update"]);
                $event = sanitizeInputData($_POST["event-session-2"]);
                $start = sanitizeInputData($_POST["sessionStartDate-update"]);
                $end = sanitizeInputData($_POST["sessionEndDate-update"]);
                $capacity = sanitizeInputData($_POST["sessionCapacity-update"]);
                
                $session = Session::newSession($name, $capacity, $event, $start, $end, $sessionID);
                $rowsAffected = $session->Put();
                return $rowsAffected;
            }
        }
    }

    /*Delete Session */
    function deleteSession() {
        if(checkIfAllSet([$_POST["session-delete"]])) {
            if(numeric($_POST["session-delete"])) {
                $sessionID = sanitizeInputData($_POST["session-delete"]);

                $rowsAffected = Session::deleteSession($sessionID);
                return $rowsAffected;
            }
        }
    }

    /*Add user to session */
    function addSessionUser() {
        if(checkIfAllSet([$_POST["selectedUser-session"], $_POST["session-user"]])) {
            if(numeric($_POST["selectedUser-session"]) && numeric($_POST["session-user"])) {
                $user = sanitizeInputData($_POST["selectedUser-session"]);
                $session = sanitizeInputData($_POST["session-user"]);

                $rowsAffected = (new Attendee)->registerSession($session,$user);
            }
        }
    }

    /*Remove user from session */
    function removeSessionUser() {
        if(checkIfAllSet([$_POST["selectedUser-session"], $_POST["session-user"]])) {
            if(numeric($_POST["selectedUser-session"]) && numeric($_POST["session-user"])) {
                $user = sanitizeInputData($_POST["selectedUser-session"]);
                $session = sanitizeInputData($_POST["session-user"]);

                $rowsAffected = (new Attendee)->unregisterSession($session,$user);
            }
        }
    }

    /*USERS*/
    //add
    function addUser() {
        if(checkIfAllSet([$_POST["userName-add"],$_POST["userPassword-add"],$_POST["user-role-add"]])) {
            if(alphabeticSpace($_POST["userName-add"]) && alphabeticNumericPunct($_POST["userPassword-add"]) && numeric($_POST["user-role-add"])) {
                $name = sanitizeInputData($_POST["userName-add"]);
                $password = hash("sha256", sanitizeInputData($_POST["userPassword-add"]));
                $role = sanitizeInputData($_POST["user-role-add"]);

                $user = Attendee::newAttendee($name, $password, $role);
                $rowsAffected = $user->Post();
                return $rowsAffected;

            }
        }
    }

    //update
    function updateUser() {
        if(checkIfAllSet([$_POST["noAdminUsers-update"],$_POST["userName-update"],$_POST["userPassword-update"],$_POST["user-role-update"]])) {
            if(numeric($_POST["noAdminUsers-update"]) && alphabeticSpace($_POST["userName-update"]) && alphabeticNumericPunct($_POST["userPassword-update"]) && numeric($_POST["user-role-update"])) {
                $userID = sanitizeInputData($_POST["noAdminUsers-update"]);
                $name = sanitizeInputData($_POST["userName-update"]);
                $password = hash("sha256", sanitizeInputData($_POST["userPassword-update"]));
                $role = sanitizeInputData($_POST["user-role-update"]);

                $user = Attendee::newAttendee($name, $password, $role, $userID);
                $rowsAffected = $user->Put();
                return $rowsAffected;
            }
        }
    }

    //delete
    function deleteUser() {
        if(checkIfAllSet([$_POST["noAdminUsers-delete"]])) {
            if(numeric($_POST["noAdminUsers-delete"])) {
                $userID = sanitizeInputData($_POST["noAdminUsers-delete"]);

                $rowsAffected = Attendee::delete($userID);
                if($rowsAffected > 0) {
                    $events = (new Attendee)->getAllEventsForUser($userID);
                    $sessions = (new Attendee)->getAllSessionsForUser($userID);
                    $managedEvents = (new Attendee)->getManagedEvents($userID);

                    //remove from events
                    foreach($events as $event) {
                        (new Attendee)->unregisterEvent($event->getID(), $userID);
                    }

                    //remove from sessions
                    foreach($sessions as $session) {
                        (new Attendee)->unregisterSession($session->getID(), $userID);
                    }

                    //remove from managers events table
                    foreach($managedEvents as $event) {
                        Event::deleteManagerEvent($event->getID());
                    }
                }
                return $rowsAffected;
            }
        }
    }

    /*VENUES*/
    function addVenue() {
        if(checkIfAllSet([$_POST["venueName-add"],$_POST["venueCapacity-add"]])) {
            if(alphaNumericSpace($_POST["venueName-add"]) && numeric($_POST["venueCapacity-add"])) {
                $name = sanitizeInputData($_POST["venueName-add"]);
                $capacity = sanitizeInputData($_POST["venueCapacity-add"]);

                $venue = Venue::newVenue($name, $capacity);
                $rowsAffected = $venue->Post();
                return $rowsAffected;
            }
        }
    }

    function updateVenue() {
        if(checkIfAllSet([$_POST["venue-updateVenue"],$_POST["venueName-update"],$_POST["venueCapacity-update"]])) {
            if(numeric($_POST["venue-updateVenue"]) && alphaNumericSpace($_POST["venueName-update"]) && numeric($_POST["venueCapacity-update"])) {
                $name = sanitizeInputData($_POST["venueName-update"]);
                $capacity = sanitizeInputData($_POST["venueCapacity-update"]);
                $venueID = sanitizeInputData($_POST["venue-updateVenue"]);

                $venue = Venue::newVenue($name, $capacity, $venueID);
                $rowsAffected = $venue->Put();
                return $rowsAffected;
            }
        }
    }

    function deleteVenue() {
        if(checkIfAllSet([$_POST["venue-deleteVenue"]])) {
            if(numeric($_POST["venue-deleteVenue"])) {
                $venueID = sanitizeInputData($_POST["venue-deleteVenue"]);

                $rowsAffected = Venue::delete($venueID);
                return $rowsAffected;
            }
        }
    }

    function checkIfAllSet($args) {
        $set = true;
        foreach($args as $input) {
            if(!(isset($input) && !empty($input))) {
                $set = false;
            }
        }

        return $set;
    }


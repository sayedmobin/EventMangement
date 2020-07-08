<?php

    require_once "../classes/Attendee.class.php";
    require_once "../utilities.inc.php";
    /*Start the session */
    session_name("events");
    session_start();

 
    if(!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] || !isset($_SESSION["currentUser"])) {
       echo header("Location: Login.php");
    }

    if(isset($_POST["unregister-event"])) {
        /*Sanitize and validate the data*/
        $sanitizedData = sanitizeInputData($_POST["unregister-event"][0]["eventID"]);
        $rowsAffected = 0;
        if(numeric($sanitizedData)) { 
            $rowsAffected = $_SESSION["currentUser"]->unregisterEvent($sanitizedData);//return number of rows affected
        }
        $json["rowsAffected"] = $rowsAffected;

        echo json_encode($json);
    }

    if(isset($_POST["add-event"])) {
        require_once "../classes/Event.class.php";
        /*sanitize and validate data*/
        $sanitizedData = sanitizeInputData($_POST["add-event"][0]["eventID"]);
        $rowsAffected = 0;
        if(numeric($sanitizedData)) { 
            $rowsAffected = $_SESSION["currentUser"]->registerEvent($sanitizedData);
        } 
        $json["rowsAffected"] = $rowsAffected;
        /*If inserted properly return the new table row with the number of rows affected*/
        if($rowsAffected > 0) {
            /*Get event formatted as table row*/
            $newEvent = Event::getEventByID($sanitizedData);
            $eventTableRow = $newEvent->getAsTableRow();
            $json["tableRow"] = $eventTableRow;

            /*Get new sessions available to the user as options for a select*/
            require_once "../classes/Session.class.php";
            $newSessions = Session::getSessionsByEventID($sanitizedData);
            $sessionsAsOptions = array();
            foreach($newSessions as $session) {
                $sessionsAsOptions[] = "<option value='{$session->getID()}' data-eventForSession='{$session->getEvent()}'>{$session->getName()}</option>";
            }
            $json["newSessionsOptions"] = $sessionsAsOptions;
        }
        
        echo json_encode($json);
    }

    /*Handle incoming requests to unregister a session*/
    if(isset($_POST["unregister-session"])) {
        /*sanitize and validate data*/
        $sanitizedData = sanitizeInputData($_POST["unregister-session"][0]["sessionID"]);
        $rowsAffected = 0;
        if(numeric($sanitizedData)) { 
            $rowsAffected = $_SESSION["currentUser"]->unregisterSession($sanitizedData);
        }
        $json["rowsAffected"] = $rowsAffected;

        echo json_encode($json);
        
    }

    if(isset($_POST["add-session"])) {
        require_once "../classes/Session.class.php";
        /*sanitize and validate data*/
        $sanitizedData = sanitizeInputData($_POST["add-session"][0]["sessionID"]);
        $rowsAffected = 0;
        if(numeric($sanitizedData)) { 
            $rowsAffected = $_SESSION["currentUser"]->registerSession($sanitizedData);
        }
        $json["rowsAffected"] = $rowsAffected;
        /*If inserted properly return the new table row with the number of rows affected*/
        if($rowsAffected > 0) {
            $newSession = Session::getSessionByID($sanitizedData);
            $sessionTableRow = $newSession->getSessionAsRow();
            $json["tableRow"] = $sessionTableRow;
        }
        
        echo json_encode($json);
    }
?>
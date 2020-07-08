<?php

    require_once "./classes/Attendee.class.php";
    /*Start the session */
    session_name("events");
    session_start();

    /*Authentication and authorization checks*/
    if(!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] || !isset($_SESSION["currentUser"])) {
        header("Location: Login.php");
    }
    require_once "utilities.inc.php";
    /*Page header */
    Header::buildHeader("Registrations", true, ($_SESSION["currentUser"]->getRole() < 3), "Registrations");
    $displayName = ucwords($_SESSION["currentUser"]->getName());
    echo "<h1>Registrations:</h1>
       <h2>Hi {$displayName}, you can view and manage your registrations below!</h2>";

    /*Display the current users events and sessions*/
    $userEvents = $_SESSION["currentUser"]->getAllEventsForUser();
    $userSessions = $_SESSION["currentUser"]->getAllSessionsForUser();
    
    /*Get all events available so user can add them if needed */
    $allAvailableEvents = Event::getAllEvents();
    $allAvailableSessions = Session::getAllSessions();

    /*BUILD EVENTS*/
    echo "<h3>Your Registered Events:</h3>";
    $eventsTable = "<table id='events-table' class='table'><thead><tr>
        <td>Event ID</td>
        <td>Name</td>
        <td>Venue</td>
        <td>Start Date</td>
        <td>End Date</td>
        <td>Capacity</td>
        <td>Registered Attendees</td>
        </tr></thead><tbody>";

    /*Select/option of each registered event for the user. Used to */
    $usersEventSelect = "<select id ='userEvents' name='userEvents'><option value ='' selected='selected' disabled>Select an Event...</option>";
    foreach($userEvents as $event) {
        $eventsTable .= $event->getAsTableRow();
        $usersEventSelect .= "<option value='{$event->getID()}'>{$event->getName()}</option>";
    }
    $eventsTable .= "</tbody></table>";
    $usersEventSelect .= "</select>";
    //print the events and select/option for events
    echo $eventsTable;

    echo "<br /> <label for='userEvents'>Select one of your events to unregister (<span class='warning'>WARNING! Unregistering an event will unregister you fromm all sessions for that event!</span>): </label>" . $usersEventSelect . "<button id='unregister-event'>Unregister</button>";

    /*Allow the user to add events they are not currently registered for*/
    $unRegisteredEventSelect = "<select id ='unregisteredEvents' name='unregisteredEvents'><option value ='' selected='selected' disabled>Select an Event to Add...</option>";
  
    $userEventIDs = array();
    foreach($userEvents as $userEvent){
        $userEventIDs[] = $userEvent->getID();
    }
    $allEventsIDs = array();
    foreach($allAvailableEvents as $allEvent){
        $allEventsIDs[] = $allEvent->getID();
    }
    $unRegisteredEventIDs = array_diff($allEventsIDs, $userEventIDs);
    if(!empty($unRegisteredEventIDs)){
        foreach($allAvailableEvents as $allEvent) {
            if(in_array($allEvent->getID(), $unRegisteredEventIDs)){
                $unRegisteredEventSelect .= "<option value='{$allEvent->getID()}'>{$allEvent->getName()}</option>";
            }
        }
    }

    $unRegisteredEventSelect .= "</select>";
    echo "<br /> <label for='unregisteredEvents'>Select a new Event to add to you Registrations: </label>" . $unRegisteredEventSelect . "<button id='add-event'>Add</button>";
  
    echo "<h3>Your Registered Event Sessions:</h3>";
    $sessionsTable = "<table id='sessions-table' class='table'><thead><tr>
        <td>Session ID</td>
        <td>Name</td>
        <td>Event</td>
        <td>Start Date</td>
        <td>End Date</td>
        <td>Capacity</td>
        <td>Registered Attendees</td>
        </tr></thead><tbody>";
    $usersSessionSelect = "<select id='userSessions' name='userSessions'><option value ='' selected='selected' disabled>Select a Session...</option>";
    foreach($userSessions as $session) {
        $sessionsTable .= $session->getSessionAsRow();
        $usersSessionSelect .= "<option value='{$session->getID()}' data-eventForSession='{$session->getEvent()}'>{$session->getName()}</option>";
    }
    $sessionsTable .= "</tbody></table>";
    $usersSessionSelect .= "</select>";
    //print the sessions
    echo $sessionsTable;

    echo "<br /> <label for='userSessions'>Select a Session of yours to Unregister: </label>" . $usersSessionSelect  . "<button id='unregister-session'>Unregister</button>";

    /*Allow the user to add events they are not currently registered for*/
    $unRegisteredSessionSelect = "<select id ='unregisteredSessions' name='unregisteredSessions'><option value ='' selected='selected' disabled>Select a Session to Add...</option>";
    
    /*Determine events not registered by user - again ugly but works well*/
    $userSessionIDs = array();
    foreach($userSessions as $registeredSession) {
        $userSessionIDs[] = $registeredSession->getID();
    }
    $allSessionsIDs = array();
    foreach($allAvailableSessions as $allSession){
        $allSessionsIDs[] = $allSession->getID();
    }
    $unRegisteredSessionIDs = array_diff($allSessionsIDs, $userSessionIDs);
    foreach($allAvailableSessions as $allSession) {
        if(in_array($allSession->getID(), $unRegisteredSessionIDs) && !(in_array($allSession->getEvent(), $unRegisteredEventIDs))) {
            $unRegisteredSessionSelect .= "<option value='{$allSession->getID()}' data-eventForSession='{$allSession->getEvent()}'>{$allSession->getName()}</option>";
        }
    }

    $unRegisteredSessionSelect .= "</select>";
    echo "<br /> <label for='unregisteredSessions'>Select a new session to add to your Registrations: </label>" . $unRegisteredSessionSelect . "<button id='add-session'>Add</button>";
    /*END SESSIONS*/

    echo "<script src='./js/Registrations.js'></script>";
    Footer::buildFooter();
?>
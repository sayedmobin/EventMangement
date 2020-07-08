<?php
 
    require_once "./classes/Attendee.class.php";
    session_name("events");
    session_start();
    /*Authentication and authorization checks*/
    if(!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] || !isset($_SESSION["currentUser"])) {
        header("Location: Login.php");
    }
    /*Load utilities*/
    include "utilities.inc.php";

    /*Include our form handler here, before any other actions are performed.*/
    include "./form_handlers/Admin_Form_Handler.php";

    /*Get the authorization level*/
    define("USER_ROLE", $_SESSION["currentUser"]->getRole());

    Header::buildHeader("Admin", true, (USER_ROLE < 3), "Admin");
    $displayName = ucwords($_SESSION["currentUser"]->getName());
    echo "<h1>Registrations:</h1>
    <h2>Hi {$displayName}, you can view and manage users, events, and sessions below!</h2><br />";

    if(!(USER_ROLE < 3)) {
        header("Location: Events.php");
    }

    /*SETUP - basic setup for roles */
     $roleTitle = USER_ROLE == 2 ? "Event Manager" : "Admin";
     echo "<h2>Admin Panel</h2>
         <h3>Your role: $roleTitle</h3><br />";
     
    /*Get all venues*/
    $venues = Venue::getAllVenues();

    /*if user is an admin */
    if(USER_ROLE == 1) {
 
        $events = Event::getAllEvents();
        $users = Attendee::getAllUsers();
        
        $eventManagers = Event::getAllEventManagers();

        include "./inc/Admin_Role_Specific_Functions.inc.php";
    }

    if(USER_ROLE == 2) {

        $events = $_SESSION["currentUser"]->getManagedEvents();
        $users = Attendee::getAllUsers();
    }
    
    $sessions = array();

    foreach($events as $event) {
        $sessions[] = Session::getSessionsByEventID($event->getID());
    } 


    echo "<div><h1 class='text-center'><u>Events</u></h1>";
    $eventsTable = "<table id='events-table' class='table'><thead><tr>
        <td>Event ID</td>
        <td>Name</td>
        <td>Venue</td>
        <td>Start Date</td>
        <td>End Date</td>
        <td>Capacity</td>
        <td>Registered Attendees</td>
        </tr></thead><tbody>";
    foreach($events as $event) {
        $eventsTable .= $event->getAsTableRow();
    }
    $eventsTable .= "</tbody></table>";
    echo $eventsTable;
    
    //form setup
    echo "<form id='event-form' method='POST' action='Admin.php?feature=event'>";

    //add
    $addEventFields = buildEventInputFields("add");
    echo "<div class='col-lg-12 panel panel-default'><h4>Add a new event:</h4>";
    echo "<div class='eventAdd'>$addEventFields</div><br /><button id='add-event'>Add Event</button></div><br />";

    //update
    $updateEventFields = buildEventInputFields("update");
    $updateEventSelect = buildEventSelect("update");
    echo "<div class='col-lg-12 panel panel-default'><h4>Update an event:</h4>";
    echo "<label>Select an event below and edit the fields to update it: </label>$updateEventSelect";
    echo "<br /> <div class='eventUpdate'>$updateEventFields</div><br /><button id='update-event'>Update Event</button></div><br />";

    //delete    
    $deleteEventSelect = buildEventSelect("delete");
    echo "<div class='col-lg-12 panel panel-default'><h4>Delete an event:</h4>";
    echo "<br />" . $deleteEventSelect . "<br /><button id='delete-event'>Delete Event</button></div><br />";
    
    //event attendees
    $userEventSelect = buildEventSelect("user");
    echo "<div class='col-lg-12 panel panel-default'><h4>Update attendees for an event:</h4>";
    echo "<br />" . buildUserSelect() . $userEventSelect . "<br /><button id='add-event-user'>Register</button>" . "<button id='remove-event-user'>Unregister</button></div><br />";
    

    echo "</form></div>";//close form

    echo "<div><h1 class='text-center'><u>Sessions</u></h1>";
    $sessionsTable = "<table id='sessions-table' class='table'><thead><tr>
        <td>Session ID</td>
        <td>Name</td>
        <td>Event</td>
        <td>Start Date</td>
        <td>End Date</td>
        <td>Capacity</td>
        <td>Registered Attendees</td>
        </tr></thead><tbody>";
    if(!empty($sessions)) {
        foreach($sessions as $eventSession) {
            foreach($eventSession as $session) {
                $sessionsTable .= $session->getSessionAsRow();
            }
        }
    }
    $sessionsTable .= "</tbody></table>";
    echo $sessionsTable;
    
    /*form setup*/
    echo "<form id='session-form' method='POST' action='Admin.php?feature=session'>";

    //add
    $addsessionFields = buildsessionInputFields("add", 1);
    echo "<div class='col-lg-12 panel panel-default'><h4>Add a new session:</h4>";
    echo "<div class='sessionAdd'>$addsessionFields</div><br /><button id='add-session'>Add session</button></div><br />";

    //update
    $updateSessionFields = buildSessionInputFields("update", 2);
    $updateSessionSelect = buildSessionSelect("update");
    echo "<div class='col-lg-12 panel panel-default'><h4>Update an session:</h4>";
    echo "<label>Select an session below and edit the fields to update it: </label>$updateSessionSelect";
    echo "<br /> <div class='sessionUpdate'>$updateSessionFields</div><br /><button id='update-session'>Update session</button></div><br />";

    //delete
    $deleteSessionSelect = buildSessionSelect("delete");
    echo "<div class='col-lg-12 panel panel-default'><h4>Delete a session:</h4>";
    echo "<br />" . $deleteSessionSelect . "<br /><button id='delete-session'>Delete Session</button></div><br />";
    
    //add attendees
    $userSessionSelect = buildSessionSelect("user");
    echo "<div class='col-lg-12 panel panel-default'><h4>Add or Remove attendees to a session:</h4>";
    echo $userSessionSelect . buildUserSelect("-session") . "<br /><button id='add-session-user'>Register</button>" . "<button id='remove-session-user'>Unregister</button></div><br />";

    //close form and create space
    echo "</form></div>";

    function buildEventInputFields($type) {
        $venueSelect = buildVenueSelect($type);
        $fields = "<label for='eventName-$type' >Name: </label><input id='eventName-$type' name='eventName-$type' type='text'>
        <label >Venue: </label>$venueSelect
        <label for='eventStartDate-$type' >Start Date:</label><input id='eventStartDate-$type' name='eventStartDate-$type' type='date'>
        <label for='eventEndDate-$type'>End Date:</label><input id='eventEndDate-$type' name='eventEndDate-$type' type='date'>
        <label for='eventCapacity-$type'>Capacity:</label><input id='eventCapacity-$type' name='eventCapacity-$type' type='number' min='1'>";

        if(USER_ROLE == 1){
            global $eventManagers;
            $managerSelect = "<select class='managerSelect' name='manager-$type'><option value ='' selected='selected' disabled>Select an Event Manager...</option>";
            foreach($eventManagers as $manager){
                $managerSelect .= "<option value='{$manager->getID()}'>{$manager->getName()}</option>";
            }
            $managerSelect .= "</select>";
            $fields .= "<label>Choose an event manager: </label>$managerSelect";

        }

        return $fields;
    }

    function buildSessionInputFields($type, $sessionCounter) {
        $venueSelect = buildVenueSelect($type);
        return "<label for='sessionName-$type' >Name: </label><input id='sessionName-$type' name='sessionName-$type' type='text'>
        <label >Event: </label>" . buildEventSelect("session-$sessionCounter") .
        "<label for='sessionStartDate-$type' >Start Date:</label><input id='sessionStartDate-$type' name='sessionStartDate-$type' type='date'>
        <label for='sessionEndDate-$type'>End Date:</label><input id='sessionEndDate-$type' name='sessionEndDate-$type' type='date'>
        <label for='sessionCapacity-$type'>Capacity:</label><input id='sessionCapacity-$type' name='sessionCapacity-$type' type='number' min='1'>";
    }

    function buildEventSelect($type) {
        global $events;
        $eventsSelect = "<select id='eventSelect-$type' class ='eventSelect' name='event-$type'><option value ='' selected='selected' disabled>Select an Event...</option>";    
        foreach($events as $event) {
            $eventsSelect .= "<option value='{$event->getID()}'>{$event->getName()}</option>";
        
        }
        $eventsSelect .= "</select>";
        return $eventsSelect;

    }

    function buildSessionSelect($type) {
        global $sessions;
        $sessionSelect = "<select class='sessionSelect' name='session-$type'><option value ='' selected='selected' disabled>Select a Session...</option>";
        if(!empty($sessions)) {
            foreach($sessions as $eventSession) {
                foreach($eventSession as $session) {
                    $sessionSelect .= "<option value='{$session->getID()}' data-eventForSession='{$session->getEvent()}'>{$session->getName()}</option>";
                }
            }
        }
        $sessionSelect .= "</select>";
        return $sessionSelect;
    }

    function buildVenueSelect($type) {
        global $venues;
        $venueSelect = "<select class ='venueSelect' name='venue-$type'><option value ='' selected='selected' disabled>Select a Venue...</option>"; 
        foreach($venues as $venue) {
            $venueSelect .= "<option value='{$venue->getID()}'>{$venue->getName()}</option>";
        } 
        $venueSelect .= "</select>";
        return $venueSelect;
    }

    function buildUserSelect($type=null){
        global $users;
        $userSelect = "<select class='userSelect' name='selectedUser$type'><option value ='' selected='selected' disabled>Select an Attendee...</option>";
        foreach($users as $user) {
            $userSelect .= "<option value='{$user->getID()}'>{$user->getName()}</option>";
        }
        $userSelect .= "</select>";
        return $userSelect;
    }

    /*Add js */
    echo "<script src='./js/Admin.js'></script>";

    Footer::buildFooter();
?>
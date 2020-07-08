<?php
  
    require "./classes/Attendee.class.php";
    session_name("events");
    session_start();
    if(!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"]) {
        header("Location: Login.php");
    }
    
    require_once "utilities.inc.php";
    Header::buildHeader("All Events", false, ($_SESSION["currentUser"]->getRole() < 3), "Events");
    $displayName = ucwords($_SESSION["currentUser"]->getName());
    echo "<h1>Hi $displayName!</h1> 
    <h2>Here are all of our upcoming events:</h2>";

    /*Get all events and all sessions*/
    $eventDB = new EventsPDO();
    $sessionsDB = new SessionPDO();

    $events = $eventDB->getAllEvents();
    $sessions = $sessionsDB->getAllSessions();
    
    foreach($events as $event) {
        $eventListing = "<h2>{$event->getName()}</h2>
            <h3>{$event->getStartDate()} - {$event->getEndDate()}</h3>
            <h4>".Venue::getVenueByName($event->getID())."</h4>
            <h5>Maximum Participants: {$event->getNumberAllowed()}</h5>";
        $sessionsTable = "<table class='table'><thead><tr>
            <td>Session Name</td>
            <td>Start Date</td>
            <td>End Date</td>
            <td>Capacity</td>
            </tr></thead><tbody>"; 
        /*Print a sessions table for each event*/ 
        foreach($sessions as $session) {
            if($session->getEvent() === $event->getID()) {
                $sessionsTable .= "<tr>
                    <td>{$session->getName()}</td>
                    <td>{$session->getStartDate()}</td>
                    <td>{$session->getEndDate()}</td>
                    <td>{$session->getNumberAllowed()}</td>
                    </tr>";
            }
        }
        $sessionsTable .= "</tbody></table>";

        echo $eventListing;
        echo $sessionsTable;
    }
    Footer::buildFooter();

?>
<?php
    
    class Header {


        public static function buildHeader($title, $includeJQuery, $isAdmin, $activePage, $noNav=false) {
            $navigation = null;
            if(!$noNav) {    
                $navigation = buildNavigation($isAdmin, $activePage);
            }
            
            $header = "<!DOCTYPE html>
                <html lang='en'>
                    <head>
                        <meta charset='utf-8' />
                        <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                        <title>$title</title>
                        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' integrity='sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u' crossorigin='anonymous'>";
            if($includeJQuery)
                $header .= "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>";
            $header .= "</head>
                <body>$navigation<div class='container-fluid'>";
            
            echo $header;
        }
    }

    function buildNavigation($isAdmin, $activePage) {
        $navigation = " <nav class='navbar  navbar-default'>
            <div class='container-fluid'>
            <div class='navbar-header'>
                <a class='navbar-brand' href='#'>Event Registration System</a>
            </div>
            <ul class='nav navbar-nav'>
                <li";
            $navigation .= $activePage == 'Events' ? ' class="active"' : '';
            $navigation .= "><a href='Events.php'>All Events</a></li>
                <li";
            $navigation .= $activePage == 'Registrations' ? ' class="active"' : '';
            $navigation .= "><a href='Registrations.php'>Registration</a></li>";
            
            if($isAdmin) {
                $navigation .= "<li";
                $navigation .= $activePage == 'Admin' ? ' class="active"' : '';
                $navigation .= "><a href='Admin.php'>Admin</a></li>";
            }

            $navigation .="</ul>
            <ul class='nav navbar-nav navbar-right'>
                <li><a href='Login.php?logout=true'><span class='glyphicon glyphicon-log-out'></span>Logout</a></li>
            </ul>
            </div>
        </nav> ";

        return $navigation;
    }

?>
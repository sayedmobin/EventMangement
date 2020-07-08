<?php

    echo "<div><h1 class='text-center'><u>Users</u></h1>";
    $usersTable = "<table id='users-table' class='table'><thead><tr>
        <td>User ID</td>
        <td>Name</td>
        <td>Role</td>
        <td>Registered Events Count</td>
        <td>Registered Sessions Count</td>
        </tr></thead><tbody>";
    foreach($users as $user) {
        $usersTable .= $user->getAsTableRow();
    }
    $usersTable .= "</tbody></table>";
    echo $usersTable;

    echo "<form id='users-form' method='POST' action='Admin.php?feature=user'>";

    //add
    $addUserFields = buildUserInputFields("add");
    echo "<div class='col-lg-12 panel panel-default'><h4>Add a new User:</h4>";
    echo "<div class='userAdd'>$addUserFields</div><br /><button id='add-user'>Add User</button></div><br />";

    //update
    $updateUserFields = buildUserInputFields("update");
    $userNoAdminSelectUpdate = buildNoAdminUuserSelect("update");
    echo "<div class='col-lg-12 panel panel-default'><h4>Update User:</h4>";
    echo "<label>Select a User below and edit the fields to update it: </label>$userNoAdminSelectUpdate";
    echo "<div class='userUpdate'>$updateUserFields</div><br /><button id='update-user'>Update User</button></div><br />";

    //delete
    $userNoAdminSelectDelete = buildNoAdminUuserSelect("delete");
    echo "<div class='col-lg-12 panel panel-default'><h4>Delete a User:</h4>";
    echo "<br />" . $userNoAdminSelectDelete . "<br /><button id='delete-user'>Delete User</button></div><br />";

    echo "</form></div>";//close form

    echo "<div><h1 class='text-center'><u>Venues</u></h1>";
    $venuesTable = "<table id='venues-table' class='table'><thead><tr>
        <td>Venue ID</td>
        <td>Name</td>
        <td>Capacity</td>
        </tr></thead><tbody>";
    foreach($venues as $venue) {
        $venuesTable .= $venue->getAsTableRow();
    }
    $venuesTable .= "</tbody></table>";
    echo $venuesTable;

    echo "<form id='venues-form' method='POST' action='Admin.php?feature=venue'>";

    //add
    $addVenueFields = buildVenueInputFields("add");
    echo "<div class='col-lg-12 panel panel-default'><h4>Add a new Venue:</h4>";
    echo "<div class='add-venue'>$addVenueFields</div><br /><button id='add-venue'>Add Venue</button></div><br />";

    //update
    $updateVenueFields = buildVenueInputFields("update");
    echo "<div class='col-lg-12 panel panel-default'><h4>Update a Venue:</h4>";
    echo "<label>Select a Venue below and edit the fields to update it: </label>".buildVenueSelect("updateVenue");
    echo "<div class='update-venue'>$updateVenueFields</div><br /><button id='update-venue'>Update Venue</button></div><br />";

    //delete
    $deleteVenueSelect = buildVenueSelect("deleteVenue");
    echo "<div class='col-lg-12 panel panel-default'><h4>Delete a Venue:</h4>";
    echo "<br />" . $deleteVenueSelect . "<br /><button id='delete-venue'>Delete Venue</button></div><br />"; 

    echo "</form></div>";
   
    

    function buildUserInputFields($type) {
        $roleSelect = "<select id='role-select-$type' name='user-role-$type'><option value ='' selected='selected' disabled>Select a Role...</option>
            <option value='1'>Admin</option>
            <option value='2'>Event Manager</option>
            <option value='3'>Attendee</option>
            </select>";

        $fields = "<label for='userName-$type' >Name: </label><input id='userName-$type' name='userName-$type' type='text'>
        <label for='userPassword-$type' >Password: </label><input id='userPassword-$type' name='userPassword-$type' type='text'>
        <label for='role-select-$type'>Role: </label>$roleSelect";

        return $fields;
    }

    function buildNoAdminUuserSelect($type) {
        global $users;
        $select = "<select id='noAdmin-$type' name='noAdminUsers-$type'><option value ='' selected='selected' disabled>Select a User...</option>";
        foreach($users as $user) {
            if($user->getRole() != 1) {
                $select .= "<option value='{$user->getID()}'>{$user->getName()}</option>";
            }
        }
        $select .= "</select>";
        return $select;
    }

    function buildVenueInputFields($type) {
        global $venues;
        $fields = "<label for='venueName-$type' >Name: </label><input id='venueName-$type' name='venueName-$type' type='text'>
        <label for='venueCapacity-$type' >Capacity: </label><input id='venueCapacity-$type' name='venueCapacity-$type' type='number'>";

        return $fields;
    }

?>
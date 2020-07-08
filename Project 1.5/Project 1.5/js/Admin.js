
    var buildInput = function(type) {
        var output = $("<input>")
            .attr("type", "hidden")
            .attr("name", "action").val(type);
        return output;
    }


    $("#add-event").on("click", function(){
        //client side validation
        if($("#eventName-add").val() && $("select[name='venue-add']").val() && $("#eventStartDate-add").val() && $("#eventEndDate-add").val() && $("#eventCapacity-add").val()) {
            var input = buildInput("add");
            $('#event-form').append(input);
            $("#event-form").submit();
        }
    });

    //update event
    $("#update-event").on("click", function(){
        if($("#eventSelect-update").val() && $("#eventName-update").val() && $("select[name='venue-update']").val() && $("#eventStartDate-update").val() && $("#eventEndDate-update").val() && $("#eventCapacity-update").val()) {
            var input = buildInput("update");
            $('#event-form').append(input);
            $("#event-form").submit();
        }
    });

    //delete event
    $("#delete-event").on("click", function(){
        if($("#eventSelect-delete").val()) {
            var input = buildInput("delete");
            $('#event-form').append(input);
            $("#event-form").submit();
        }
    });

    //add user to event
    $("#add-event-user").on("click", function(){
        if($("#eventSelect-user").val() && $("select[name='selectedUser']").val()) {
            var input = buildInput("add-user");
            $('#event-form').append(input);
            $("#event-form").submit();
        }
    });

    //remove user from event
    $("#remove-event-user").on("click", function(){
        if($("#eventSelect-user").val() && $("select[name='selectedUser']").val()) {
            var input = buildInput("remove-user");
            $('#event-form').append(input);
            $("#event-form").submit();
        }
    });

    /*Sessions*/
    //add session
    $("#add-session").on("click", function(){
        if($("#sessionName-add").val() && $("#eventSelect-session-1").val() && $("#sessionStartDate-add").val() && $("#sessionEndDate-add").val() && $("#sessionCapacity-add").val()) {
            var input = buildInput("add");
            $('#session-form').append(input);
            $("#session-form").submit();
        }
    });

    //update session
    $("#update-session").on("click", function(){
        if($("select[name='session-update']").val() &&  $("#sessionName-update").val() && $("#eventSelect-session-2").val() && $("#sessionStartDate-update").val() && $("#sessionEndDate-update").val() && $("#sessionCapacity-update").val()) {
            var input = buildInput("update");
            $('#session-form').append(input);
            $("#session-form").submit();
        }
    });

    //delete session
    $("#delete-session").on("click", function(){
        if($("select[name='session-delete']").val()) {
            var input = buildInput("delete");
            $('#session-form').append(input);
            $("#session-form").submit();
        }
    });

    //add user to session
    $("#add-session-user").on("click", function(){
        if($("select[name='session-user']").val() && $("select[name='selectedUser-session']").val()) {
            var input = buildInput("add-user");
            $('#session-form').append(input);
            $("#session-form").submit();
        }
    });

    $("#remove-session-user").on("click", function(){
        if($("select[name='session-user']").val() && $("select[name='selectedUser-session']").val()) {
            var input = buildInput("remove-user");
            $('#session-form').append(input);
            $("#session-form").submit();
        }
    });

    /*Users */
    //add
    $("#add-user").on("click", function(){
        if($("#userName-add").val() && $("#userPassword-add").val() && $("#role-select-add").val()) {
            var input = buildInput("add");
            $('#users-form').append(input);
            $("#users-form").submit();
        }
    });

    //update
    $("#update-user").on("click", function(){
        if($("#userName-update").val() && $("#userPassword-update").val() && $("#role-select-update").val() && $("#noAdmin-update").val()) {
            var input = buildInput("update");
            $('#users-form').append(input);
            $("#users-form").submit();
        }
    });

    //delete
    $("#delete-user").on("click", function(){
        if($("#noAdmin-delete").val()) {
            var input = buildInput("delete");
            $('#users-form').append(input);
            $("#users-form").submit();
        }
    });

    /*Venues */

    //add
    $("#add-venue").on("click", function(){
        if($("#venueName-add").val() && $("#venueCapacity-add").val()) {
            var input = buildInput("add");
            $('#venues-form').append(input);
            $("#venues-form").submit();
        }
    });

    //update
    $("#update-venue").on("click", function(){
        if($("#venueName-update").val() && $("#venueCapacity-update").val() && $("select[name='venue-updateVenue']").val()) {
            var input = buildInput("update");
            $('#venues-form').append(input);
            $("#venues-form").submit();
        }
    });

    //delete
    $("#delete-venue").on("click", function(){
        if($("select[name='venue-deleteVenue']").val()) {
            var input = buildInput("delete");
            $('#venues-form').append(input);
            $("#venues-form").submit();
        }
    });

    
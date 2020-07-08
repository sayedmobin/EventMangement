
    $("#unregister-event").on("click", function(){
        let event = $("#userEvents").val();
        $(".error,.success-alert").remove();//remove errors
        if(event !== null && event !== ""){
            let postData = {"unregister-event":[{"action":"remove", "eventID":event}]};
            $.ajax({url:"./form_handlers/Registration_Form_Handler.php", type: "POST", data: postData, success: 
                function(data){
                    let json = JSON.parse(data);
                    let rowsAffected = json["rowsAffected"];
                    if(rowsAffected > 0) {

                        /*Since the user is no longer going to the event unregister all sessions for that event*/
                        let unregisteredSession = null;
                        $("#userSessions option[data-eventForSession='"+event+"']").each(function(){
                            unregisterSession($(this).val(), false);
                        });
                        $("#userSessions").prop("selectedIndex", 0);

                        $("#unregisteredSessions option[data-eventForSession='"+event+"']").remove();
                        $("#unregisteredSessions").prop("selectedIndex", 0);

                        /*Let the user know it was successful*/
                        $("tr[data-event='"+event+"']").remove();
                        let cloneToMove = $("#userEvents option[value="+event+"]").clone();
                        $("#unregisteredEvents").append(cloneToMove);//move to unregistered events list
                        $("#userEvents option[value="+event+"]").remove();
                        $("#userEvents").prop("selectedIndex", 0);
                        $("#unregister-event").after("<span id='event-unregister-success' class='success-alert'>Event unregistered successfully!</span>");
                    } else {
                        $("#unregister-event").after("<span id='event-unregister-fail' class='error'>Something went wrong removing your registration!</span>");
                    }
                }
            });
        } else {
            $("#unregister-event").after("<span class='error'>No Event Selected!</span>");
        }
    });

    /* ADD EVENT - when we need to add an event */
    $("#add-event").on("click", function(){
        let event = $("#unregisteredEvents").val(); 
        console.log(event);
        $(".error,.success-alert").remove();//remove errors
        if(event !== null && event !== "") {
            let postData = {"add-event":[{"eventID":event}]};
            $.ajax({url:"./form_handlers/Registration_Form_Handler.php", type: "POST", data: postData, success: 
                function(data){
                    let json = JSON.parse(data);
                    let rowsAffected = json["rowsAffected"];
                    if(rowsAffected > 0) {
                        let tableRow = json["tableRow"];
                        $("#events-table tbody").append(tableRow);
                        let cloneToMove = $("#unregisteredEvents option[value='"+event+"']").clone();
                        $("#unregisteredEvents option[value='"+event+"']").remove();
                        $("#unregisteredEvents").prop("selectedIndex", 0);
                        $("#userEvents").append(cloneToMove);
                        $("#add-event").after("<span id='session-add-success' class='success-alert'>Event registered successfully!</span>");

                        /*Add now available sessions to the unregistered sessions select */
                        let newSessions = json["newSessionsOptions"];
                        for(var i = 0, len = newSessions.length; i < len; i++){
                            $("#unregisteredSessions").append(newSessions[i]);
                        }

                    } else {
                        $("#add-event").after("<span id='event-unregister-fail' class='error'>Something went wrong adding your registration!</span>");
                    }
                }
            });
        } else {
            $("#add-event").after("<span class='error'>No Event Selected!</span>");
        }
    });

    /*UNREGISTER SESSION*/
    $("#unregister-session").on("click", function(){
        let session = $("#userSessions").val();
        $(".error,.success-alert").remove();//remove all errors
        if(session !== null && session !== "") {
            unregisterSession(session,true);
        }
    });

    $("#add-session").on("click", function(){
        let session = $("#unregisteredSessions").val();
        $(".error,.success-alert").remove();//remove all errors
        if(session !== null && session !== "") {
            let postData = {"add-session":[{"sessionID":session}]};
            $.ajax({url:"./form_handlers/Registration_Form_Handler.php", type: "POST", data: postData, success: 
                function(data){
                    let json = JSON.parse(data);
                    let rowsAffected = json["rowsAffected"];
                    if(rowsAffected > 0) {
                        let tableRow = json["tableRow"];
                        $("#sessions-table tbody").append(tableRow);
                        let cloneToMove = $("#unregisteredSessions option[value='"+session+"']").clone();
                        $("#unregisteredSessions option[value='"+session+"']").remove();
                        $("#unregisteredSessions").prop("selectedIndex", 0);
                        $("#userSessions").append(cloneToMove);
                        $("#add-session").after("<span id='session-add-success' class='success-alert'>Session registered successfully!</span>");
                    } else {
                        $("#add-session").after("<span id='session-add-fail' class='error'>Something went wrong registering for the session!</span>");
                    }
                }
            });
        } else {
            $("#add-session").after("<span class='error'>No Event Selected!</span>");
        }
    });
`1`
    function unregisterSession(sessionID, cloneandMove){
        let postData = {"unregister-session":[{"sessionID":sessionID}]};
        var successful = false;
            $.ajax({url:"./form_handlers/Registration_Form_Handler.php", type: "POST", data: postData, success: 
                function(data){
                    let json = JSON.parse(data);
                    let rowsAffected = json["rowsAffected"];
                    if(rowsAffected > 0) {
                        successful = true;
                        $("tr[data-session='"+sessionID+"']").remove();
                        $("#unregister-session").after("<span id='session-unregister-success' class='success-alert'>Session unregistered successfully!</span>");
                        $("#userSessions").prop("selectedIndex", 0);
                        if(cloneandMove){
                            let cloneToMove =  $("#userSessions option[value="+sessionID+"]").clone();
                            $("#userSessions option[value="+sessionID+"]").remove();
                            $("#unregisteredSessions").append(cloneToMove);
                        } else {
                            $("#userSessions option[value="+sessionID+"]").remove();
                        }
                    } else {
                        $("#unregister-session").after("<span id='session-unregister-fail' class='error'>Something went wrong removing your registration!</span>");
                    }
                }
            });
        return successful;    
    }
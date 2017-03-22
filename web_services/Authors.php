<?php

# Access the operation to do with a author
if (isset($_POST['action'])){
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action){
        # Select the list of authors
        case 1:
            get_authors($functions);
            break;
        case 2:
            get_specific_author($functions);
            break;
        case 3:
            edit_author($functions);
            break;
    }

} else {
    echo json_encode( array("status" => 666, "message" => "No se recibió acción a realizar.") );
}

# get_authors
function get_authors($functions){
    $result = $functions->get_authors();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_author
function get_specific_author($functions){

}

# edit_author
function edit_author($functions){

}
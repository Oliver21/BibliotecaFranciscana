<?php

# Access the operation to do with a magazine
if (isset($_POST['action'])){
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action){
        # Select the list of magazines
        case 1:
            get_magazines($functions);
            break;
        case 2:
            get_specific_magazine($functions);
            break;
        case 3:
            edit_magazine($functions);
            break;
    }
} else {
    echo json_encode( array("status" => 666, "message" => "No se recibió acción a realizar.") );
}

# get_magazines
function get_magazines($functions){
    $result = $functions->get_magazines();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_magazine
function get_specific_magazine($functions){

}

# edit_magazine
function edit_magazine($functions){

}
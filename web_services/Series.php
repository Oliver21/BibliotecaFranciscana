<?php

# Access the operation to do with a serie
if (isset($_POST['action'])){
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action){
        # Select the list of series
        case 1:
            get_series($functions);
            break;
        case 2:
            get_specific_serie($functions);
            break;
        case 3:
            edit_serie($functions);
            break;
    }

} else {
    echo json_encode( array("status" => 666, "message" => "No se recibió acción a realizar.") );
}

# get_series
function get_series($functions){
    $result = $functions->get_series();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_serie
function get_specific_serie($functions){

}

# edit_serie
function edit_serie($functions){

}
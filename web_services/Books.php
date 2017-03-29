<?php

# Access the operation to do with a book
if (isset($_POST['action'])){
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action){
        # Select the list of books
        case 1:
            get_books($functions);
            break;
        # Select a specific book of the list
        case 2:
            get_specific_book($functions);
            break;
        # Edit book information
        case 3:
            edit_book($functions);
            break;
    }

} else {
    echo json_encode( array("status" => 666, "message" => "No se recibió acción a realizar.") );
}

# get_books
function get_books($functions){
    $result = $functions->get_books();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_book
function get_specific_book($functions){
    
}

# edit_book
function edit_book($functions){

}
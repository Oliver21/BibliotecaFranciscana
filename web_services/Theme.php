<?php

# Access the operation to do with a theme
if (isset($_POST['action'])){
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action){
        # Select the list of themes
        case 1:
            get_themes($functions);
            break;
        case 2:
            get_specific_theme($functions);
            break;
        case 3:
            edit_theme($functions);
            break;
        case 4:
            add_theme($functions);
            break;
        case 5:
            delete_theme($functions);
            break;
        default:
            echo json_encode( array("status" => 600, "message" => "Acción no válida.") );
    }

} else {
    echo json_encode( array("status" => 666, "message" => "No se recibió acción a realizar.") );
}

# delete theme
function delete_theme($functions){


    if ( isset( $_POST['id_tema'] ) ){
        $id_tema = $_POST['id_tema'];
        # Display the result
        echo json_encode( $functions->delete_theme($id_tema) );
    } else {
        echo json_encode( array( "status" => 0, "message" =>"No se recibió el tema a borrar." ) );
    }


}

# add_theme
function add_theme($functions){
    # id_tema
    # tema
    if ( ! isset($_POST['tema']) ){
        echo json_encode( array("status" => 601, "message" => ".") );
    } else {
        $tema= $_POST['tema'];
        echo json_encode( $functions->add_theme($tema) );
    }
}

# get_themes
function get_themes($functions){
    $result = $functions->get_themes();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_theme
/**
 * @param $functions
 */
function get_specific_theme($functions){

}

# edit_theme
function edit_theme($functions){
    $id_tema = $_POST['id_tema'];
    $tema = $_POST['tema'];

    # Display the result
    echo json_encode( $functions->edit_theme($id_tema, $tema) );
}
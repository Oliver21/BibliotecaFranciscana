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
        case 4:
            add_serie($functions);
            break;
        case 5:
            delete_serie($functions);
            break;
        default:
            echo json_encode( array("status" => 600, "message" => "Acción no válida.") );
    }

} else {
    echo json_encode( array("status" => 666, "message" => "No se recibió acción a realizar.") );
}

# delete serie
function delete_serie($functions){


    if ( isset( $_POST['id_serie'] ) ){
        $id_serie = $_POST['id_serie'];
        # Display the result
        echo json_encode( $functions->delete_serie($id_serie) );
    } else {
        echo json_encode( array( "status" => 0, "message" =>"No se recibió la serie a borrar." ) );
    }


}

# add_serie
function add_serie($functions){
    # nombre_serie
    # volumen_serie
    $nombre_serie = $_POST['nombre_serie'];
    if ( isset( $nombre_serie ) && $nombre_serie != "" ){
        $nombre_serie = $_POST['nombre_serie'];
        $volumen_serie = $_POST['volumen_serie'];
        echo json_encode( $functions->insert_serie($nombre_serie, $volumen_serie) );
    } else {
        echo json_encode( array("status" => 601, "message" => "No se recibió información necesaria de la serie.") );
    }
}

# get_series
/**
 * @param $functions
 */
function get_series($functions){
    $result = $functions->get_series();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_serie
/**
 * @param $functions
 */
function get_specific_serie($functions){

}

# edit_serie
/**
 * @param $functions
 */
function edit_serie($functions){
    $id_serie = $_POST['id_serie'];
    $nombre_serie = $_POST['nombre_serie'];
    $volumen_serie = $_POST['volumen_serie'];

    # Display the result
    echo json_encode( $functions->edit_serie($id_serie, $nombre_serie, $volumen_serie) );
}
<?php

# Access the operation to do with a editorial
if (isset($_POST['action'])) {
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action) {
        # Select the list of editorials
        case 1:
            get_editorials($functions);
            break;
        case 2:
            get_specific_editorial($functions);
            break;
        case 3:
            edit_editorial($functions);
            break;
        case 4:
            add_editorial($functions);
            break;
        case 5:
            delete_editorial($functions);
            break;
        default:
            echo json_encode(array("status" => 600, "message" => "Acción no válida."));
    }

} else {
    echo json_encode(array("status" => 666, "message" => "No se recibió acción a realizar."));
}

# delete editorial
/**
 * @param $functions
 */
function delete_editorial($functions)
{


    if (isset($_POST['id_editorial'])) {
        $id_editorial = $_POST['id_editorial'];
        # Display the result
        echo json_encode($functions->delete_editorial($id_editorial));
    } else {
        echo json_encode(array("status" => 0, "message" => "No se recibió la editorial a borrar."));
    }


}

# add_editorial
/**
 * @param $functions
 */
function add_editorial($functions)
{
    # nombre_editorial
    # nombre_direccion
    if (isset($_POST['nombre_editorial']) && $_POST['nombre_editorial'] != '' ) {
        $nombre_editorial = $_POST['nombre_editorial'];
        $nombre_direccion = $_POST['nombre_direccion'];
        echo json_encode($functions->add_editorial($nombre_editorial, $nombre_direccion));
    } else {
        echo json_encode(array("status" => 601, "message" => "No se recibió el nombre de editorial."));
    }
}

# get_editorials
/**
 * @param $functions
 */
function get_editorials($functions)
{
    $result = $functions->get_editorial();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_editorial
/**
 * @param $functions
 */
function get_specific_editorial($functions)
{

}

# edit_editorial
/**
 * @param $functions
 */
function edit_editorial($functions)
{
    $id_editorial = $_POST['id_editorial'];
    $nombre_editorial = $_POST['nombre_editorial'];
    $nombre_direccion = $_POST['nombre_direccion'];

    # Display the result
    echo json_encode($functions->edit_editorial($id_editorial, $nombre_editorial, $nombre_direccion));
}
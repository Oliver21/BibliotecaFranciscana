<?php

# Access the operation to do with a collection
if (isset($_POST['action'])) {
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action) {
        # Select the list of collections
        case 1:
            get_collections($functions);
            break;
        case 2:
            get_specific_collection($functions);
            break;
        case 3:
            edit_collection($functions);
            break;
        case 4:
            add_collection($functions);
            break;
        case 5:
            delete_collection($functions);
            break;
        default:
            echo json_encode(array("status" => 600, "message" => "Acción no válida."));
    }

} else {
    echo json_encode(array("status" => 666, "message" => "No se recibió acción a realizar."));
}

# delete collection
/**
 * @param $functions
 */
function delete_collection($functions)
{
    if (isset($_POST['id_coleccion'])) {
        $id_coleccion = $_POST['id_coleccion'];
        # Display the result
        echo json_encode($functions->delete_collection($id_coleccion));
    } else {
        echo json_encode(array("status" => 0, "message" => "No se recibió la colección a borrar."));
    }
}

# add_collection
/**
 * @param $functions
 */
function add_collection($functions)
{
    # $nombre_coleccion, $numero_coleccion, $volumenes, $id_seccion
    $nombre_coleccion = $_POST['nombre_coleccion'];
    if (isset( $nombre_coleccion ) && $nombre_coleccion != "" ) {
        $nombre_coleccion = $_POST['nombre_coleccion'];
        $numero_coleccion = $_POST['numero_coleccion'];
        $volumenes = $_POST['volumenes'];
        $id_seccion = $_POST['id_seccion'];
        echo json_encode($functions->add_collection($nombre_coleccion, $numero_coleccion, $volumenes, $id_seccion));
    } else {
        echo json_encode(array("status" => 601, "message" => "No se recibió información necesaria de la colección."));
    }
}

# get_collections
/**
 * @param $functions
 */
function get_collections($functions)
{
    $result = $functions->get_collections();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_collection
/**
 * @param $functions
 */
function get_specific_collection($functions)
{

}

# edit_collection
/**
 * @param $functions
 */
function edit_collection($functions)
{
    $id_coleccion = $_POST['id_coleccion'];
    $nombre_coleccion = $_POST['nombre_coleccion'];
    $numero_coleccion = $_POST['numero_coleccion'];
    $volumenes = $_POST['volumenes'];
    $id_seccion = $_POST['id_seccion'];

    # Display the result
    echo json_encode($functions->edit_collection($id_coleccion, $nombre_coleccion, $numero_coleccion, $volumenes, $id_seccion));
}
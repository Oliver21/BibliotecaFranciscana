<?php

# Access the operation to do with a magazine
if (isset($_POST['action'])) {
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action) {
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
        case 4:
            add_magazine($functions);
            break;
        case 5:
            delete_magazine($functions);
            break;
        default:
            echo json_encode(array("status" => 0, "message" => "Acción no válida."));
    }

} else {
    echo json_encode(array("status" => 666, "message" => "No se recibió acción a realizar."));
}

# get_magazines
/**
 * @param $functions
 */
function get_magazines($functions)
{
    $result = $functions->get_magazines();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_magazine
/**
 * @param $functions
 */
function get_specific_magazine($functions)
{
    $id = $_POST['id'];

    if ( isset($id) && $id != "" ){
        echo json_encode( $functions->get_specific_magazine($id) );
    } else {
        echo json_encode( array("status" => 599, "message" => "No se recibió el identificador.") );
    }
}

# edit_magazine
/**
 * @param $functions
 */
function edit_magazine($functions)
{
    /*
    id_revista
    id_seccion
    id_editorial
    nombre_revista
    periodicidad
    palabras_clave
    notas_adicionales
    */
    $id_revista = $_POST['id_revista'];
    $id_seccion = $_POST['id_seccion'];
    $id_editorial = $_POST['id_editorial'];
    $nombre_revista = $_POST['nombre_revista'];
    $periodicidad = $_POST['periodicidad'];
    $palabras_clave = $_POST['palabras_clave'];
    $notas_adicionales = $_POST['notas_adicionales'];

    echo json_encode($functions->edit_magazine($id_revista, $id_seccion, $id_editorial, $nombre_revista, $periodicidad, $palabras_clave, $notas_adicionales));
}

function add_magazine($functions)
{
    $id_seccion = $_POST['id_seccion'];
    $id_editorial = $_POST['id_editorial'];
    $nombre_revista = $_POST['nombre_revista'];
    $periodicidad = $_POST['periodicidad'];
    $palabras_clave = $_POST['palabras_clave'];
    $notas_adicionales = $_POST['notas_adicionales'];

    if ( isset($nombre_revista) && $nombre_revista != ""){
        echo json_encode($functions->add_magazine($id_seccion, $id_editorial, $nombre_revista, $periodicidad, $palabras_clave, $notas_adicionales));
    } else {
        echo json_encode(array("status" => 0, "message" => "No se recibió el nombre de la revista."));
    }
}

function delete_magazine($functions)
{
    $id_revista = $_POST['id_revista'];

    echo json_encode($functions->delete_magazine($id_revista));
}
<?php

# Access the operation to do with a subapartado
if (isset($_POST['action'])) {
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action) {
        # Select the list of subapartados
        case 1:
            get_subapartados($functions);
            break;
        case 2:
            get_specific_subapartado($functions);
            break;
        case 3:
            edit_subapartado($functions);
            break;
        case 4:
            add_subapartado($functions);
            break;
        case 5:
            delete_subapartado($functions);
            break;
        default:
            echo json_encode(array("status" => 600, "message" => "Acción no válida."));
    }

} else {
    echo json_encode(array("status" => 666, "message" => "No se recibió acción a realizar."));
}

# delete subapartado
/**
 * @param $functions
 */
function delete_subapartado($functions)
{
    /*
     * progresivo
     * id_apartado
     * id_seccion
     * nombre_subapartado
     */
    if (isset($_POST['progresivo'])) {
        $progresivo = $_POST['progresivo'];
        # Display the result
        echo json_encode($functions->delete_subapartado($progresivo));
    } else {
        echo json_encode(array("status" => 0, "message" => "No se recibió el subapartado a borrar."));
    }


}

# add_subapartado
/**
 * @param $functions
 */
function add_subapartado($functions)
{
    $nombre_subapartado = $_POST['nombre_subapartado'];
    if (isset($nombre_subapartado) && $nombre_subapartado != "") {
        $id_apartado = $_POST['id_apartado'];
        $id_seccion = $_POST['id_seccion'];
        $nombre_subapartado = $_POST['nombre_subapartado'];
        echo json_encode($functions->add_subapartado($id_apartado, $id_seccion, $nombre_subapartado));
    } else {
        echo json_encode(array("status" => 601, "message" => "No se recibió información necesaria del subapartado."));
    }
}

# get_subapartados
/**
 * @param $functions
 */
function get_subapartados($functions)
{
    $result = $functions->get_subapartados();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_subapartado
/**
 * @param $functions
 */
function get_specific_subapartado($functions)
{
    $id = $_POST['id'];
    $id_apartado = $_POST['id_apartado'];

    if ( isset($id) && $id != "" ){
        echo json_encode( $functions->get_specific_subapartado($id, $id_apartado) );
    } else {
        echo json_encode( array("status" => 599, "message" => "No se recibió el identificador.") );
    }
}

# edit_subapartado
/**
 * @param $functions
 */
function edit_subapartado($functions)
{
    $progresivo = $_POST['progresivo'];
    $id_apartado = $_POST['id_apartado'];
    $id_seccion = $_POST['id_seccion'];
    $nombre_subapartado = $_POST['nombre_subapartado'];

    # Display the result
    echo json_encode($functions->edit_subapartado( $progresivo, $id_apartado, $id_seccion, $nombre_subapartado ));
}
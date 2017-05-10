<?php

# Access the operation to do with a apartado
if (isset($_POST['action'])) {
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action) {
        # Select the list of apartados
        case 1:
            get_apartados($functions);
            break;
        case 2:
            get_specific_apartado($functions);
            break;
        case 3:
            edit_apartado($functions);
            break;
        case 4:
            add_apartado($functions);
            break;
        case 5:
            delete_apartado($functions);
            break;
        default:
            echo json_encode(array("status" => 600, "message" => "Acción no válida."));
    }

} else {
    echo json_encode(array("status" => 666, "message" => "No se recibió acción a realizar."));
}

# delete apartado
/**
 * @param $functions
 */
function delete_apartado($functions)
{


    if (isset($_POST['id_apartado'])) {
        $id_apartado = $_POST['id_apartado'];
        # Display the result
        echo json_encode($functions->delete_apartado($id_apartado));
    } else {
        echo json_encode(array("status" => 0, "message" => "No se recibió el apartado a borrar."));
    }


}

# add_apartado
/*
* id_apartado
* id_seccion
* nombre_apartado
 *
 */
/**
 * @param $functions
 */
function add_apartado($functions)
{
    $nombre_apartado = $_POST['nombre_apartado'];
    if (isset( $nombre_apartado ) && $nombre_apartado != "" ) {
        # $nombre_apartado = $_POST['nombre_apartado'];
        $id_apartado = $_POST['id_apartado'];
        $id_seccion = $_POST['id_seccion'];
        echo json_encode($functions->add_apartado($id_apartado, $id_seccion, $nombre_apartado));
    } else {
        echo json_encode(array("status" => 601, "message" => "No se recibió información necesaria del apartado."));
    }
}

# get_apartados
/**
 * @param $functions
 */
function get_apartados($functions)
{
    $result = $functions->get_apartados();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_apartado
/**
 * @param $functions
 */
function get_specific_apartado($functions)
{
    $id = $_POST['id'];

    if ( isset($id) && $id != "" ){
        echo json_encode( $functions->get_specific_apartado($id) );
    } else {
        echo json_encode( array("status" => 599, "message" => "No se recibió el identificador.") );
    }
}

# edit_apartado
/**
 * @param $functions
 */
function edit_apartado($functions)
{
    $id_apartado = $_POST['id_apartado'];
    $id_seccion = $_POST['id_seccion'];
    $nombre_apartado = $_POST['nombre_apartado'];

    # Display the result
    echo json_encode($functions->edit_apartado( $id_apartado, $id_seccion, $nombre_apartado ));
}
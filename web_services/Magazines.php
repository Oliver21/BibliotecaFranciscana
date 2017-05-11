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
        case 6:
            add_magazine_exampler($functions);
            break;
        case 11:
            get_page_number($functions);
            break;
        default:
            echo json_encode(array("status" => 0, "message" => "Acción no válida."));
    }
} else {
    echo json_encode(array("status" => 666, "message" => "No se recibió acción a realizar."));
}

/**
 * @param $functions
 */
function add_magazine_exampler($functions){
    $id_revista = $_POST['id_revista'];
    $id_ejemplar = $_POST['id_ejemplar'];

    if ( $id_revista != "" && $id_ejemplar != "" ) {
        # Display the result of the insert
        echo json_encode( $functions->add_magazine_exampler($id_revista, $id_ejemplar) );
    } else {
        echo json_encode(array("status" => 600, "message" => "No se información suficiente para la asociación Revista - Ejemplar."));
    }

}

# get_magazines
/**
 * @param $functions
 */
function get_magazines($functions)
{
    $result = $functions->get_magazines();
    $page = $_POST['page'] - 1;

    $first_index = $page * 10;
    $last_index = $first_index + 10;

    # var_dump($first_index);
    # var_dump($last_index);
    $selected = array();
    for ($i = $first_index; $i < $last_index; $i++){
        array_push($selected, $result[$i]);
    }
    # Display the result whatever its status is
    echo json_encode($selected);
}


function get_page_number($functions){
    $result = $functions->get_magazines();

    echo json_encode( array( "pages" =>  ceil ( count ($result) / 10 ) ));
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

/**
 * @param $functions
 */
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

/**
 * @param $functions
 */
function delete_magazine($functions)
{
    $id_revista = $_POST['id_revista'];

    echo json_encode($functions->delete_magazine($id_revista));
}
<?php

# Access the operation to do with a exampler
if (isset($_POST['action'])) {
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action) {
        # Select the list of examplers
        case 1:
            get_examplers($functions);
            break;
        case 2:
            get_specific_exampler($functions);
            break;
        case 3:
            edit_exampler($functions);
            break;
        case 4:
            add_exampler($functions);
            break;
        case 5:
            delete_exampler($functions);
            break;
        default:
            echo json_encode(array("status" => 0, "message" => "Acción no válida."));
    }

} else {
    echo json_encode(array("status" => 666, "message" => "No se recibió acción a realizar."));
}

# get_examplers
/**
 * @param $functions
 */
function get_examplers($functions)
{
    $result = $functions->get_examplers();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_exampler
/**
 * @param $functions
 */
function get_specific_exampler($functions)
{
    $id = $_POST['id'];

    if ( isset($id) && $id != "" ){
        echo json_encode( $functions->get_specific_exampler($id) );
    } else {
        echo json_encode( array("status" => 599, "message" => "No se recibió el identificador.") );
    }
}

# edit_exampler
/**
 * @param $functions
 */
function edit_exampler($functions)
{
    /*
  * id_datosrevista
  * ISSN
  * numero_ejemplar
  * precio_revista
  * año_revista
  * mes_revista
  * semana_revista
  */
    $id_datosrevista = $_POST['id_datosrevista'];
    $ISSN = $_POST['ISSN'];
    $numero_ejemplar = $_POST['numero_ejemplar'];
    $precio_revista = $_POST['precio_revista'];
    $año_revista = $_POST['año_revista'];
    $mes_revista = $_POST['mes_revistsa'];
    $semana_revista = $_POST['semana_revista'];

    echo json_encode($functions->edit_exampler($id_datosrevista, $ISSN, $numero_ejemplar, $precio_revista, $año_revista, $mes_revista, $semana_revista));
}

/**
 * @param $functions
 */
function add_exampler($functions)
{
    $ISSN = $_POST['ISSN'];
    $numero_ejemplar = $_POST['numero_ejemplar'];
    $precio_revista = $_POST['precio_revista'];
    $año_revista = $_POST['año_revista'];
    $mes_revista = $_POST['mes_revistsa'];
    $semana_revista = $_POST['semana_revista'];

    if (isset($numero_ejemplar) && $numero_ejemplar != "") {
        echo json_encode($functions->add_exampler($ISSN, $numero_ejemplar, $precio_revista, $año_revista, $mes_revista, $semana_revista));
    } else {
        echo json_encode(array("status" => 0, "message" => "No se recibió el nombre de la revista."));
    }
}

/**
 * @param $functions
 */
function delete_exampler($functions)
{
    $id_datosrevista = $_POST['id_datosrevista'];

    echo json_encode($functions->delete_exampler($id_datosrevista));
}
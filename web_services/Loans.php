<?php

# Access the operation to do with a loan
if (isset($_POST['action'])) {
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action) {
        # Select the list of loans
        case 1:
            get_loans($functions);
            break;
        case 2:
            get_specific_loan($functions);
            break;
        case 3:
            edit_loan($functions);
            break;
        case 4:
            add_loan($functions);
            break;
        case 5:
            delete_loan($functions);
            break;
        default:
            echo json_encode(array("status" => 600, "message" => "Acción no válida."));
    }

} else {
    echo json_encode(array("status" => 666, "message" => "No se recibió acción a realizar."));
}

# delete loan
/**
 * @param $functions
 */
function delete_loan($functions)
{
    if (isset($_POST['id_prestamo'])) {
        $id_prestamo = $_POST['id_prestamo'];
        # Display the result
        echo json_encode($functions->delete_loan($id_prestamo));
    } else {
        echo json_encode(array("status" => 0, "message" => "No se recibió el préstamo a borrar."));
    }
}

# add_loan
/**
 * @param $functions
 */
function add_loan($functions)
{
    /*
     * id_prestamo
     * id_libro
     * id_usuario
     * fecha_prestamo
     * fecha_vencimiento
     * fecha_devolucion
     */
    $id_libro = $_POST['id_libro'];
    if (isset($id_libro) && $id_libro != "" ) {
        $id_libro = $_POST['id_libro'];
        $id_usuario = $_POST['id_usuario'];
        $fecha_prestamo = $_POST['frecha_prestamo'];
        $fecha_vencimiento = $_POST['fecha_vencimiento'];
        $fecha_devolucion = $_POST['fecha_devolucion'];
        echo json_encode($functions->add_loan($id_libro, $id_usuario, $fecha_prestamo, $fecha_vencimiento, $fecha_devolucion));
    } else {
        echo json_encode(array("status" => 601, "message" => "No se recibió información necesaria del préstamo."));
    }
}

# get_loans
/**
 * @param $functions
 */
function get_loans($functions)
{
    $result = $functions->get_loans();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_loan
/**
 * @param $functions
 */
function get_specific_loan($functions)
{
    $id = $_POST['id'];

    if ( isset($id) && $id != "" ){
        echo json_encode( $functions->get_specific_loan($id) );
    } else {
        echo json_encode( array("status" => 599, "message" => "No se recibió el identificador.") );
    }
}

# edit_loan
/**
 * @param $functions
 */
function edit_loan($functions)
{
    $id_prestamo = $_POST['id_prestamo'];
    $id_libro = $_POST['id_libro'];
    $id_usuario = $_POST['id_usuario'];
    $fecha_prestamo = $_POST['fecha_prestamo'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];
    $fecha_devolucion = $_POST['fecha_devolucion'];

    # Display the result
    echo json_encode($functions->edit_loan( $id_prestamo, $id_libro, $id_usuario, $fecha_prestamo, $fecha_vencimiento, $fecha_devolucion ));
}
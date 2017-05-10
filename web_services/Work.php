<?php
# Access the operation to do with a work
if (isset($_POST['action'])) {
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action) {
        # Select the list of works
        case 1:
            get_works($functions);
            break;
        case 2:
            get_specific_work($functions);
            break;
        case 3:
            edit_work($functions);
            break;
        case 4:
            add_work($functions);
            break;
        case 5:
            delete_work($functions);
            break;
        default:
            echo json_encode(array("status" => 600, "message" => "Acción no válida."));
    }

} else {
    echo json_encode(array("status" => 666, "message" => "No se recibió acción a realizar."));
}

# delete work
function delete_work($functions)
{


    if (isset($_POST['id_obra'])) {
        $id_obra = $_POST['id_obra'];
        # Display the result
        echo json_encode($functions->delete_work($id_obra));
    } else {
        echo json_encode(array("status" => 0, "message" => "No se recibió la obra a borrar."));
    }


}

# add_work
function add_work($functions)
{
    /*
     * id_obra
     * nombre_obra
     * numero_tomo
     */
    $work = $_POST['nombre_obra'];
    if (isset($work) && $work != "" ) {
        $nombre_obra = $_POST['nombre_obra'];
        $numero_tomo = $_POST['numero_tomo'];
        echo json_encode($functions->add_work($nombre_obra, $numero_tomo));
    } else {
        echo json_encode(array("status" => 601, "message" => "No se recibió información necesaria de la obra."));
    }
}

# get_works
function get_works($functions)
{
    $result = $functions->get_works();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_work
/**
 * @param $functions
 */
function get_specific_work($functions)
{
    $id = $_POST['id'];

    if ( isset($id) && $id != "" ){
        echo json_encode( $functions->get_specific_work($id) );
    } else {
        echo json_encode( array("status" => 599, "message" => "No se recibió el identificador.") );
    }
}

# edit_work
function edit_work($functions)
{
    $id_obra = $_POST['numero_tomo'];
    $nombre_obra = $_POST['numero_tomo'];
    $numero_tomo = $_POST['numero_tomo'];

    # Display the result
    echo json_encode($functions->edit_work($id_obra, $nombre_obra, $numero_tomo));
}
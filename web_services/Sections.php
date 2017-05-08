<?php

# Access the operation to do with a section
if (isset($_POST['action'])) {
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action) {
        # Select the list of sections
        case 1:
            get_sections($functions);
            break;
        case 2:
            get_specific_section($functions);
            break;
        case 3:
            edit_section($functions);
            break;
        case 4:
            add_section($functions);
            break;
        case 5:
            delete_section($functions);
            break;
        default:
            echo json_encode(array("status" => 600, "message" => "Acción no válida."));
    }

} else {
    echo json_encode(array("status" => 666, "message" => "No se recibió acción a realizar."));
}

# delete section
/**
 * @param $functions
 */
function delete_section($functions)
{


    if (isset($_POST['id_seccion'])) {
        $id_section = $_POST['id_section'];
        # Display the result
        echo json_encode($functions->delete_section($id_section));
    } else {
        echo json_encode(array("status" => 0, "message" => "No se recibió la sección a borrar."));
    }
}

# add_section
/**
 * @param $functions
 */
function add_section($functions)
{
    # nombre_seccion
    if (!isset($_POST['nombre_seccion'])) {
        echo json_encode(array("status" => 601, "message" => "No se recibió la sección."));
    } else {
        $nombre_seccion = $_POST['nombre_seccion'];
        echo json_encode($functions->add_section($nombre_seccion));
    }
}

# get_sections
/**
 * @param $functions
 */
function get_sections($functions)
{
    $result = $functions->get_sections();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_section
/**
 * @param $functions
 */
function get_specific_section($functions)
{

}

# edit_section
/**
 * @param $functions
 */
function edit_section($functions)
{
    $id_seccion = $_POST['id_seccion'];
    $nombre_seccion = $_POST['nombre_seccion'];

    # Display the result
    echo json_encode($functions->edit_section($id_seccion, $nombre_seccion));
}
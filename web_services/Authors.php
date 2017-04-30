<?php

# Access the operation to do with a author
if (isset($_POST['action'])) {
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action) {
        # Select the list of authors
        case 1:
            get_authors($functions);
            break;
        case 2:
            get_specific_author($functions);
            break;
        case 3:
            edit_author($functions);
            break;
        case 4:
            add_author($functions);
            break;
        case 5:
            delete_author($functions);
            break;
        default:
            echo json_encode(array("status" => 0, "message" => "Acción no válida."));
    }

} else {
    echo json_encode(array("status" => 666, "message" => "No se recibió acción a realizar."));
}

# get_authors
/**
 * @param $functions
 */
function get_authors($functions)
{
    $result = $functions->get_authors();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_author
/**
 * @param $functions
 */
function get_specific_author($functions)
{

}

# edit_author
/**
 * @param $functions
 */
function edit_author($functions)
{
    /*
    * id_autor
    * apaterno_autor
    * amaterno_autor
    * nombre_autor
    * nacimiento_autor
    * nacionalidad_autor
     */
    $id_autor = $_POST['id_autor'];
    $apaterno_autor = $_POST['apaterno_autor'];
    $amaterno_autor = $_POST['amaterno_autor'];
    $nombre_autor = $_POST['nombre_autor'];
    $nacimiento_autor = $_POST['nacimiento_autor'];
    $nacionalidad_autor = $_POST['nacionalidad_autor'];

    echo json_encode($functions->edit_author($id_autor, $apaterno_autor, $amaterno_autor, $nombre_autor, $nacimiento_autor, $nacionalidad_autor));
}

function add_author($functions)
{
    $apaterno_autor = $_POST['apaterno_autor'];
    $amaterno_autor = $_POST['amaterno_autor'];
    $nombre_autor = $_POST['nombre_autor'];
    $nacimiento_autor = $_POST['nacimiento_autor'];
    $nacionalidad_autor = $_POST['nacionalidad_autor'];

    echo json_encode($functions->add_author($apaterno_autor, $amaterno_autor, $nombre_autor, $nacimiento_autor, $nacionalidad_autor));
}

function delete_author($functions)
{
    $id_autor = $_POST['id_autor'];

    echo json_encode($functions->delete_author($id_autor));
}
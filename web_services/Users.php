<?php

# Access the operation of the user
if (isset($_POST['action'])){
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action){
        # Add a user to the table
        case 1:
            add_user($functions);
            break;
    }

} else {
    echo json_encode( array("status" => 666, "message" => "No se recibió acción a realizar.") );
}

function check_add_user($keyword){
    # Check if the values were provided
    if (isset($_POST[$keyword])){
        # Extract the data
        return $_POST[$keyword];
    } else {
        # Status 3 indicates missing data
        die (json_encode( array("status" => 3, "message" => "No se encuentra $keyword.") ));
    }
}

function check_add_user_null($keyword){
    # The value can be null
    if (isset($_POST[$keyword])){
        # Extract the data
        return $_POST[$keyword];
    } else {
        return NULL;
    }
}

function add_user($functions){
    # Check if the values were provided
    $Nombre = check_add_user('Nombre');
    $Ap_Paterno = check_add_user('Ap_Paterno');
    $Ap_Materno = check_add_user('Ap_Materno');
    $Username = check_add_user('Username');
    $Tipo_Usuario = check_add_user('Tipo_Usuario');
    $Contrasena = check_add_user('Contrasena');
    $Grado = check_add_user_null('Grado');
    $Telefono = check_add_user_null('Telefono');
    $Correo = check_add_user_null('Correo');
    $Direccion = check_add_user_null('Direccion');
    $Instituto_Proveniencia = check_add_user_null('Instituto_Proveniencia');

    # Return to the front-end the answer of the call
    echo json_encode( $functions->add_user($Nombre, $Ap_Paterno, $Ap_Materno, $Username, $Tipo_Usuario, $Grado, $Telefono, $Correo, $Direccion, $Instituto_Proveniencia, $Contrasena) );
}
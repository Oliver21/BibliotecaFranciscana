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
        case 2:
            check_user($functions);
            break;
        case 3:
            close_session($functions);
            break;
    }

} else {
    echo json_encode( array("status" => 666, "message" => "No se recibió acción a realizar.") );
}

function close_session($functions){
    $functions->close_session();
}

function check_user_param($keyword){
    # Check if the values were provided
    if (isset($_POST[$keyword])){
        # Extract the data
        return $_POST[$keyword];
    } else {
        # Status 3 indicates missing data
        die (json_encode( array("status" => 3, "message" => "No se encuentra $keyword.") ));
    }
}

function check_user_null($keyword){
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
    $Nombre = check_user_param('Nombre');
    $Ap_Paterno = check_user_param('Ap_Paterno');
    $Ap_Materno = check_user_param('Ap_Materno');
    $Username = check_user_param('Username');
    $Tipo_Usuario = check_user_param('Tipo_Usuario');
    $Contrasena = check_user_param('Contrasena');
    $Grado = check_user_null('Grado');
    $Telefono = check_user_null('Telefono');
    $Correo = check_user_null('Correo');
    $Direccion = check_user_null('Direccion');
    $Instituto_Proveniencia = check_user_null('Instituto_Proveniencia');

    # Return to the front-end the answer of the call
    echo json_encode( $functions->add_user($Nombre, $Ap_Paterno, $Ap_Materno, $Username, $Tipo_Usuario, $Grado, $Telefono, $Correo, $Direccion, $Instituto_Proveniencia, $Contrasena) );
}

function check_user($functions){
    # Check if the values were provided
    $Username = check_user_param('Username');
    $Contrasena = check_user_param('Contrasena');
    
    # Return to the front-end the answer of the call
    $result = $functions->check_user($Username, $Contrasena);

    # Check the status value
    switch ($result['status']){
        case 1:
            # Create session
            session_start();
            # Save session variables
            $_SESSION = $result;
            # Response to the front-end 
            echo json_encode( array("status" => 1, "message" => "Inicio exitoso.", "Nombre" => $result['Nombre'] ) );
            break;
        case 2:
            # Response to the front-end 
            echo json_encode( array("status" => 2, "message" => "Usuario no encontrado.") );
            break;
        case 0;
            # Response to the front-end 
            echo json_encode( array("status" => 0, "message" => "Falló la búsqueda del usuario.") );
            break;
    }
}
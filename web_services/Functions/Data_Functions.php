<?php

class Functions{
    # Global connection to the database
    private $db;

    # Constructor
    function __construct()
    {
        require_once 'Db_Connection.php';
        # Create a new instance of the connection
        $this->conn = new Connection();
        $this->db = $this->conn->connect();
    }

    # Destructor
    function __destruct()
    {
    }
    
    # Destroy a user session
    function close_session(){
        # Start session for last time
        session_start();
        # Unset all the session variables
        $_SESSION = array();
        # Destroy the session
        session_destroy();
    }

    # Add a user to the 'Usuario' table
    function add_user($Nombre, $Ap_Paterno, $Ap_Materno, $Username, $Tipo_Usuario, $Grado, $Telefono, $Correo, $Direccion, $Instituto_Proveniencia, $Contrasena){
        # Check that the username does not exist
        $query = "SELECT 1 FROM Usuario WHERE Username = '$Username'";
        if ($result = mysqli_query($this->db, $query)){
            # Check the length of the answer
            if ($result -> num_rows > 0){
                # Username already exists
                return ( array("status" => 2, "message" => "El username ya existe."));
            } else {
                # Add the user
                # Query to insert the new user to the database
                $query = "INSERT INTO Usuario (Nombre, Ap_Paterno, Ap_Materno, Username, Tipo_Usuario, Grado, Telefono, Correo, Direccion, Instituto_Proveniencia, Contrasena) VALUES ('$Nombre', '$Ap_Paterno', '$Ap_Materno', '$Username', '$Tipo_Usuario', '$Grado', '$Telefono', '$Correo', '$Direccion', '$Instituto_Proveniencia', md5('$Contrasena'))";

                # Execute the query
                if ($result = mysqli_query($this->db, $query)){
                    # If the user was successfully added
                    return ( array("status" => 1, "message" => "Usuario añadido correctamente.") );
                } else {
                    # If something went wrong
                    return ( array("status" => 0, "message" => "No es posible agregar al usuario."));
                }
            }
        } else {
            # If something went wrong
            return ( array("status" => 0, "message" => "Algo salió mal al validar el usuario."));
        }
    }

    # Check if the user exist in the table
    function check_user($Username, $Contrasena){
        # Query that checks if the user is in the database
        $query = "SELECT * FROM Usuario WHERE Username = '$Username' AND Contrasena = md5('$Contrasena')";
        
        # Execute query
        if ($result = mysqli_query($this->db, $query)){
            if ($result -> num_rows < 1){
                # Invalid credentials or user not exists
                return ( array("status" => 2, "message" => "No se ha encontrado usuario con estas credenciales."));
            } else {
                $results = $result->fetch_array(MYSQLI_ASSOC);
                
                $results['status'] = 1;
                return ( $results);
            }
        } else {
            # If something went wrong
            return ( array("status" => 0, "message" => "Algo salió mal al buscar el usuario."));
        }
    }

}

# Test de añadir usuario
# $functions = new Functions();
# $functions->add_user("Test04", "TestAP", "TestAM", "test04", "Admin", "0", "8111628532", "test01@test.com", "Test Address", "Tec", "test");

# Test de buscar usuario
# $functions = new Functions();
# $functions->check_user('test04', 'test');

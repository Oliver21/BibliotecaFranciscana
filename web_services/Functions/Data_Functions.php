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
        // TODO: Implement __destruct() method.
    }

    # Add a user to the 'Usuario' table
    function add_user($Nombre, $Ap_Paterno, $Ap_Materno, $Username, $Tipo_Usuario, $Grado, $Telefono, $Correo, $Direccion, $Instituto_Proveniencia, $Contrasena){
        # Query to insert the new user to the database
        $query = "INSERT INTO Usuario (Nombre, Ap_Paterno, Ap_Materno, Username, Tipo_Usuario, Grado, Telefono, Correo, Direccion, Instituto_Proveniencia, Contrasena) VALUES ('$Nombre', '$Ap_Paterno', '$Ap_Materno', '$Username', '$Tipo_Usuario', '$Grado', '$Telefono', '$Correo', '$Direccion', '$Instituto_Proveniencia', md5('$Contrasena'))" or die(mysqli_error($this->db));

        # Execute the query
        if ($result = mysqli_query($this->db, $query)){
            # If the user was successfully added
            return array("status" => 1, "message" => "Usuario añadido correctamente.");
        } else {
            # If something went wrong
            return array("status" => 0, "message" => "Algo salió mal al intentar agregar al usuario.");
        }
    }
}

# $functions = new Functions();
# $functions->add_user("Test01", "TestAP", "TestAM", "test", "Admin", "0", "8111628532", "test01@test.com", "Test Address", "Tec", "test");
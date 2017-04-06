<?php

class Functions
{
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
    function close_session()
    {
        # Start session for last time
        session_start();
        # Unset all the session variables
        $_SESSION = array();
        # Destroy the session
        session_destroy();
    }

    # Get the list of series
    function get_series()
    {
        # Query that selects all the series from the table
        $query = "SELECT * FROM Serie";
        if ($result = mysqli_query($this->db, $query)) {
            # Check for the number of series
            if ($result->num_rows > 0) {
                $series = array();
                while ($row = mysqli_fetch_row($result)) {
                    $values = array("id_serie" => $row[0], "nombre_serie" => $row[1], "volumen_serie" => $row[2]);
                    array_push($series, $values);
                }
                $series['status'] = 1;
                return ($series);
            } else {
                # The authors table is empty
                return (array("status" => 2, "message" => "No se han agregado series."));
            }
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de las series."));
        }
    }

    # Get the list of authors
    function get_authors()
    {
        # Query that selects all the authors from the table
        $query = "SELECT * FROM Autor";
        if ($result = mysqli_query($this->db, $query)) {
            # Check for the number of magazines
            if ($result->num_rows > 0) {
                $authors = array();
                while ($row = mysqli_fetch_row($result)) {
                    array_push($authors, $row);
                }
                $authors['status'] = 1;
                return ($authors);
            } else {
                # The authors table is empty
                return (array("status" => 2, "message" => "No se han agregado autores."));
            }
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de los autores."));
        }
    }

    # Get all the magazines information
    function get_magazines()
    {
        # Query that selects all the magazines from the table
        $query = "SELECT * FROM Revista";
        if ($result = mysqli_query($this->db, $query)) {
            # Check for the number of magazines
            if ($result->num_rows > 0) {
                $magazines = array();
                while ($row = mysqli_fetch_row($result)) {
                    array_push($magazines, $row);
                }
                $magazines['status'] = 1;
                return ($magazines);
            } else {
                # There are no magazines in the table
                return (array("status" => 2, "message" => "No se han agregado revistas."));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de las revistas."));
        }
    }

    # Get all the books information
    function get_books()
    {
        # Query that selects all the books from the table
        $query = "SELECT * FROM Libro";
        if ($result = mysqli_query($this->db, $query)) {
            # Check for the number of books
            if ($result->num_rows > 0) {
                $books = array();
                while ($row = mysqli_fetch_row($result)) {
                    array_push($books, $row);
                }
                $answer = array("books" => $books, "status" => 1);
                # $books['status'] = 1;
                return ($answer);
            } else {
                # There are no books in the table
                return (array("status" => 2, "message" => "No se han agregado libros."));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de los libros."));
        }
    }

    # Add a user to the 'Usuario' table
    function add_user($Nombre, $Ap_Paterno, $Ap_Materno, $Username, $Tipo_Usuario, $Grado, $Telefono, $Correo, $Direccion, $Instituto_Proveniencia, $Contrasena)
    {
        # Check that the username does not exist
        $query = "SELECT 1 FROM Usuario WHERE Username = '$Username'";
        if ($result = mysqli_query($this->db, $query)) {
            # Check the length of the answer
            if ($result->num_rows > 0) {
                # Username already exists
                return (array("status" => 2, "message" => "El username ya existe."));
            } else {
                # Add the user
                # Query to insert the new user to the database
                $query = "INSERT INTO Usuario (Nombre, Ap_Paterno, Ap_Materno, Username, Tipo_Usuario, Grado, Telefono, Correo, Direccion, Instituto_Proveniencia, Contrasena) VALUES ('$Nombre', '$Ap_Paterno', '$Ap_Materno', '$Username', '$Tipo_Usuario', '$Grado', '$Telefono', '$Correo', '$Direccion', '$Instituto_Proveniencia', md5('$Contrasena'))";

                # Execute the query
                if ($result = mysqli_query($this->db, $query)) {
                    # If the user was successfully added
                    return (array("status" => 1, "message" => "Usuario añadido correctamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 0, "message" => "No es posible agregar al usuario."));
                }
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al validar el usuario."));
        }
    }

    # Check if the user exist in the table
    function check_user($Username, $Contrasena)
    {
        # Query that checks if the user is in the database
        $query = "SELECT * FROM Usuario WHERE Username = '$Username' AND Contrasena = md5('$Contrasena')";

        # Execute query
        if ($result = mysqli_query($this->db, $query)) {
            if ($result->num_rows < 1) {
                # Invalid credentials or user not exists
                return (array("status" => 2, "message" => "No se ha encontrado usuario con estas credenciales."));
            } else {
                $results = $result->fetch_array(MYSQLI_ASSOC);

                $results['status'] = 1;
                return ($results);
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al buscar el usuario."));
        }
    }

    # Add a book to the table
    function add_book($isbn, $titulo_libro, $subtitulo_libro, $titulo_original, $numero_paginas, $id_editorial, $numero_edicion,
                      $fecha_edicion, $lugar_publicacion, $fecha_adquisicion, $costo_libro, $proveedor_libro, $observaciones_libro, $id_seccion, $id_apartado,
                      $volumen_libro, $ilustraciones, $graficas, $mapas, $bibliografia, $indice, $pasta_blanda, $planos, $estatus, $numero_copias, $palabras_clave)
    {
        # Check that the book does not exist
        $query = "SELECT 1 FROM Libro WHERE ISBN = '$isbn'";
        if ( $result = mysqli_query($this->db, $query) ){

            # Check the length of the answer
            if ($result->num_rows > 0){
                # Book already exists
                return (array("status" => 2, "message" => "El libro ya existe."));
            } else {
                # Add the user
                # Query to insert the new book to the database
                $query = "INSERT INTO Libro (ISBN, titulo_libro, subtitulo_libro, titulo_original, numero_paginas, id_editorial, numero_edicion, fecha_edicion, lugar_publicacion, fecha_adquisicion, costo_libro, proveedor_libro, observaciones_libro, id_seccion, id_apartado, volumen_libro, ilustraciones, graficas, mapas, bibliografia, indice, pasta_blanda, planos, estatus, numero_copias, palabras_clave)"
                    .  " VALUES ('$isbn', '$titulo_libro', '$subtitulo_libro', '$titulo_original', $numero_paginas, $id_editorial, $numero_edicion, '$fecha_edicion', '$lugar_publicacion', '$fecha_adquisicion', '$costo_libro', '$proveedor_libro', '$observaciones_libro', $id_seccion, '$id_apartado', '$volumen_libro', $ilustraciones, $graficas, $mapas, $bibliografia, $indice, $pasta_blanda, $planos, $estatus, $numero_copias, '$palabras_clave') ";

                # Execute the query
                if ($result = mysqli_query($this->db, $query)) {
                    # If the user was successfully added
                    return (array("status" => 1, "message" => "Libro añadido correctamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 0, "message" => "No es posible agregar el libro."));
                }

            }

        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al validar el libro."));
        }
    }
}

# Test de añadir usuario
# $functions = new Functions();
# $functions->add_user("Test04", "TestAP", "TestAM", "test04", "Admin", "0", "8111628532", "test01@test.com", "Test Address", "Tec", "test");

# Test de buscar usuario
# $functions = new Functions();
# $functions->check_user('test04', 'test');

# Test de selección de libros
# $functions = new Functions();
# $functions->get_books();

# Test de selección de revistas
# $functions = new Functions();
# echo json_encode( $functions->get_magazines();

# Test de selección de autores
# $functions = new Functions();
# echo json_encode( $functions->get_authors());

# Test de inserción de libro
# $functions = new Functions();
# echo json_encode( $functions->add_book( "test", "Titulo test", "Subtitulo test", "titulo original test", 4, 1, 3, "06/04/2017", "Monterrey", "06/04/2017", "43dlls", "Proveedor", "observaciones", 3, "apartado", "volumen", 1,1, 1,1,1,1,1,1,3,"Test de add libros" ));
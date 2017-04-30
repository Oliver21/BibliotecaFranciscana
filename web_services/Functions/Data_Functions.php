<?php

/**
 * Class Functions
 */
class Functions
{


    //<editor-fold desc="Class functions">
    /**
     * @var mysqli
     */
    private $db;

    # Constructor
    /**
     * Functions constructor.
     */
    function __construct()
    {
        require_once 'Db_Connection.php';
        # Create a new instance of the connection
        $this->conn = new Connection();
        $this->db = $this->conn->connect();
    }



    # Destructor
    /**
     *
     */
    function __destruct()
    {
    }
    //</editor-fold>

    //<editor-fold desc="User functions">
    # Destroy a user session
    /**
     *
     */
    function close_session()
    {
        # Start session for last time
        session_start();
        # Unset all the session variables
        $_SESSION = array();
        # Destroy the session
        session_destroy();
    }

    # Check if the user exist in the table
    /**
     * @param $Username
     * @param $Contrasena
     * @return array|mixed
     */
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

    # Add a user to the 'Usuario' table
    /**
     * @param $Nombre
     * @param $Ap_Paterno
     * @param $Ap_Materno
     * @param $Username
     * @param $Tipo_Usuario
     * @param $Grado
     * @param $Telefono
     * @param $Correo
     * @param $Direccion
     * @param $Instituto_Proveniencia
     * @param $Contrasena
     * @return array
     */
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

    # Edit user information
    /**
     * @param $Nombre
     * @param $Ap_Paterno
     * @param $Ap_Materno
     * @param $Username
     * @param $Tipo_Usuario
     * @param $Grado
     * @param $Telefono
     * @param $Correo
     * @param $Direccion
     * @param $Instituto_Proveniencia
     * @param $Contrasena
     * @return array
     */
    function edit_user($Id_Usuario, $Nombre, $Ap_Paterno, $Ap_Materno, $Username, $Tipo_Usuario, $Grado, $Telefono, $Correo, $Direccion, $Instituto_Proveniencia, $Contrasena){
        # Check that the user exists
        $query_validate = "SELECT * FROM Usuario WHERE Id_Usuario = $Id_Usuario";

        # Execute the validation query
        if ( $result = mysqli_query($this->db, $query_validate) ){
            if ( $result->num_rows > 0 ){
                # Update the user
                $query_update = "UPDATE Usuario SET Nombre = '$Nombre', Ap_Paterno = '$Ap_Paterno', Ap_Materno = '$Ap_Materno', Username = '$Username' ,Tipo_Usuario = '$Tipo_Usuario', Grado = '$Grado', Telefono = '$Telefono', Correo = '$Correo', Direccion = '$Direccion', Instituto_Proveniencia = '$Instituto_Proveniencia', Contrasena = '$Contrasena' WHERE Id_Usuario = $Id_Usuario";

                # Execute the update query

                if ( $result = mysqli_query($this->db, $query_update) ){
                    # Everything was ok
                    return (array("status" => 1, "message" => "Usuario editado exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información del usuario"));
                }
            } else {
                # Users does not exists
                return (array("status" => 101, "message" => "El usuario no se encuentra en la base de datos: " . $Id_Usuario . " ." . mysqli_error($this->db) ));
            }
            
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Editar usuario: Algo salió mal al validar el usuario."));
        }


    }

    # Delete user
    /**
     * @param $Username
     * @return array
     */
    function delete_user($Username){
        # Check that the user actually exists
        $query_validate = "SELECT 1 FROM Usuario WHERE Username = '$Username'";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)){
            # Check the number of results
            if ($result->num_rows < 1 ){
                # The username does not exists
                return (array("status" => 100, "message" => "No existe el usuario a eliminar."));
            } else {
                # Delete the user from the database
                # Query to eliminate the user
                $query_delete = "DELETE FROM Usuario WHERE Username = '$Username'";

                # Execute the deletion
                if ( $result = mysqli_query($this->db, $query_delete) ){
                    # Se borró el usuario
                    return array("status" => 1, "message" => "Usuario eliminado exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar el usuario." . mysqli_error($this->db) ));
                }

            }

        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Borrar usuario: Algo salió mal al validar el usuario."));
        }
    }
    //</editor-fold>

    //<editor-fold desc="Book functions">
    # Get all the books information
    /**
     * @return array
     */
    function get_books()
    {
        # Query that selects all the books from the table
        $query = "SELECT * FROM Libro";
        if ($result = mysqli_query($this->db, $query)) {
            # Check for the number of books
            if ($result->num_rows > 0) {
                $books = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    array_push($books, $row);
                }
                $answer = array("books" => $books, "status" => 1);
                # var_dump($answer);
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

    # Select a specific book
    /**
     * @param $id
     * @return array|null
     */
    function get_specific_book($id){
        # Query that select the specific book
        $query = "SELECT * FROM Libro WHERE id_libro = $id";

        if ( $result = mysqli_query($this->db, $query) ){

            $row = mysqli_fetch_assoc ($result);

            return $row;


        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información del libro."));
        }

    }

    # Add a book to the table
    /**
     * @param $isbn
     * @param $titulo_libro
     * @param $subtitulo_libro
     * @param $titulo_original
     * @param $numero_paginas
     * @param $id_editorial
     * @param $numero_edicion
     * @param $fecha_edicion
     * @param $lugar_publicacion
     * @param $fecha_adquisicion
     * @param $costo_libro
     * @param $proveedor_libro
     * @param $observaciones_libro
     * @param $id_seccion
     * @param $id_apartado
     * @param $volumen_libro
     * @param $ilustraciones
     * @param $graficas
     * @param $mapas
     * @param $bibliografia
     * @param $indice
     * @param $pasta_blanda
     * @param $planos
     * @param $estatus
     * @param $numero_copias
     * @param $palabras_clave
     * @return array
     */
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

    # Edit books
    /**
     * @param $id_libro
     * @param $isbn
     * @param $titulo_libro
     * @param $subtitulo_libro
     * @param $titulo_original
     * @param $numero_paginas
     * @param $id_editorial
     * @param $numero_edicion
     * @param $fecha_edicion
     * @param $lugar_publicacion
     * @param $fecha_adquisicion
     * @param $costo_libro
     * @param $proveedor_libro
     * @param $observaciones_libro
     * @param $id_seccion
     * @param $id_apartado
     * @param $volumen_libro
     * @param $ilustraciones
     * @param $graficas
     * @param $mapas
     * @param $bibliografia
     * @param $indice
     * @param $pasta_blanda
     * @param $planos
     * @param $estatus
     * @param $numero_copias
     * @param $palabras_clave
     * @return array
     */
    function edit_book($id_libro, $isbn, $titulo_libro, $subtitulo_libro, $titulo_original, $numero_paginas, $id_editorial, $numero_edicion, $fecha_edicion, $lugar_publicacion, $fecha_adquisicion, $costo_libro, $proveedor_libro, $observaciones_libro, $id_seccion, $id_apartado, $volumen_libro, $ilustraciones, $graficas, $mapas, $bibliografia, $indice, $pasta_blanda, $planos, $estatus, $numero_copias, $palabras_clave ){
        # Check that the book exists
        $query_validate = "SELECT * FROM Libro WHERE id_libro = $id_libro";

        # Execute the validation query
        if ( $result = mysqli_query($this->db, $query_validate) ){
            if ( $result->num_rows > 0 ){
                # Update the user
                $query_update = "UPDATE Libro SET ISBN = '$isbn', titulo_libro = '$titulo_libro', subtitulo_libro = '$subtitulo_libro', titulo_original = '$titulo_original', numero_paginas = $numero_paginas, id_editorial = $id_editorial, numero_edicion = $numero_edicion, fecha_edicion = '$fecha_edicion', lugar_publicacion = '$lugar_publicacion', fecha_adquisicion = '$fecha_adquisicion', costo_libro = '$costo_libro', proveedor_libro = '$proveedor_libro', observaciones_libro = '$observaciones_libro', id_seccion = $id_seccion, id_apartado = '$id_apartado', volumen_libro = '$volumen_libro', ilustraciones = $ilustraciones, graficas = $graficas, mapas = $mapas, bibliografia = $bibliografia, indice = $indice, pasta_blanda = $pasta_blanda, planos = $planos, estatus = $estatus, numero_copias = $numero_copias, palabras_clave = '$palabras_clave' WHERE id_libro = $id_libro";

                # Execute the update query

                if ( $result = mysqli_query($this->db, $query_update) ){
                    # Everything was ok
                    return (array("status" => 1, "message" => "Libro editado exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información del libro"));
                }
            } else {
                # Book does not exists
                return (array("status" => 101, "message" => "El libro no se encuentra en la base de datos: " . $id_libro . " ." . mysqli_error($this->db) ));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Editar libro: Algo salió mal al validar el libro."));
        }
    }

    # Delete book
    /**
     * @param $id_libro
     * @return array
     */
    function delete_book($id_libro){
        # Check that the book actually exists
        $query_validate = "SELECT 1 FROM Libro WHERE id_libro = $id_libro";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)){
            # Check the number of results
            if ($result->num_rows < 1 ){
                # The book does not exists
                return (array("status" => 100, "message" => "No existe el libro a eliminar."));
            } else {
                # Delete the book from the database
                # Query to eliminate the book
                $query_delete = "DELETE FROM Libro WHERE id_libro = $id_libro";

                # Execute the deletion
                if ( $result = mysqli_query($this->db, $query_delete) ){
                    # Se borró el usuario
                    return array("status" => 1, "message" => "Libro eliminado exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar el libro." . mysqli_error($this->db) ));
                }
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Borrar libro: Algo salió mal al validar el libro."));
        }
    }


    //</editor-fold>

    //<editor-fold desc="Serie functions">
    # Get the list of series
    /**
     * @return array
     */
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


    # Insert a new serie
    /**
     * @param $nombre_serie
     * @param $volumen_serie
     * @return array
     */
    function insert_serie($nombre_serie, $volumen_serie){
        # nombre_serie
        # volumen_serie
        # Create the query
        $query = "INSERT INTO Serie (nombre_serie, volumen_serie) VALUES ('$nombre_serie', '$volumen_serie')";

        if ( $result = mysqli_query($this->db, $query) ){
            # Everything went right
            return (array("status" => 1, "message" => "La serie se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al insertar una serie."));
        }
    }

    # Edit a serie
    /**
     * @param $id_serie
     * @param $nombre_serie
     * @param $volumen_serie
     * @return array
     */
    function edit_serie($id_serie, $nombre_serie, $volumen_serie){
        # Check that the user exists
        $query_validate = "SELECT * FROM Serie WHERE id_serie = $id_serie";

        # Execute the validation query
        if ( $result = mysqli_query($this->db, $query_validate) ){
            if ( $result->num_rows > 0 ){
                # Update the serie
                $query_update = "UPDATE Serie SET nombre_serie = '$nombre_serie', volumen_serie = '$volumen_serie' WHERE id_serie = $id_serie";
                # Execute the update query
                if ( $result = mysqli_query($this->db, $query_update) ){
                    # Everything was ok
                    return (array("status" => 1, "message" => "Serie editada exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información de la serie"));
                }
            } else {
                # Serie does not exists
                return (array("status" => 101, "message" => "La serie no se encuentra en la base de datos: " . $id_serie . " ." . mysqli_error($this->db) ));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Editar serie: Algo salió mal al validar la serie."));
        }
    }

    /**
     * @param $id_serie
     * @return array
     */
    function delete_serie($id_serie){
        # Check that the user actually exists
        $query_validate = "SELECT 1 FROM Serie WHERE id_serie = '$id_serie'";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)){
            # Check the number of results
            if ($result->num_rows < 1 ){
                # The serie does not exists
                return (array("status" => 100, "message" => "No existe la serie a eliminar."));
            } else {
                # Delete the serie from the database
                # Query to eliminate the serie
                $query_delete = "DELETE FROM Serie WHERE id_serie = '$id_serie'";

                # Execute the deletion
                if ( $result = mysqli_query($this->db, $query_delete) ){
                    # the serie was deleted
                    return array("status" => 1, "message" => "Serie eliminada exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar la serie." . mysqli_error($this->db) ));
                }
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Borrar serie: Algo salió mal al validar la serie."));
        }
    }


    //</editor-fold>

    //<editor-fold desc="Author functions">
    # Get the list of authors
    function get_authors()
    {
        # Query that selects all the authors from the table
        $query = "SELECT * FROM Autor";
        if ($result = mysqli_query($this->db, $query)) {
            # Check for the number of magazines
            if ($result->num_rows > 0) {
                $authors = array();
                while ($row = mysqli_fetch_assoc($result)) {
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

    # Add authors
    /*
     * id_autor
     * apaterno_autor
     * amaterno_autor
     * nombre_autor
     * nacimiento_autor
     * nacionalidad_autor
     */
    function add_author($apaterno_autor, $amaterno_autor, $nombre_autor, $nacimiento_autor, $nacionalidad_autor){
        # Create the query
        $query = "INSERT INTO Autor (apaterno_autor, amaterno_autor, nombre_autor, nacimiento_autor, nacionalidad_autor) VALUES ( '$apaterno_autor', '$amaterno_autor', '$nombre_autor', '$nacimiento_autor', '$nacionalidad_autor' )";
        if ( $result = mysqli_query($this->db, $query) ){
            # Everything went right
            return (array("status" => 1, "message" => "El autor se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al insertar un autor."));
        }
    }

    # Edit an author
    function edit_author($id_autor, $apaterno_autor, $amaterno_autor, $nombre_autor, $nacimiento_autor, $nacionalidad_autor){
        # Check that the author exists
        $query_validate = "SELECT * FROM Autor WHERE id_autor = $id_autor";

        # Execute the validation query
        if ( $result = mysqli_query($this->db, $query_validate) ){
            if ( $result->num_rows > 0 ){
                # Update the author
                $query_update = "UPDATE Autor SET apaterno_autor = '$apaterno_autor', amaterno_autor = '$amaterno_autor', nombre_autor = '$nombre_autor', nacimiento_autor = '$nacimiento_autor', nacionalidad_autor = '$nacionalidad_autor' WHERE id_autor = $id_autor";
                # Execute the update query
                if ( $result = mysqli_query($this->db, $query_update) ){
                    # Everything was ok
                    return (array("status" => 1, "message" => "Autor editado exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información del autor"));
                }
            } else {
                # Serie does not exists
                return (array("status" => 101, "message" => "El autor no se encuentra en la base de datos: " . $id_autor . " ." . mysqli_error($this->db) ));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Editar autor: Algo salió mal al validar el autor."));
        }
    }

    function delete_autor($id_autor){
        # Check that the author actually exists
        $query_validate = "SELECT 1 FROM Autor WHERE id_autor = '$id_autor'";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)){
            # Check the number of results
            if ($result->num_rows < 1 ){
                # The author does not exists
                return (array("status" => 100, "message" => "No existe el autor a eliminar."));
            } else {
                # Delete the author from the database
                # Query to eliminate the author
                $query_delete = "DELETE FROM Autor WHERE id_autor = '$id_autor'";

                # Execute the deletion
                if ( $result = mysqli_query($this->db, $query_delete) ){
                    # the author was deleted
                    return array("status" => 1, "message" => "Autor eliminado exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar el autor." . mysqli_error($this->db) ));
                }
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Borrar autor: Algo salió mal al validar el autor."));
        }
    }

    //</editor-fold>

    //<editor-fold desc="Magazine functions">
    # Get all the magazines information
    /**
     * @return array
     */
    function get_magazines()
    {
        # Query that selects all the magazines from the table
        $query = "SELECT * FROM Revista";
        if ($result = mysqli_query($this->db, $query)) {
            # Check for the number of magazines
            if ($result->num_rows > 0) {
                $magazines = array();
                while ($row = mysqli_fetch_assoc($result)) {
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
    
    # Add magazine to the database
    /*
    id_revista
    id_seccion
    id_editorial
    nombre_revista
    periodicidad
    palabras_clave
    notas_adicionales
    */
    /**
     * @param $id_seccion
     * @param $id_editorial
     * @param $nombre_revista
     * @param $periodicidad
     * @param $palabras_clave
     * @param $notas_adicionales
     * @return array
     */
    function add_magazine($id_seccion, $id_editorial, $nombre_revista, $periodicidad, $palabras_clave, $notas_adicionales){
        $query = "INSERT INTO Revista (id_seccion, id_editorial, nombre_revista, periodicidad, palabras_clave, notas_adicionales) VALUES ($id_seccion, $id_editorial, '$nombre_revista', '$periodicidad', '$palabras_clave', '$notas_adicionales')";

        if ( $result = mysqli_query($this->db, $query) ){
            # Everything went right
            return (array("status" => 1, "message" => "La revista se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al insertar una revista."));
        }
    }
    
    # Edit magazine
    /**
     * @param $id_revista
     * @param $id_seccion
     * @param $id_editorial
     * @param $nombre_revista
     * @param $periodicidad
     * @param $palabras_clave
     * @param $notas_adicionales
     * @return array
     */
    function edit_magazine($id_revista, $id_seccion, $id_editorial, $nombre_revista, $periodicidad, $palabras_clave, $notas_adicionales ){
        # Check that the section exists
        $query_validate = "SELECT * FROM Revista WHERE id_revista = $id_revista";

        # Execute the validation query
        if ( $result = mysqli_query($this->db, $query_validate) ){
            if ( $result->num_rows > 0 ){
                # Update the section
                $query_update = "UPDATE Revista SET id_seccion = $id_seccion, id_editorial = $id_editorial, nombre_revista = '$nombre_revista', periodicidad = '$periodicidad', palabras_clave = '$palabras_clave', notas_adicionales = '$notas_adicionales' WHERE id_revista = $id_revista";
                # Execute the update query
                if ( $result = mysqli_query($this->db, $query_update) ){
                    # Everything was ok
                    return (array("status" => 1, "message" => "Revista editada exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información de la revista"));
                }
            } else {
                # Section does not exists
                return (array("status" => 101, "message" => "La revista no se encuentra en la base de datos: " . $id_revista . " ." . mysqli_error($this->db) ));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Editar revista: Algo salió mal al validar la revista."));
        }
    }
    
    # Delete magazine
    /**
     * @param $id_revista
     * @return array
     */
    function delete_magazine($id_revista){
        # Check that the magazine actually exists
        $query_validate = "SELECT 1 FROM Revista WHERE id_revista = '$id_revista'";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)){
            # Check the number of results
            if ($result->num_rows < 1 ){
                # The magazine does not exists
                return (array("status" => 100, "message" => "No existe la revista a eliminar."));
            } else {
                # Delete the magazine from the database
                # Query to eliminate the magazine
                $query_delete = "DELETE FROM Revista WHERE id_revista = '$id_revista'";

                # Execute the deletion
                if ( $result = mysqli_query($this->db, $query_delete) ){
                    # the magazine was deleted
                    return array("status" => 1, "message" => "Revista eliminada exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar la revista." . mysqli_error($this->db) ));
                }
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Borrar revista: Algo salió mal al validar la revista."));
        }
    }


    //</editor-fold>

    //<editor-fold desc="Section functions">
    # add a section to the database
    /**
     * @param $nombre_seccion
     * @return array
     */
    function add_section($nombre_seccion){
        # nombre_seccion
        # Create the query
        $query = "INSERT INTO Seccion (nombre_seccion) VALUES ('$nombre_seccion')";

        if ( $result = mysqli_query($this->db, $query) ){
            # Everything went right
            return (array("status" => 1, "message" => "La sección se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al insertar una sección."));
        }
    }

    # get sections
    /**
     * @return array
     */
    function get_sections()
    {
        # Query that selects all the sections from the table
        $query = "SELECT * FROM Seccion";
        if ($result = mysqli_query($this->db, $query)) {
            # Check for the number of sections
            if ($result->num_rows > 0) {
                $sections = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    array_push($sections, $row);
                }
                $answer = array("books" => $sections, "status" => 1);
                # var_dump($answer);
                # $books['status'] = 1;
                return ($answer);
            } else {
                # There are no sections in the table
                return (array("status" => 2, "message" => "No hay secciones.."));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de las secciones."));
        }
    }

    # edit a section
    /**
     * @param $id_seccion
     * @param $nombre_seccion
     * @return array
     */
    function edit_section($id_seccion, $nombre_seccion){
        # Check that the section exists
        $query_validate = "SELECT * FROM Seccion WHERE id_seccion = $id_seccion";

        # Execute the validation query
        if ( $result = mysqli_query($this->db, $query_validate) ){
            if ( $result->num_rows > 0 ){
                # Update the section
                $query_update = "UPDATE Seccion SET nombre_seccion = '$nombre_seccion' WHERE id_seccion = $id_seccion";
                # Execute the update query
                if ( $result = mysqli_query($this->db, $query_update) ){
                    # Everything was ok
                    return (array("status" => 1, "message" => "Sección editada exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información de la sección"));
                }
            } else {
                # Section does not exists
                return (array("status" => 101, "message" => "La sección no se encuentra en la base de datos: " . $id_seccion . " ." . mysqli_error($this->db) ));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Editar sección: Algo salió mal al validar la sección."));
        }
    }

    # delete a section
    /**
     * @param $id_seccion
     * @return array
     */
    function delete_section($id_seccion){
        # Check that the section actually exists
        $query_validate = "SELECT 1 FROM Seccion WHERE id_seccion = '$id_seccion'";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)){
            # Check the number of results
            if ($result->num_rows < 1 ){
                # The section does not exists
                return (array("status" => 100, "message" => "No existe la sección a eliminar."));
            } else {
                # Delete the section from the database
                # Query to eliminate the section
                $query_delete = "DELETE FROM Seccion WHERE id_seccion = '$id_seccion'";

                # Execute the deletion
                if ( $result = mysqli_query($this->db, $query_delete) ){
                    # the section was deleted
                    return array("status" => 1, "message" => "Sección eliminada exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar la sección." . mysqli_error($this->db) ));
                }
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Borrar sección: Algo salió mal al validar la sección."));
        }
    }

    //</editor-fold>

    //<editor-fold desc="Editorial functions">

    # Get all the editorial entries
    function get_editorial()
    {
        # Query that selects all the editorials from the table
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

    # Insert a new editorial
    function add_editorial($nombre_editorial, $nombre_direccion){
        # id_editorial
        # nombre_editorial
        # nombre_direccion

        # Create the query
        $query = "INSERT INTO Editorial (nombre_editorial, nombre_direccion) VALUES ('$nombre_editorial', '$nombre_direccion')";

        if ( $result = mysqli_query($this->db, $query) ){
            # Everything went right
            return (array("status" => 1, "message" => "La editorial se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al insertar la editorial."));
        }
    }

    # Edit a editorial
    function edit_editorial($id_editorial, $nombre_editorial, $nombre_direccion){
        # Check that the editorial exists
        $query_validate = "SELECT * FROM Editorial WHERE id_editorial = $id_editorial";

        # Execute the validation query
        if ( $result = mysqli_query($this->db, $query_validate) ){
            if ( $result->num_rows > 0 ){
                # Update the editorial
                $query_update = "UPDATE Editorial SET nombre_editorial = '$nombre_editorial', nombre_direccion = '$nombre_direccion' WHERE id_editorial = $id_editorial";
                # Execute the update query
                if ( $result = mysqli_query($this->db, $query_update) ){
                    # Everything was ok
                    return (array("status" => 1, "message" => "Editorial editada exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información de la editorial"));
                }
            } else {
                # Editorial does not exists
                return (array("status" => 101, "message" => "La editorial no se encuentra en la base de datos: " . $id_editorial . " ." . mysqli_error($this->db) ));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Editar editorial: Algo salió mal al validar la editorial."));
        }
    }

    # delete a editorial
    function delete_editorial($id_editorial){
        # Check that the editorial actually exists
        $query_validate = "SELECT 1 FROM Editorial WHERE id_editorial = $id_editorial";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)){
            # Check the number of results
            if ($result->num_rows < 1 ){
                # The editorial does not exists
                return (array("status" => 100, "message" => "No existe la editorial a eliminar."));
            } else {
                # Delete the editorial from the database
                # Query to eliminate the editorial
                $query_delete = "DELETE FROM Editorial WHERE id_editorial = $id_editorial";

                # Execute the deletion
                if ( $result = mysqli_query($this->db, $query_delete) ){
                    # the editorial was deleted
                    return array("status" => 1, "message" => "Editorial eliminada exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar la editorial." . mysqli_error($this->db) ));
                }
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Borrar editorial: Algo salió mal al validar la editorial."));
        }
    }

    //</editor-fold>

    /* Pendientes
     * Colección
     * Apartado
     * Obra
     * Prestamo
     * Subapartado
     * Articulo
     * Tema
     */
    //<editor-fold desc="Coleccion functions">
    
    //</editor-fold>

    //<editor-fold desc="Apartado functions">
    //</editor-fold>

    //<editor-fold desc="Obra functions">
    //</editor-fold>

    //<editor-fold desc="Prestamo functions">
    //</editor-fold>

    //<editor-fold desc="Subapartado functions">
    //</editor-fold>

    //<editor-fold desc="Articulo functions">
    //</editor-fold>

    //<editor-fold desc="Tema functions">
    //</editor-fold>
}

//<editor-fold desc="Tests">
# Test de añadir usuario
# $functions = new Functions();
# echo json_encode( $functions->add_user("Test04", "TestAP", "TestAM", "test", "Admin", "0", "8111628532", "test01@test.com", "Test Address", "Tec", "test") );

# Test de editar un usuario
# $functions = new Functions();
# echo json_encode( $functions->edit_user(44, "Test_Edit", "TestAP_Edit", "TestAM_Edit", "test_edit", "Admin", "0", "8111628532_E", "test01_edit@test.com", "Test Edit Address", "Tec", "test") );

# Test de buscar usuario
# $functions = new Functions();
# $functions->check_user('test04', 'test');

# Test de eliminar usuario
# $functions = new Functions();
# echo json_encode( $functions->delete_user('test') );

# Test de selección de libros
# $functions = new Functions();
# echo json_encode( $functions->get_books() );

# Test de inserción de libro
# $functions = new Functions();
# echo json_encode( $functions->add_book( "test", "Titulo test", "Subtitulo test", "titulo original test", 4, 1, 3, "06/04/2017", "Monterrey", "06/04/2017", "43dlls", "Proveedor", "observaciones", 3, "apartado", "volumen", 1,1, 1,1,1,1,1,1,3,"Test de add libros" ));

# Test de editar un libro
# $functions = new Functions();
# echo json_encode( $functions->edit_book(275, "test_edit", "Titulo editado test", "Subtitulo editado test", "titulo original test", 4, 1, 3, "06/04/2017", "Monterrey", "06/04/2017", "43dlls", "Proveedor", "observaciones", 3, "apartado", "volumen", 1,1, 1,1,1,1,1,1,3,"Test de edit libros" ) );

# Test de eliminar un libro
# $functions = new Functions();
# echo json_encode( $functions->delete_book(275) );

# Test de selección de libro específico
# $functions = new Functions();
# echo json_encode($functions->get_specific_book(2));

# Test de selección de revistas
# $functions = new Functions();
# echo json_encode( $functions->get_magazines() );

# Test de selección de autores
# $functions = new Functions();
# echo json_encode( $functions->get_authors());

# Test de selección de series
# $functions = new Functions();
# echo json_encode( $functions->get_series() );

# Test de inserción de serie
# $functions = new Functions();
# echo json_encode( $functions->insert_serie("test_serie", "test") );

# Test de editar serie
# $functions = new Functions();
# echo json_encode( $functions->edit_serie(14, "test__edit_serie", "test_volumen_edit") );

# Test de eliminar serie
# $functions = new Functions();
# echo json_encode( $functions->delete_serie(14) );

//</editor-fold>
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

    /**
     * @param $id
     * @return array|null
     */
    function get_specific_user($id)
    {
        # Query that select the specific book
        $query = "SELECT * FROM Usuario WHERE Id_Usuario = $id";

        if ($result = mysqli_query($this->db, $query)) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información del usuario."));
        }
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
    function edit_user($Id_Usuario, $Nombre, $Ap_Paterno, $Ap_Materno, $Username, $Tipo_Usuario, $Grado, $Telefono, $Correo, $Direccion, $Instituto_Proveniencia, $Contrasena)
    {
        # Check that the user exists
        $query_validate = "SELECT * FROM Usuario WHERE Id_Usuario = $Id_Usuario";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            if ($result->num_rows > 0) {
                # Update the user
                $query_update = "UPDATE Usuario SET Nombre = '$Nombre', Ap_Paterno = '$Ap_Paterno', Ap_Materno = '$Ap_Materno', Username = '$Username' ,Tipo_Usuario = '$Tipo_Usuario', Grado = '$Grado', Telefono = '$Telefono', Correo = '$Correo', Direccion = '$Direccion', Instituto_Proveniencia = '$Instituto_Proveniencia', Contrasena = '$Contrasena' WHERE Id_Usuario = $Id_Usuario";

                # Execute the update query

                if ($result = mysqli_query($this->db, $query_update)) {
                    # Everything was ok
                    return (array("status" => 1, "message" => "Usuario editado exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información del usuario"));
                }
            } else {
                # Users does not exists
                return (array("status" => 101, "message" => "El usuario no se encuentra en la base de datos: " . $Id_Usuario . " ." . mysqli_error($this->db)));
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
    function delete_user($Username)
    {
        # Check that the user actually exists
        $query_validate = "SELECT 1 FROM Usuario WHERE Username = '$Username'";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            # Check the number of results
            if ($result->num_rows < 1) {
                # The username does not exists
                return (array("status" => 100, "message" => "No existe el usuario a eliminar."));
            } else {
                # Delete the user from the database
                # Query to eliminate the user
                $query_delete = "DELETE FROM Usuario WHERE Username = '$Username'";

                # Execute the deletion
                if ($result = mysqli_query($this->db, $query_delete)) {
                    # Se borró el usuario
                    return array("status" => 1, "message" => "Usuario eliminado exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar el usuario." . mysqli_error($this->db)));
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
                return ($books);
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
    function get_specific_book($id)
    {
        # Query that select the specific book
        $query = "SELECT * FROM Libro WHERE id_libro = $id";

        if ($result = mysqli_query($this->db, $query)) {
            $row = mysqli_fetch_assoc($result);
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
        if ($result = mysqli_query($this->db, $query)) {

            # Check the length of the answer
            if ($result->num_rows > 0) {
                # Book already exists
                return (array("status" => 2, "message" => "El libro ya existe."));
            } else {
                # Add the user
                # Query to insert the new book to the database
                $query = "INSERT INTO Libro (ISBN, titulo_libro, subtitulo_libro, titulo_original, numero_paginas, id_editorial, numero_edicion, fecha_edicion, lugar_publicacion, fecha_adquisicion, costo_libro, proveedor_libro, observaciones_libro, id_seccion, id_apartado, volumen_libro, ilustraciones, graficas, mapas, bibliografia, indice, pasta_blanda, planos, estatus, numero_copias, palabras_clave)"
                    . " VALUES ('$isbn', '$titulo_libro', '$subtitulo_libro', '$titulo_original', $numero_paginas, $id_editorial, $numero_edicion, '$fecha_edicion', '$lugar_publicacion', '$fecha_adquisicion', '$costo_libro', '$proveedor_libro', '$observaciones_libro', $id_seccion, '$id_apartado', '$volumen_libro', $ilustraciones, $graficas, $mapas, $bibliografia, $indice, $pasta_blanda, $planos, $estatus, $numero_copias, '$palabras_clave') ";

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


function add_book_author($id_libro, $id_autor){
    $query = "INSERT INTO Libro_Autor ( id_libro, id_autor) VALUES ( $id_libro, $id_autor )";

    if ($result = mysqli_query($this->db, $query)) {
        # Everything went right
        return (array("status" => 1, "message" => "La asociación libro - autor se agregó correctamente."));
    } else {
        # Something went wrong
        return (array("status" => 0, "message" => "Algo salió mal al agregar la asociación libro - autor."));
    }
}

function add_book_collection($id_libro, $id_coleccion){
    $query = "INSERT INTO Libro_Coleccion ( id_libro, id_coleccion ) VALUES ( $id_libro, $id_coleccion )";

    if ($result = mysqli_query($this->db, $query)) {
        # Everything went right
        return (array("status" => 1, "message" => "La asociación libro - colección se agregó correctamente."));
    } else {
        # Something went wrong
        return (array("status" => 0, "message" => "Algo salió mal al agregar la asociación libro - colección."));
    }
}

function add_book_work($id_libro, $id_obra, $numero_tomo){
    $query = "INSERT INTO Libro_Obra ( id_libro, id_obra, numero_tomo ) VALUES ( $id_libro, $id_obra, $numero_tomo)";

    if ($result = mysqli_query($this->db, $query)) {
        # Everything went right
        return (array("status" => 1, "message" => "La asociación libro - obra se agregó correctamente."));
    } else {
        # Something went wrong
        return (array("status" => 0, "message" => "Algo salió mal al agregar la asociación libro - obra."));
    }
}

function add_book_serie($id_libro, $id_serie){
    $query = "INSERT INTO Libro_Serie ( id_libro, id_serie ) VALUES ( $id_libro, $id_serie )";

    if ($result = mysqli_query($this->db, $query)) {
        # Everything went right
        return (array("status" => 1, "message" => "La asociación libro - serie se agregó correctamente."));
    } else {
        # Something went wrong
        return (array("status" => 0, "message" => "Algo salió mal al agregar la asociación libro - serie."));
    }
}

function add_book_theme($id_libro, $id_tema){
    $query = "INSERT INTO Revista_Ejemplar ( id_libro, id_tema ) VALUES ( $id_libro, $id_tema )";

    if ($result = mysqli_query($this->db, $query)) {
        # Everything went right
        return (array("status" => 1, "message" => "La asociación libro - tema se agregó correctamente."));
    } else {
        # Something went wrong
        return (array("status" => 0, "message" => "Algo salió mal al agregar la asociación libro - tema."));
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
    function edit_book($id_libro, $isbn, $titulo_libro, $subtitulo_libro, $titulo_original, $numero_paginas, $id_editorial, $numero_edicion, $fecha_edicion, $lugar_publicacion, $fecha_adquisicion, $costo_libro, $proveedor_libro, $observaciones_libro, $id_seccion, $id_apartado, $volumen_libro, $ilustraciones, $graficas, $mapas, $bibliografia, $indice, $pasta_blanda, $planos, $estatus, $numero_copias, $palabras_clave)
    {
        # Check that the book exists
        $query_validate = "SELECT * FROM Libro WHERE id_libro = $id_libro";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            if ($result->num_rows > 0) {
                # Update the user
                $query_update = "UPDATE Libro SET ISBN = '$isbn', titulo_libro = '$titulo_libro', subtitulo_libro = '$subtitulo_libro', titulo_original = '$titulo_original', numero_paginas = $numero_paginas, id_editorial = $id_editorial, numero_edicion = $numero_edicion, fecha_edicion = '$fecha_edicion', lugar_publicacion = '$lugar_publicacion', fecha_adquisicion = '$fecha_adquisicion', costo_libro = '$costo_libro', proveedor_libro = '$proveedor_libro', observaciones_libro = '$observaciones_libro', id_seccion = $id_seccion, id_apartado = '$id_apartado', volumen_libro = '$volumen_libro', ilustraciones = $ilustraciones, graficas = $graficas, mapas = $mapas, bibliografia = $bibliografia, indice = $indice, pasta_blanda = $pasta_blanda, planos = $planos, estatus = $estatus, numero_copias = $numero_copias, palabras_clave = '$palabras_clave' WHERE id_libro = $id_libro";

                # Execute the update query

                if ($result = mysqli_query($this->db, $query_update)) {
                    # Everything was ok
                    return (array("status" => 1, "message" => "Libro editado exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información del libro"));
                }
            } else {
                # Book does not exists
                return (array("status" => 101, "message" => "El libro no se encuentra en la base de datos: " . $id_libro . " ." . mysqli_error($this->db)));
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
    function delete_book($id_libro)
    {
        # Check that the book actually exists
        $query_validate = "SELECT 1 FROM Libro WHERE id_libro = $id_libro";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            # Check the number of results
            if ($result->num_rows < 1) {
                # The book does not exists
                return (array("status" => 100, "message" => "No existe el libro a eliminar."));
            } else {
                # Delete the book from the database
                # Query to eliminate the book
                $query_delete = "DELETE FROM Libro WHERE id_libro = $id_libro";

                # Execute the deletion
                if ($result = mysqli_query($this->db, $query_delete)) {
                    # Se borró el usuario
                    return array("status" => 1, "message" => "Libro eliminado exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar el libro." . mysqli_error($this->db)));
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

    /**
     * @param $id
     * @return array|null
     */
    function get_specific_serie($id)
    {
        # Query that select the specific serie
        $query = "SELECT * FROM Serie WHERE id_serie = $id";

        if ($result = mysqli_query($this->db, $query)) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de la serie."));
        }
    }

    # Insert a new serie

    /**
     * @param $nombre_serie
     * @param $volumen_serie
     * @return array
     */
    function insert_serie($nombre_serie, $volumen_serie)
    {
        # nombre_serie
        # volumen_serie
        # Create the query
        $query = "INSERT INTO Serie (nombre_serie, volumen_serie) VALUES ('$nombre_serie', '$volumen_serie')";

        if ($result = mysqli_query($this->db, $query)) {
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
    function edit_serie($id_serie, $nombre_serie, $volumen_serie)
    {
        # Check that the user exists
        $query_validate = "SELECT * FROM Serie WHERE id_serie = $id_serie";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            if ($result->num_rows > 0) {
                # Update the serie
                $query_update = "UPDATE Serie SET nombre_serie = '$nombre_serie', volumen_serie = '$volumen_serie' WHERE id_serie = $id_serie";
                # Execute the update query
                if ($result = mysqli_query($this->db, $query_update)) {
                    # Everything was ok
                    return (array("status" => 1, "message" => "Serie editada exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información de la serie"));
                }
            } else {
                # Serie does not exists
                return (array("status" => 101, "message" => "La serie no se encuentra en la base de datos: " . $id_serie . " ." . mysqli_error($this->db)));
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
    function delete_serie($id_serie)
    {
        # Check that the user actually exists
        $query_validate = "SELECT 1 FROM Serie WHERE id_serie = '$id_serie'";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            # Check the number of results
            if ($result->num_rows < 1) {
                # The serie does not exists
                return (array("status" => 100, "message" => "No existe la serie a eliminar."));
            } else {
                # Delete the serie from the database
                # Query to eliminate the serie
                $query_delete = "DELETE FROM Serie WHERE id_serie = '$id_serie'";

                # Execute the deletion
                if ($result = mysqli_query($this->db, $query_delete)) {
                    # the serie was deleted
                    return array("status" => 1, "message" => "Serie eliminada exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar la serie." . mysqli_error($this->db)));
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
    /**
     * @return array
     */
    function get_authors()
    {
        # Query that selects all the authors from the table
        $query = "SELECT * FROM Autor";
        if ($result = mysqli_query($this->db, $query)) {
            # Check for the number of authors
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

    /**
     * @param $id
     * @return array|null
     */
    function get_specific_author($id)
    {
        # Query that select the specific author
        $query = "SELECT * FROM Autor WHERE id_autor = $id";

        if ($result = mysqli_query($this->db, $query)) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información del autor."));
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
    /**
     * @param $apaterno_autor
     * @param $amaterno_autor
     * @param $nombre_autor
     * @param $nacimiento_autor
     * @param $nacionalidad_autor
     * @return array
     */
    function add_author($apaterno_autor, $amaterno_autor, $nombre_autor, $nacimiento_autor, $nacionalidad_autor)
    {
        # Create the query
        $query = "INSERT INTO Autor (apaterno_autor, amaterno_autor, nombre_autor, nacimiento_autor, nacionalidad_autor) VALUES ( '$apaterno_autor', '$amaterno_autor', '$nombre_autor', '$nacimiento_autor', '$nacionalidad_autor' )";
        if ($result = mysqli_query($this->db, $query)) {
            # Everything went right
            return (array("status" => 1, "message" => "El autor se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al insertar un autor."));
        }
    }

    # Edit an author

    /**
     * @param $id_autor
     * @param $apaterno_autor
     * @param $amaterno_autor
     * @param $nombre_autor
     * @param $nacimiento_autor
     * @param $nacionalidad_autor
     * @return array
     */
    function edit_author($id_autor, $apaterno_autor, $amaterno_autor, $nombre_autor, $nacimiento_autor, $nacionalidad_autor)
    {
        # Check that the author exists
        $query_validate = "SELECT * FROM Autor WHERE id_autor = $id_autor";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            if ($result->num_rows > 0) {
                # Update the author
                $query_update = "UPDATE Autor SET apaterno_autor = '$apaterno_autor', amaterno_autor = '$amaterno_autor', nombre_autor = '$nombre_autor', nacimiento_autor = '$nacimiento_autor', nacionalidad_autor = '$nacionalidad_autor' WHERE id_autor = $id_autor";
                # Execute the update query
                if ($result = mysqli_query($this->db, $query_update)) {
                    # Everything was ok
                    return (array("status" => 1, "message" => "Autor editado exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información del autor"));
                }
            } else {
                # Serie does not exists
                return (array("status" => 101, "message" => "El autor no se encuentra en la base de datos: " . $id_autor . " ." . mysqli_error($this->db)));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Editar autor: Algo salió mal al validar el autor."));
        }
    }

    /**
     * @param $id_autor
     * @return array
     */
    function delete_autor($id_autor)
    {
        # Check that the author actually exists
        $query_validate = "SELECT 1 FROM Autor WHERE id_autor = '$id_autor'";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            # Check the number of results
            if ($result->num_rows < 1) {
                # The author does not exists
                return (array("status" => 100, "message" => "No existe el autor a eliminar."));
            } else {
                # Delete the author from the database
                # Query to eliminate the author
                $query_delete = "DELETE FROM Autor WHERE id_autor = '$id_autor'";

                # Execute the deletion
                if ($result = mysqli_query($this->db, $query_delete)) {
                    # the author was deleted
                    return array("status" => 1, "message" => "Autor eliminado exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar el autor." . mysqli_error($this->db)));
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

    /**
     * @param $id
     * @return array|null
     */
    function get_specific_magazine($id)
    {
        # Query that select the specific magazine
        $query = "SELECT * FROM Revista WHERE id_revista = $id";

        if ($result = mysqli_query($this->db, $query)) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de la revista."));
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
    function add_magazine($id_seccion, $id_editorial, $nombre_revista, $periodicidad, $palabras_clave, $notas_adicionales)
    {
        $query = "INSERT INTO Revista (id_seccion, id_editorial, nombre_revista, periodicidad, palabras_clave, notas_adicionales) VALUES ($id_seccion, $id_editorial, '$nombre_revista', '$periodicidad', '$palabras_clave', '$notas_adicionales')";

        if ($result = mysqli_query($this->db, $query)) {
            # Everything went right
            return (array("status" => 1, "message" => "La revista se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al insertar una revista."));
        }
    }

    /**
     * @param $id_revista
     * @param $id_datosrevista
     * @return array
     */
    function add_magazine_exampler($id_revista, $id_datosrevista)
    {
        $query = "INSERT INTO Revista_Ejemplar ( id_revista, id_datosrevista ) VALUES ( $id_revista, $id_datosrevista )";

        if ($result = mysqli_query($this->db, $query)) {
            # Everything went right
            return (array("status" => 1, "message" => "La asociación revista - ejemplar se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al agregar la asociación revista - ejemplar."));
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
    function edit_magazine($id_revista, $id_seccion, $id_editorial, $nombre_revista, $periodicidad, $palabras_clave, $notas_adicionales)
    {
        # Check that the section exists
        $query_validate = "SELECT * FROM Revista WHERE id_revista = $id_revista";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            if ($result->num_rows > 0) {
                # Update the section
                $query_update = "UPDATE Revista SET id_seccion = $id_seccion, id_editorial = $id_editorial, nombre_revista = '$nombre_revista', periodicidad = '$periodicidad', palabras_clave = '$palabras_clave', notas_adicionales = '$notas_adicionales' WHERE id_revista = $id_revista";
                # Execute the update query
                if ($result = mysqli_query($this->db, $query_update)) {
                    # Everything was ok
                    return (array("status" => 1, "message" => "Revista editada exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información de la revista"));
                }
            } else {
                # Section does not exists
                return (array("status" => 101, "message" => "La revista no se encuentra en la base de datos: " . $id_revista . " ." . mysqli_error($this->db)));
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
    function delete_magazine($id_revista)
    {
        # Check that the magazine actually exists
        $query_validate = "SELECT 1 FROM Revista WHERE id_revista = '$id_revista'";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            # Check the number of results
            if ($result->num_rows < 1) {
                # The magazine does not exists
                return (array("status" => 100, "message" => "No existe la revista a eliminar."));
            } else {
                # Delete the magazine from the database
                # Query to eliminate the magazine
                $query_delete = "DELETE FROM Revista WHERE id_revista = '$id_revista'";

                # Execute the deletion
                if ($result = mysqli_query($this->db, $query_delete)) {
                    # the magazine was deleted
                    return array("status" => 1, "message" => "Revista eliminada exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar la revista." . mysqli_error($this->db)));
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
    function add_section($nombre_seccion)
    {
        # nombre_seccion
        # Create the query
        $query = "INSERT INTO Seccion (nombre_seccion) VALUES ('$nombre_seccion')";

        if ($result = mysqli_query($this->db, $query)) {
            # Everything went right
            return (array("status" => 1, "message" => "La sección se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al insertar una sección."));
        }
    }

    /**
     * @param $id
     * @return array|null
     */
    function get_specific_section($id)
    {
        # Query that select the specific section
        $query = "SELECT * FROM Seccion WHERE id_seccion = $id";

        if ($result = mysqli_query($this->db, $query)) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de la sección."));
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
    function edit_section($id_seccion, $nombre_seccion)
    {
        # Check that the section exists
        $query_validate = "SELECT * FROM Seccion WHERE id_seccion = $id_seccion";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            if ($result->num_rows > 0) {
                # Update the section
                $query_update = "UPDATE Seccion SET nombre_seccion = '$nombre_seccion' WHERE id_seccion = $id_seccion";
                # Execute the update query
                if ($result = mysqli_query($this->db, $query_update)) {
                    # Everything was ok
                    return (array("status" => 1, "message" => "Sección editada exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información de la sección"));
                }
            } else {
                # Section does not exists
                return (array("status" => 101, "message" => "La sección no se encuentra en la base de datos: " . $id_seccion . " ." . mysqli_error($this->db)));
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
    function delete_section($id_seccion)
    {
        # Check that the section actually exists
        $query_validate = "SELECT 1 FROM Seccion WHERE id_seccion = '$id_seccion'";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            # Check the number of results
            if ($result->num_rows < 1) {
                # The section does not exists
                return (array("status" => 100, "message" => "No existe la sección a eliminar."));
            } else {
                # Delete the section from the database
                # Query to eliminate the section
                $query_delete = "DELETE FROM Seccion WHERE id_seccion = '$id_seccion'";

                # Execute the deletion
                if ($result = mysqli_query($this->db, $query_delete)) {
                    # the section was deleted
                    return array("status" => 1, "message" => "Sección eliminada exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar la sección." . mysqli_error($this->db)));
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
    /**
     * @return array
     */
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

    function add_editorial_serie($id_editorial, $id_serie){
        $query = "INSERT INTO Editorial_Serie ( id_editorial, id_serie) VALUES ( $id_editorial, $id_serie )";

        if ($result = mysqli_query($this->db, $query)) {
            # Everything went right
            return (array("status" => 1, "message" => "La asociación editorial - serie se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al agregar la asociación editorial - serie."));
        }
    }

    /**
     * @param $id
     * @return array|null
     */
    function get_specific_editorial($id)
    {
        # Query that select the specific editorial
        $query = "SELECT * FROM Editorial WHERE id_editorial = $id";

        if ($result = mysqli_query($this->db, $query)) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de la editorial."));
        }
    }

    # Insert a new editorial

    /**
     * @param $nombre_editorial
     * @param $nombre_direccion
     * @return array
     */
    function add_editorial($nombre_editorial, $nombre_direccion)
    {
        # id_editorial
        # nombre_editorial
        # nombre_direccion

        # Create the query
        $query = "INSERT INTO Editorial (nombre_editorial, nombre_direccion) VALUES ('$nombre_editorial', '$nombre_direccion')";

        if ($result = mysqli_query($this->db, $query)) {
            # Everything went right
            return (array("status" => 1, "message" => "La editorial se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al insertar la editorial."));
        }
    }

    # Edit a editorial

    /**
     * @param $id_editorial
     * @param $nombre_editorial
     * @param $nombre_direccion
     * @return array
     */
    function edit_editorial($id_editorial, $nombre_editorial, $nombre_direccion)
    {
        # Check that the editorial exists
        $query_validate = "SELECT * FROM Editorial WHERE id_editorial = $id_editorial";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            if ($result->num_rows > 0) {
                # Update the editorial
                $query_update = "UPDATE Editorial SET nombre_editorial = '$nombre_editorial', nombre_direccion = '$nombre_direccion' WHERE id_editorial = $id_editorial";
                # Execute the update query
                if ($result = mysqli_query($this->db, $query_update)) {
                    # Everything was ok
                    return (array("status" => 1, "message" => "Editorial editada exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información de la editorial"));
                }
            } else {
                # Editorial does not exists
                return (array("status" => 101, "message" => "La editorial no se encuentra en la base de datos: " . $id_editorial . " ." . mysqli_error($this->db)));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Editar editorial: Algo salió mal al validar la editorial."));
        }
    }

    # delete a editorial

    /**
     * @param $id_editorial
     * @return array
     */
    function delete_editorial($id_editorial)
    {
        # Check that the editorial actually exists
        $query_validate = "SELECT 1 FROM Editorial WHERE id_editorial = $id_editorial";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            # Check the number of results
            if ($result->num_rows < 1) {
                # The editorial does not exists
                return (array("status" => 100, "message" => "No existe la editorial a eliminar."));
            } else {
                # Delete the editorial from the database
                # Query to eliminate the editorial
                $query_delete = "DELETE FROM Editorial WHERE id_editorial = $id_editorial";

                # Execute the deletion
                if ($result = mysqli_query($this->db, $query_delete)) {
                    # the editorial was deleted
                    return array("status" => 1, "message" => "Editorial eliminada exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar la editorial." . mysqli_error($this->db)));
                }
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Borrar editorial: Algo salió mal al validar la editorial."));
        }
    }

    //</editor-fold>

    //<editor-fold desc="Apartado functions">

    # Obtener la lista de apartados
    /**
     * @return array
     */
    function get_apartados()
    {
        # Query para seleccionar todos los apartados de la tabla
        $query = "SELECT * FROM Apartado";
        if ($result = mysqli_query($this->db, $query)) {
            # Revisar el número de apartados
            if ($result->num_rows > 0) {
                $apartados = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    array_push($apartados, $row);
                }
                $apartados['status'] = 1;
                return ($apartados);
            } else {
                # La tabla de apartados esta vacía
                return (array("status" => 2, "message" => "No se han agregado apartados."));
            }
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de los apartados."));
        }
    }

    /**
     * @param $id
     * @return array|null
     */
    function get_specific_apartado($id)
    {
        # Query that select the specific apartado
        $query = "SELECT * FROM Apartado WHERE id_apartado = '$id'";

        if ($result = mysqli_query($this->db, $query)) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información del apartado."));
        }
    }

# Agregar apartados
    /*
     * id_apartado
     * id_seccion
     * nombre_apartado
     */
    /**
     * @param $id_seccion
     * @param $nombre_apartado
     * @return array
     */
    function add_apartado($id_apartado, $id_seccion, $nombre_apartado)
    {
        # Create the query
        $query = "INSERT INTO Apartado ( id_apartado, id_seccion, nombre_apartado ) VALUES ( '$id_apartado', $id_seccion, '$nombre_apartado' )";
        if ($result = mysqli_query($this->db, $query)) {
            # Everything went right
            return (array("status" => 1, "message" => "El apartado se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al insertar un apartado."));
        }
    }

# Editar un apartado

    /**
     * @param $id_apartado
     * @param $id_seccion
     * @param $nombre_apartado
     * @return array
     */
    function edit_apartado($id_apartado, $id_seccion, $nombre_apartado)
    {
        # Revisar si el apartado a modificar existe
        $query_validate = "SELECT * FROM Apartado WHERE id_apartado = $id_apartado";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            if ($result->num_rows > 0) {
                # Actualizar el apartado
                $query_update = "UPDATE Apartado SET id_seccion = $id_seccion, nombre_apartado = '$nombre_apartado' WHERE id_apartado = $id_apartado";
                # Execute the update query
                if ($result = mysqli_query($this->db, $query_update)) {
                    # Everything was ok
                    return (array("status" => 1, "message" => "Apartado editado exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información del apartado"));
                }
            } else {
                # el apartado no existe
                return (array("status" => 101, "message" => "El apartado no se encuentra en la base de datos: " . $id_apartado . " ." . mysqli_error($this->db)));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Editar apartado: Algo salió mal al validar el apartado."));
        }
    }

    /**
     * @param $id_apartado
     * @return array
     */
    function delete_apartado($id_apartado)
    {
        # Revisar si existe el apartado a eliminar
        $query_validate = "SELECT 1 FROM Apartado WHERE id_apartado = $id_apartado";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            # Check the number of results
            if ($result->num_rows < 1) {
                # El apartado no existe
                return (array("status" => 100, "message" => "No existe el apartado a eliminar."));
            } else {
                # Eliminar el apartado de la base de datos
                # Query para eliminar el apartado
                $query_delete = "DELETE FROM Apartado WHERE id_apartado = $id_apartado";

                # Execute the deletion
                if ($result = mysqli_query($this->db, $query_delete)) {
                    # Apartado eliminado
                    return array("status" => 1, "message" => "Apartado eliminado exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar el apartado." . mysqli_error($this->db)));
                }
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Borrar apartado: Algo salió mal al validar el apartado."));
        }
    }

    //</editor-fold>

    //<editor-fold desc="Prestamo functions">

    # Get the list of all the loans
    /**
     * @return array
     */
    function get_loans()
    {
        # Query that selects all the loans from the table
        $query = "SELECT * FROM Prestamo";
        if ($result = mysqli_query($this->db, $query)) {
            # Check for the number of loans
            if ($result->num_rows > 0) {
                $loans = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    array_push($loans, $row);
                }
                $loans['status'] = 1;
                return ($loans);
            } else {
                # The loans table is empty
                return (array("status" => 2, "message" => "No se han agregado préstamos."));
            }
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de los préstamos."));
        }
    }

    /**
     * @param $id
     * @return array|null
     */
    function get_specific_loan($id)
    {
        # Query that select the specific book
        $query = "SELECT * FROM Prestamo WHERE id_prestamo = $id";

        if ($result = mysqli_query($this->db, $query)) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información del préstamo."));
        }
    }

# Add loan
    /*
     * id_prestamo
     * id_libro
     * id_usuario
     * fecha_prestamo
     * fecha_vencimiento
     * fecha_devolucion
     */
    /**
     * @param $id_libro
     * @param $id_usuario
     * @param $fecha_prestamo
     * @param $fecha_vencimiento
     * @param $fecha_devolucion
     * @return array
     */
    function add_loan($id_libro, $id_usuario, $fecha_prestamo, $fecha_vencimiento, $fecha_devolucion)
    {
        # Create the query
        $query = "INSERT INTO Prestamo ( id_libro, id_usuario, fecha_prestamo, fecha_vencimiento, fecha_devolucion ) VALUES ( $id_libro, $id_usuario, '$fecha_prestamo', '$fecha_vencimiento', '$fecha_devolucion' )";
        if ($result = mysqli_query($this->db, $query)) {
            # Everything went right
            return (array("status" => 1, "message" => "El préstamo se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al insertar un préstamo."));
        }
    }

# Edit a loan

    /**
     * @param $id_prestamo
     * @param $id_libro
     * @param $id_usuario
     * @param $fecha_prestamo
     * @param $fecha_vencimiento
     * @param $fecha_devolucion
     * @return array
     */
    function edit_loan($id_prestamo, $id_libro, $id_usuario, $fecha_prestamo, $fecha_vencimiento, $fecha_devolucion)
    {
        # Check that the loan exists
        $query_validate = "SELECT * FROM Prestamo WHERE id_prestamo = $id_prestamo";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            if ($result->num_rows > 0) {
                # Update the loan
                $query_update = "UPDATE Prestamo SET id_libro = $id_libro, id_usuario = $id_usuario, fecha_prestamo = '$fecha_prestamo', fecha_vencimiento = '$fecha_vencimiento', fecha_devolucion = '$fecha_devolucion' WHERE id_prestamo = $id_prestamo";
                # Execute the update query
                if ($result = mysqli_query($this->db, $query_update)) {
                    # Everything was ok
                    return (array("status" => 1, "message" => "Préstamo editado exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información del préstamo"));
                }
            } else {
                # Loan does not exists
                return (array("status" => 101, "message" => "El préstamo no se encuentra en la base de datos: " . $id_prestamo . " ." . mysqli_error($this->db)));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Editar préstamo: Algo salió mal al validar el préstamo."));
        }
    }

    /**
     * @param $id_prestamo
     * @return array
     */
    function delete_loan($id_prestamo)
    {
        # Check that the author actually exists
        $query_validate = "SELECT 1 FROM Prestamo WHERE id_prestamo = $id_prestamo";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            # Check the number of results
            if ($result->num_rows < 1) {
                # The loan does not exists
                return (array("status" => 100, "message" => "No existe el préstamo a eliminar."));
            } else {
                # Delete the loan from the database
                # Query to eliminate the loan
                $query_delete = "DELETE FROM Préstamo WHERE id_prestamo = $id_prestamo";

                # Execute the deletion
                if ($result = mysqli_query($this->db, $query_delete)) {
                    # the loan was deleted
                    return array("status" => 1, "message" => "Préstamo eliminado exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar el préstamo." . mysqli_error($this->db)));
                }
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Borrar préstamo: Algo salió mal al validar el préstamo."));
        }
    }

    //</editor-fold>

    //<editor-fold desc="Subapartado functions">

    # obtener la lista de subapartados
    /**
     * @return array
     */
    function get_subapartados()
    {
        # Query para seleccionar todos los subapartados de la base de datos
        $query = "SELECT * FROM Subapartado";
        if ($result = mysqli_query($this->db, $query)) {
            # Revisar el número de subapartados
            if ($result->num_rows > 0) {
                $subapartados = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    array_push($subapartados, $row);
                }
                $subapartados['status'] = 1;
                return ($subapartados);
            } else {
                # The authors table is empty
                return (array("status" => 2, "message" => "No se han agregado subapartados."));
            }
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de los subapartados."));
        }
    }

    /**
     * @param $id
     * @param $id_apartado
     * @return array|null
     */
    function get_specific_subapartado($id, $id_apartado)
    {
        # Query that select the specific book
        $query = "SELECT * FROM Subapartado WHERE progresivo = $id AND id_apartado = $id_apartado";

        if ($result = mysqli_query($this->db, $query)) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información del subapartado."));
        }
    }

# Add subapartado
    /*
     * progresivo
     * id_apartado
     * id_seccion
     * nombre_subapartado
     */
    /**
     * @param $id_apartado
     * @param $id_seccion
     * @param $nombre_subapartado
     * @return array
     */
    function add_subapartado($id_apartado, $id_seccion, $nombre_subapartado)
    {
        # Create the query
        $query = "INSERT INTO Subapartado ( id_apartado, id_seccion, nombre_subapartado ) VALUES ( '$id_apartado', $id_seccion, '$nombre_subapartado' )";
        if ($result = mysqli_query($this->db, $query)) {
            # Everything went right
            return (array("status" => 1, "message" => "El subapartado se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al insertar un subapartado."));
        }
    }

# Edit subapartado

    /**
     * @param $progresivo
     * @param $id_apartado
     * @param $id_seccion
     * @param $nombre_subapartado
     * @return array
     */
    function edit_subapartado($progresivo, $id_apartado, $id_seccion, $nombre_subapartado)
    {
        # Check that the author exists
        $query_validate = "SELECT * FROM Subapartado WHERE progresivo = $progresivo";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            if ($result->num_rows > 0) {
                # Actualizar el subapartado
                $query_update = "UPDATE Subapartado SET id_apartado = '$id_apartado', id_seccion = $id_seccion, nombre_subapartado = '$nombre_subapartado' WHERE progresivo = $progresivo";
                # Execute the update query
                if ($result = mysqli_query($this->db, $query_update)) {
                    # Everything was ok
                    return (array("status" => 1, "message" => "Subapartado editado exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información del subapartado"));
                }
            } else {
                # El subapartado no existe
                return (array("status" => 101, "message" => "El subapartado no se encuentra en la base de datos: " . $progresivo . " ." . mysqli_error($this->db)));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Editar subapartado: Algo salió mal al validar el subapartado."));
        }
    }

    /**
     * @param $progresivo
     * @return array
     */
    function delete_subapartado($progresivo)
    {
        # Revisar si el subapartado existe en la base de datos
        $query_validate = "SELECT 1 FROM Subapartado WHERE progresivo = $progresivo";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            # Check the number of results
            if ($result->num_rows < 1) {
                # El subapartado no existe
                return (array("status" => 100, "message" => "No existe el subapartado a eliminar."));
            } else {
                # Borrar el subapartado de la base de datos
                # Query para eliminar el subapartado
                $query_delete = "DELETE FROM Subapartado WHERE progresivo = $progresivo";

                # Execute the deletion
                if ($result = mysqli_query($this->db, $query_delete)) {
                    # El subapartado fue borrado
                    return array("status" => 1, "message" => "Subapartado eliminado exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar el subapartado." . mysqli_error($this->db)));
                }
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Borrar subapartado: Algo salió mal al validar el subapartado."));
        }
    }

    //</editor-fold>

    //<editor-fold desc="Articulo functions">

    # Get the list of article
    /**
     * @return array
     */
    function get_articles()
    {
        # Query that selects all the articles from the table
        $query = "SELECT * FROM Articulo";
        if ($result = mysqli_query($this->db, $query)) {
            # Check for the number of articles
            if ($result->num_rows > 0) {
                $articles = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    array_push($articles, $row);
                }
                $articles['status'] = 1;
                return ($articles);
            } else {
                # The authors table is empty
                return (array("status" => 2, "message" => "No se han agregado artículos."));
            }
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de los artículos."));
        }
    }

    /**
     * @param $id
     * @return array|null
     */
    function get_specific_article($id)
    {
        # Query that select the specific article
        $query = "SELECT * FROM Articulo WHERE id_articulo = $id";

        if ($result = mysqli_query($this->db, $query)) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información del artículo."));
        }
    }

# Add article
    /*
     * id_articulo
     * id_revista
     * nombre_articulo
     * cantidad_paginas
     * numero_ejemplar
     * año_articulo
     * reseña_articulo
     */
    /**
     * @param $id_revista
     * @param $nombre_articulo
     * @param $cantidad_paginas
     * @param $numero_ejemplar
     * @param $año_articulo
     * @param $reseña_articulo
     * @return array
     */
    function add_article($id_revista, $nombre_articulo, $cantidad_paginas, $numero_ejemplar, $año_articulo, $reseña_articulo)
    {
        # Create the query
        $query = "INSERT INTO Articulo ( id_revista, nombre_articulo, cantidad_paginas, numero_ejemplar, año_articulo, reseña_articulo ) VALUES ( $id_revista, '$nombre_articulo', '$cantidad_paginas', '$numero_ejemplar', '$año_articulo', '$reseña_articulo' )";
        if ($result = mysqli_query($this->db, $query)) {
            # Everything went right
            return (array("status" => 1, "message" => "El artículo se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al insertar un artículo."));
        }
    }

    /**
     * @param $idArticulo
     * @param $idAutor
     * @return array
     */
    function add_article_author($idArticulo, $idAutor)
    {
        # Create the query
        $query = "INSERT INTO Articulo_Autor ( idArticulo, idAutor ) VALUES ( $idArticulo, $idAutor )";
        if ($result = mysqli_query($this->db, $query)) {
            # Everything went right
            return (array("status" => 1, "message" => "La asociación artículo - autor se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al asociar nu artículo con autor."));
        }
    }

# Edit an article

    /**
     * @param $id_articulo
     * @param $id_revista
     * @param $nombre_articulo
     * @param $cantidad_paginas
     * @param $numero_ejemplar
     * @param $año_articulo
     * @param $reseña_articulo
     * @return array
     */
    function edit_article($id_articulo, $id_revista, $nombre_articulo, $cantidad_paginas, $numero_ejemplar, $año_articulo, $reseña_articulo)
    {
        # Check that the article exists
        $query_validate = "SELECT * FROM Articulo WHERE id_articulo = $id_articulo";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            if ($result->num_rows > 0) {
                # Update the article
                $query_update = "UPDATE Articulo SET id_revista = $id_revista, nombre_articulo = '$nombre_articulo', cantidad_paginas = '$cantidad_paginas', numero_ejemplar = '$numero_ejemplar', año_articulo = '$año_articulo', reseña_articulo = $reseña_articulo WHERE id_articulo = $id_articulo";
                # Execute the update query
                if ($result = mysqli_query($this->db, $query_update)) {
                    # Everything was ok
                    return (array("status" => 1, "message" => "Artículo editado exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información del artículo"));
                }
            } else {
                # Article does not exists
                return (array("status" => 101, "message" => "El artículo no se encuentra en la base de datos: " . $id_articulo . " ." . mysqli_error($this->db)));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Editar artículo: Algo salió mal al validar el artículo."));
        }
    }

    /**
     * @param $id_articulo
     * @return array
     */
    function delete_article($id_articulo)
    {
        # Check that the article actually exists
        $query_validate = "SELECT 1 FROM Articulo WHERE id_articulo = $id_articulo";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            # Check the number of results
            if ($result->num_rows < 1) {
                # The article does not exists
                return (array("status" => 100, "message" => "No existe el artículo a eliminar."));
            } else {
                # Delete the article from the database
                # Query to eliminate the article
                $query_delete = "DELETE FROM Articulo WHERE id_articulo = $id_articulo";

                # Execute the deletion
                if ($result = mysqli_query($this->db, $query_delete)) {
                    # the article was deleted
                    return array("status" => 1, "message" => "Artículo eliminado exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar el artículo." . mysqli_error($this->db)));
                }
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Borrar artículo: Algo salió mal al validar el artículo."));
        }
    }

    //</editor-fold>

    //<editor-fold desc="Coleccion functions">

    # Get the list of collections
    /**
     * @return array
     */
    function get_collections()
    {
        # Query that selects all the collections from the table
        $query = "SELECT * FROM Coleccion";
        if ($result = mysqli_query($this->db, $query)) {
            # Check for the number of collections
            if ($result->num_rows > 0) {
                $collections = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    array_push($collections, $row);
                }
                $collections['status'] = 1;
                return ($collections);
            } else {
                # The collection table is empty
                return (array("status" => 2, "message" => "No se han agregado colecciones."));
            }
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de las colecciones."));
        }
    }

    /**
     * @param $id
     * @return array|null
     */
    function get_specific_collection($id)
    {
        # Query that select the specific collection
        $query = "SELECT * FROM Coleccion WHERE id_coleccion = $id";

        if ($result = mysqli_query($this->db, $query)) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de la colección."));
        }
    }

# Add collections
    /*
         * id_coleccion
         * nombre_coleccion
         * numero_coleccion
         * volumenes
         * id_seccion
         */
    /**
     * @param $nombre_coleccion
     * @param $numero_coleccion
     * @param $volumenes
     * @param $id_seccion
     * @return array
     */
    function add_collection($nombre_coleccion, $numero_coleccion, $volumenes, $id_seccion)
    {
        # Create the query
        $query = "INSERT INTO Coleccion ( nombre_coleccion, numero_coleccion, volumenes, id_seccion ) VALUES ( '$nombre_coleccion', $numero_coleccion, '$volumenes', $id_seccion )";
        if ($result = mysqli_query($this->db, $query)) {
            # Everything went right
            return (array("status" => 1, "message" => "La colección se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al insertar una colección."));
        }
    }

# Edit a collection

    /**
     * @param $id_coleccion
     * @param $nombre_coleccion
     * @param $numero_coleccion
     * @param $volumenes
     * @param $id_seccion
     * @return array
     */
    function edit_collection($id_coleccion, $nombre_coleccion, $numero_coleccion, $volumenes, $id_seccion)
    {
        # Check that the collection exists
        $query_validate = "SELECT * FROM Coleccion WHERE id_coleccion = $id_coleccion";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            if ($result->num_rows > 0) {
                # Update the collection
                $query_update = "UPDATE Coleccion SET nombre_coleccion = '$nombre_coleccion', numero_coleccion = $numero_coleccion, volumenes = '$volumenes', id_seccion = $id_seccion WHERE id_coleccion = $id_coleccion";
                # Execute the update query
                if ($result = mysqli_query($this->db, $query_update)) {
                    # Everything was ok
                    return (array("status" => 1, "message" => "Colección editada exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información de la colección"));
                }
            } else {
                # Serie does not exists
                return (array("status" => 101, "message" => "La colección no se encuentra en la base de datos: " . $id_coleccion . " ." . mysqli_error($this->db)));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Editar colección: Algo salió mal al validar la colección."));
        }
    }

    /**
     * @param $id_coleccion
     * @return array
     */
    function delete_collection($id_coleccion)
    {
        # Check that the collection actually exists
        $query_validate = "SELECT 1 FROM Coleccion WHERE id_collecion = $id_coleccion";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            # Check the number of results
            if ($result->num_rows < 1) {
                # The collection does not exists
                return (array("status" => 100, "message" => "No existe la coleccion a eliminar."));
            } else {
                # Delete the collection from the database
                # Query to eliminate the collection
                $query_delete = "DELETE FROM Coleccion WHERE id_coleccion = $id_coleccion";

                # Execute the deletion
                if ($result = mysqli_query($this->db, $query_delete)) {
                    # the collection was deleted
                    return array("status" => 1, "message" => "Coleccion eliminada exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar la coleccion." . mysqli_error($this->db)));
                }
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Borrar colección: Algo salió mal al validar la colección."));
        }
    }

    //</editor-fold>

    //<editor-fold desc="Obra functions">

    # Get the list of works
    /**
     * @return array
     */
    function get_works()
    {
        # Query that selects all the works from the table
        $query = "SELECT * FROM Obra";
        if ($result = mysqli_query($this->db, $query)) {
            # Check for the number of works
            if ($result->num_rows > 0) {
                $works = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    array_push($works, $row);
                }
                $works['status'] = 1;
                return ($works);
            } else {
                # The authors table is empty
                return (array("status" => 2, "message" => "No se han agregado obras."));
            }
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de las obras."));
        }
    }

    /**
     * @param $id
     * @return array|null
     */
    function get_specific_work($id)
    {
        # Query that select the specific work
        $query = "SELECT * FROM Obra WHERE id_obra = $id";

        if ($result = mysqli_query($this->db, $query)) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de la obra."));
        }
    }

# Add work
    /*
     * id_obra
     * nombre_obra
     * numero_tomo
     */
    /**
     * @param $nombre_obra
     * @param $numero_tomo
     * @return array
     */
    function add_work($nombre_obra, $numero_tomo)
    {
        # Create the query
        $query = "INSERT INTO Obra ( nombre_obra, numero_tomo ) VALUES ( '$nombre_obra', $numero_tomo )";
        if ($result = mysqli_query($this->db, $query)) {
            # Everything went right
            return (array("status" => 1, "message" => "La obra se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al insertar una obra."));
        }
    }

# Edit a work

    /**
     * @param $id_obra
     * @param $nombre_obra
     * @param $numero_tomo
     * @return array
     */
    function edit_work($id_obra, $nombre_obra, $numero_tomo)
    {
        # Check that the work exists
        $query_validate = "SELECT * FROM Obra WHERE id_obra = $id_obra";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            if ($result->num_rows > 0) {
                # Update the work
                $query_update = "UPDATE Obra SET nombre_obra = '$nombre_obra', numero_tomo = $numero_tomo WHERE id_obra = $id_obra";
                # Execute the update query
                if ($result = mysqli_query($this->db, $query_update)) {
                    # Everything was ok
                    return (array("status" => 1, "message" => "Obra editada exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información de la obra."));
                }
            } else {
                # Work does not exists
                return (array("status" => 101, "message" => "La obra no se encuentra en la base de datos: " . $id_obra . " ." . mysqli_error($this->db)));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Editar obra: Algo salió mal al validar la obra."));
        }
    }

    /**
     * @param $id_obra
     * @return array
     */
    function delete_work($id_obra)
    {
        # Check that the work actually exists
        $query_validate = "SELECT 1 FROM Obra WHERE id_obra = $id_obra";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            # Check the number of results
            if ($result->num_rows < 1) {
                # The work does not exists
                return (array("status" => 100, "message" => "No existe la obra a eliminar."));
            } else {
                # Delete the work from the database
                # Query to eliminate the work
                $query_delete = "DELETE FROM Obra WHERE id_obra = $id_obra";

                # Execute the deletion
                if ($result = mysqli_query($this->db, $query_delete)) {
                    # the work was deleted
                    return array("status" => 1, "message" => "Obra eliminada exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar la obra." . mysqli_error($this->db)));
                }
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Borrar obra: Algo salió mal al validar la obra."));
        }
    }

    //</editor-fold>

    //<editor-fold desc="Tema functions">
    # Get the list of themes
    /**
     * @return array
     */
    function get_themes()
    {
        # Query that selects all the themes from the table
        $query = "SELECT * FROM Tema";
        if ($result = mysqli_query($this->db, $query)) {
            # Check for the number of themes
            if ($result->num_rows > 0) {
                $themes = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    array_push($themes, $row);
                }
                $themes['status'] = 1;
                return ($themes);
            } else {
                # The themes table is empty
                return (array("status" => 2, "message" => "No se han agregado temas."));
            }
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de los temas."));
        }
    }

    /**
     * @param $id
     * @return array|null
     */
    function get_specific_theme($id)
    {
        # Query that select the specific theme
        $query = "SELECT * FROM Tema WHERE id_tema = $id";

        if ($result = mysqli_query($this->db, $query)) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información del tema."));
        }
    }

# Add theme
    /*
     * id_tema
     * tema
     */
    /**
     * @param $tema
     * @return array
     */
    function add_theme($tema)
    {
        # Create the query
        $query = "INSERT INTO Tema (tema) VALUES ( '$tema' )";
        if ($result = mysqli_query($this->db, $query)) {
            # Everything went right
            return (array("status" => 1, "message" => "El tema se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al insertar un tema."));
        }
    }

# Edit a theme

    /**
     * @param $id_tema
     * @param $tema
     * @return array
     */
    function edit_theme($id_tema, $tema)
    {
        # Check that the theme exists
        $query_validate = "SELECT * FROM Tema WHERE id_tema = $id_tema";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            if ($result->num_rows > 0) {
                # Update the theme
                $query_update = "UPDATE Tema SET tema = '$tema' WHERE id_tema = $id_tema";
                # Execute the update query
                if ($result = mysqli_query($this->db, $query_update)) {
                    # Everything was ok
                    return (array("status" => 1, "message" => "Tema editado exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información del tema"));
                }
            } else {
                # Theme does not exists
                return (array("status" => 101, "message" => "El tema no se encuentra en la base de datos: " . $id_tema . " ." . mysqli_error($this->db)));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Editar tema: Algo salió mal al validar el tema."));
        }
    }

    # Delete theme

    /**
     * @param $id_tema
     * @return array
     */
    function delete_theme($id_tema)
    {
        # Check that the theme actually exists
        $query_validate = "SELECT 1 FROM Tema WHERE id_tema = '$id_tema'";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            # Check the number of results
            if ($result->num_rows < 1) {
                # The theme does not exists
                return (array("status" => 100, "message" => "No existe el tema a eliminar."));
            } else {
                # Delete the theme from the database
                # Query to eliminate the theme
                $query_delete = "DELETE FROM Tema WHERE id_tema = '$id_tema'";

                # Execute the deletion
                if ($result = mysqli_query($this->db, $query_delete)) {
                    # the theme was deleted
                    return array("status" => 1, "message" => "Tema eliminado exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar el tema." . mysqli_error($this->db)));
                }
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Borrar tema: Algo salió mal al validar el tema."));
        }
    }
    //</editor-fold>

    //<editor-fold desc="Exemplar functions">
    # Get the list of exemplar
    /**
     * @return array
     */
    function get_exemplars()
    {
        # Query that selects all the examplers from the table
        $query = "SELECT * FROM Ejemplar";
        if ($result = mysqli_query($this->db, $query)) {
            # Check for the number of examplers
            if ($result->num_rows > 0) {
                $examplers = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    array_push($examplers, $row);
                }
                $examplers['status'] = 1;
                return ($examplers);
            } else {
                # The themes table is empty
                return (array("status" => 2, "message" => "No se han agregado ejemplares."));
            }
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información de los ejemplares."));
        }
    }

    /**
     * @param $id
     * @return array|null
     */
    function get_specific_exemplar($id)
    {
        # Query that select the specific exemplar
        $query = "SELECT * FROM Ejemplar WHERE id_datosrevista = $id";

        if ($result = mysqli_query($this->db, $query)) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Algo salió mal obtener la información del ejemplar."));
        }
    }


    # Insert a new exampler

    /**
     * @param $ISSN
     * @param $numero_ejemplar
     * @param $precio_revista
     * @param $año_revista
     * @param $mes_revista
     * @param $semana_revista
     * @return array
     */
    function add_exampler($ISSN, $numero_ejemplar, $precio_revista, $año_revista, $mes_revista, $semana_revista)
    {
        /*
     * id_datosrevista
     * ISSN
     * numero_ejemplar
     * precio_revista
     * año_revista
     * mes_revista
     * semana_revista
     */
        # Create the query
        $query = "INSERT INTO Ejemplar ( ISSN, numero_ejemplar, precio_revista, año_revista, mes_revista, semana_revista ) " .
            "VALUES ( '$ISSN', '$numero_ejemplar', '$precio_revista', '$año_revista', '$mes_revista', '$semana_revista' )";

        if ($result = mysqli_query($this->db, $query)) {
            # Everything went right
            return (array("status" => 1, "message" => "El ejemplar se agregó correctamente."));
        } else {
            # Something went wrong
            return (array("status" => 0, "message" => "Algo salió mal al insertar un ejemplar."));
        }
    }

    # Edit a exampler

    /**
     * @param $id_datosrevista
     * @param $ISSN
     * @param $numero_ejemplar
     * @param $precio_revista
     * @param $año_revista
     * @param $mes_revista
     * @param $semana_revista
     * @return array
     */
    function edit_exampler($id_datosrevista, $ISSN, $numero_ejemplar, $precio_revista, $año_revista, $mes_revista, $semana_revista)
    {
        # Check that the exampler exists
        $query_validate = "SELECT * FROM Ejemplar WHERE id_datosrevista = $id_datosrevista";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            if ($result->num_rows > 0) {
                # Update the exampler
                $query_update = "UPDATE Ejemplar SET ISSN = '$ISSN', numero_ejemplar = '$numero_ejemplar', precio_revista = '$precio_revista'" .
                    " año_revista = '$año_revista', mes_revista = '$mes_revista', semana_revista = '$semana_revista' WHERE id_datosrevista = $id_datosrevista";
                # Execute the update query
                if ($result = mysqli_query($this->db, $query_update)) {
                    # Everything was ok
                    return (array("status" => 1, "message" => "Ejemplar editado exitosamente."));
                } else {
                    # If something went wrong
                    return (array("status" => 100, "message" => "Error al actualizar la información del ejemplar"));
                }
            } else {
                # Exampler does not exists
                return (array("status" => 101, "message" => "El ejemplar no se encuentra en la base de datos: " . $id_datosrevista . " ." . mysqli_error($this->db)));
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Editar ejemplar: Algo salió mal al validar el ejemplar."));
        }
    }

    /**
     * @param $id_datosrevista
     * @return array
     */
    function delete_exampler($id_datosrevista)
    {
        # Check that the exampler actually exists
        $query_validate = "SELECT 1 FROM Ejemplar WHERE id_datosrevista = $id_datosrevista";

        # Execute the validation query
        if ($result = mysqli_query($this->db, $query_validate)) {
            # Check the number of results
            if ($result->num_rows < 1) {
                # The exampler does not exists
                return (array("status" => 100, "message" => "No existe el ejemplar a eliminar."));
            } else {
                # Delete the exampler from the database
                # Query to eliminate the exampler
                $query_delete = "DELETE FROM Ejemplar WHERE id_datosrevista = $id_datosrevista";

                # Execute the deletion
                if ($result = mysqli_query($this->db, $query_delete)) {
                    # the exampler was deleted
                    return array("status" => 1, "message" => "Ejemplar eliminado exitosamente.");
                } else {
                    # If something went wrong
                    return (array("status" => 101, "message" => "Existió un error al eliminar el ejemplar." . mysqli_error($this->db)));
                }
            }
        } else {
            # If something went wrong
            return (array("status" => 0, "message" => "Borrar ejemplar: Algo salió mal al validar el ejemplar."));
        }
    }
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
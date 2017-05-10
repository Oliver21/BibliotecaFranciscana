<?php

# Access the operation to do with a book
if (isset($_POST['action'])){
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action){
        # Select the list of books
        case 1:
            get_books($functions);
            break;
        # Select a specific book of the list
        case 2:
            get_specific_book($functions);
            break;
        # Edit book information
        case 3:
            edit_book($functions);
            break;
        # Add book to the table
        case 4:
            add_book($functions);
            break;
        # Delete book
        case 5:
            delete_book($functions);
            break;
        # Book - author association
        case 6:
            add_book_author($functions);
            break;
        # Book - colection association
        case 7:
            add_book_collection($functions);
            break;
        # Book - work association
        case 8:
            add_book_work($functions);
            break;
        # Book - serie association
        case 9:
            add_book_serie($functions);
            break;
        # Book - theme association
        case 10:
            add_book_theme($functions);
            break;
        default:
            echo json_encode( array("status" => 660, "message" => "Acción no válida.") );
            break;
    }

} else {
    echo json_encode( array("status" => 666, "message" => "No se recibió acción a realizar.") );
}

function add_book_author($functions){
    if ( isset($_POST['id_libro']) && isset($_POST['id_assoc']) ){
        $id_libro = $_POST['id_libro'];
        $id_assoc = $_POST['id_assoc'];
        if ( $id_libro != "" && $id_assoc != "" ){

        } else {
            echo json_encode( array("status" => 599, "message" => "No se recibió el identificador.") );
        }
    } else {
        echo json_encode( array("status" => 599, "message" => "No se recibió el identificador.") );
    }
}

# get_books
/**
 * @param $functions
 */
function get_books($functions){
    $result = $functions->get_books();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_book
/**
 * @param $functions
 */
function get_specific_book($functions){
    $id = $_POST['id'];

    if ( isset($id) && $id != "" ){
        echo json_encode( $functions->get_specific_book($id) );
    } else {
        echo json_encode( array("status" => 599, "message" => "No se recibió el identificador.") );
    }
}

/**
 * @param $functions
 */
function delete_book($functions){
    $id_libro = $_POST['id_libro'];
    
    echo json_encode( $functions->delete_book($id_libro) );
}

# edit_book
/**
 * @param $functions
 */
function edit_book($functions){
    $id_libro = $_POST['id_libro'];
    $isbn = $_POST['ISBN'];
    $titulo_libro = $_POST['titulo_libro'];
    $subtitulo_libro = $_POST['subtitulo_libro'];
    $titulo_original = $_POST['titulo_original'];
    $numero_paginas = $_POST['numero_paginas'];
    $id_editorial = $_POST['id_editorial'];
    $numero_edicion = $_POST['numero_edicion'];
    $fecha_edicion = $_POST['fecha_edicion'];
    $lugar_publicacion = $_POST['lugar_publicacion'];
    $fecha_adquisicion = $_POST['fecha_adquisicion'];
    $costo_libro = $_POST['costo_libro'];
    $proveedor_libro = $_POST['proveedor_libro'];
    $observaciones_libro = $_POST['observaciones_libro'];
    $id_seccion = $_POST['id_seccion'];
    $id_apartado = $_POST['id_apartado'];
    $volumen_libro = $_POST['volumen_libro'];
    $ilustraciones = $_POST['ilustraciones'];
    $graficas = $_POST['graficas'];
    $mapas = $_POST['mapas'];
    $bibliografia = $_POST['bibliografia'];
    $indice = $_POST['indice'];
    $pasta_blanda = $_POST['pasta_blanda'];
    $planos = $_POST['planos'];
    $estatus = $_POST['estatus'];
    $numero_copias = $_POST['numero_copias'];
    $palabras_clave = $_POST['palabras_clave'];

    $result = $functions->edit_book($id_libro, $isbn, $titulo_libro, $subtitulo_libro, $titulo_original, $numero_paginas, $id_editorial,
        $numero_edicion, $fecha_edicion, $lugar_publicacion, $fecha_adquisicion, $costo_libro, $proveedor_libro, $observaciones_libro,
        $id_seccion, $id_apartado, $volumen_libro, $ilustraciones, $graficas, $mapas, $bibliografia, $indice, $pasta_blanda, $planos,
        $estatus, $numero_copias, $palabras_clave);

    echo json_encode( $result );
}

# add_book
/**
 * @param $functions
 */
function add_book($functions){
    $isbn = $_POST['ISBN'];
    $titulo_libro = $_POST['titulo_libro'];
    $subtitulo_libro = $_POST['subtitulo_libro'];
    $titulo_original = $_POST['titulo_original'];
    $numero_paginas = $_POST['numero_paginas'];
    $id_editorial = $_POST['id_editorial'];
    $numero_edicion = $_POST['numero_edicion'];
    $fecha_edicion = $_POST['fecha_edicion'];
    $lugar_publicacion = $_POST['lugar_publicacion'];
    $fecha_adquisicion = $_POST['fecha_adquisicion']; //pendiente
    $costo_libro = $_POST['costo_libro'];
    $proveedor_libro = $_POST['proveedor_libro'];
    $observaciones_libro = $_POST['observaciones_libro']; //pendiente
    $id_seccion = $_POST['id_seccion'];
    $id_apartado = $_POST['id_apartado'];
    $volumen_libro = $_POST['volumen_libro'];
    $ilustraciones = $_POST['ilustraciones'];
    $graficas = $_POST['graficas'];
    $mapas = $_POST['mapas'];
    $bibliografia = $_POST['bibliografia'];
    $indice = $_POST['indice'];
    $pasta_blanda = $_POST['pasta_blanda'];    
    $planos = $_POST['planos'];
    $estatus = $_POST['estatus'];
    $numero_copias = $_POST['numero_copias'];
    $palabras_clave = $_POST['palabras_clave']; //pendiente

    if ( $titulo_libro != "" && $titulo_original != "" ){
        $result = $functions->add_book($isbn, $titulo_libro, $subtitulo_libro, $titulo_original, $numero_paginas, $id_editorial, $numero_edicion,
            $fecha_edicion, $lugar_publicacion, $fecha_adquisicion, $costo_libro, $proveedor_libro, $observaciones_libro, $id_seccion, $id_apartado,
            $volumen_libro, $ilustraciones, $graficas, $mapas, $bibliografia, $indice, $pasta_blanda, $planos, $estatus, $numero_copias, $palabras_clave);
        echo json_encode( $result );
    } else {
        echo json_encode(array("status" => 601, "message" => "No se recibió la información del libro."));
    }

}
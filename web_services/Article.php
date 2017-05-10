<?php
# Access the operation to do with a article
if (isset($_POST['action'])) {
    # Get the functions file
    require_once "Functions/Data_Functions.php";
    $functions = new Functions();

    # Store the action in a variable
    $action = $_POST['action'];

    # Perform the action
    switch ($action) {
        # Select the list of articles
        case 1:
            get_articles($functions);
            break;
        case 2:
            get_specific_article($functions);
            break;
        case 3:
            edit_article($functions);
            break;
        case 4:
            add_article($functions);
            break;
        case 5:
            delete_article($functions);
            break;
        case 6:
            add_article_author($functions);
            break;
        default:
            echo json_encode(array("status" => 600, "message" => "Acción no válida."));
    }

} else {
    echo json_encode(array("status" => 666, "message" => "No se recibió acción a realizar."));
}

/**
 * @param $functions
 */
function add_article_author($functions){
    if ( isset($_POST['idArticulo']) && isset( $_POST['idAutor'])) {
        $idArticulo = $_POST['idArticulo'];
        $idAutor = $_POST['idAutor'];

        if ( $idArticulo != "" && $idAutor != ""){
            echo json_encode( $functions->add_article_author($idArticulo, $idAutor) );
        } else {
            echo json_encode(array("status" => 0, "message" => "No se recibió información necesaria para la asociación de artículo con autor."));
        }
    } else {
        echo json_encode(array("status" => 0, "message" => "No se recibió información necesaria para la asociación de artículo con autor."));
    }
}

# delete article
/**
 * @param $functions
 */
function delete_article($functions)
{
    if (isset($_POST['id_articulo'])) {
        $id_articulo = $_POST['id_articulo'];
        # Display the result
        echo json_encode($functions->delete_article($id_articulo));
    } else {
        echo json_encode(array("status" => 0, "message" => "No se recibió el artículo a borrar."));
    }
}

# add_article
/**
 * @param $functions
 */
function add_article($functions)
{
    /*
     * id_articulo
     * id_revista
     * nombre_articulo
     * cantidad_paginas
     * numero_ejemplar
     * año_articulo
     * reseña_articulo
     */
    $nombre_articulo = $_POST['nombre_articulo'];
    if (isset($nombre_articulo) && $nombre_articulo != "") {
        $id_revista = $_POST['id_revista'];
        $nombre_articulo = $_POST['nombre_articulo'];
        $cantidad_paginas = $_POST['cantidad_paginas'];
        $numero_ejemplar = $_POST['numero_ejemplar'];
        $año_articulo = $_POST['año_articulo'];
        $reseña_articulo = $_POST['reseña_articulo'];
        echo json_encode($functions->add_article($id_revista, $nombre_articulo, $cantidad_paginas, $numero_ejemplar, $año_articulo, $reseña_articulo));
    } else {
        echo json_encode(array("status" => 601, "message" => "No se recibió el nombre del artículo."));
    }
}

# get_articles
/**
 * @param $functions
 */
function get_articles($functions)
{
    $result = $functions->get_articles();

    # Display the result whatever its status is
    echo json_encode($result);
}

# get_specific_article
/**
 * @param $functions
 */
function get_specific_article($functions)
{
    $id = $_POST['id'];

    if ( isset($id) && $id != "" ){
        echo json_encode( $functions->get_specific_article($id) );
    } else {
        echo json_encode( array("status" => 599, "message" => "No se recibió el identificador.") );
    }
}

# edit_article
/**
 * @param $functions
 */
function edit_article($functions)
{
    $id_articulo = $_POST['id_articulo'];
    $id_revista = $_POST['id_revista'];
    $nombre_articulo = $_POST['nombre_articulo'];
    $cantidad_paginas = $_POST['cantidad_paginas'];
    $numero_ejemplar = $_POST['numero_ejemplar'];
    $año_articulo = $_POST['año_articulo'];
    $reseña_articulo = $_POST['reseña_articulo'];

    # Display the result
    echo json_encode($functions->edit_article($id_articulo, $id_revista, $nombre_articulo, $cantidad_paginas, $numero_ejemplar, $año_articulo, $reseña_articulo));
}
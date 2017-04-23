/*

</thead>
    */

$(document).on('ready', function () {
    $.post('../web_services/Books.php', { action : 1},
        function(returnedData){
            var libros = JSON.parse(returnedData);

            if (libros.status == 1){
                console.log("Carga de libros exitosa.");
                console.log( libros );
                
                for (var i = 0; i < libros.books.length; i++){
                    htmlAdicional = "<tr id='book_" + i + "'></tr>";
                    $('#books_append').append(htmlAdicional);
                    var book = libros.books[i];
                    var longitud = libros.books[i].length; 
                    for (var j = 0; j < longitud; i++){
                        htmlAdicional = "<td>" + book[j] + "</td>";
                        $('#book_' + i).append(htmlAdicional);
                    }
                }
                alert("Terminó la carga.");
            } else {
                console.log("Algo salió mal al cargar los libros.");
            }

            /*
            console.log( JSON.parse(returnedData).message);
            if (JSON.parse(returnedData).message == "Usuario no encontrado."){
                Materialize.toast('No se ha podido iniciar sesión!', 4000);
            } else if (JSON.parse(returnedData).message == "Inicio exitoso."){
                Materialize.toast('Bienvenido ' + user, 40000);
                window.location.href = "../html/menu.html";
            }*/
        });
});
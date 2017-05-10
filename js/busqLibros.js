$('#addLibroButton').click(function () {
    // Cargar el dropdown de seccion, editorial, autor y apartado
    $.post('../web_services/Authors.php', {
            action: 1
        },
        function (returnedData) {
            console.log(JSON.parse(returnedData));
            options = JSON.parse(returnedData);

            var text = "";
            for (var i = 0; i < options.length; i++){
                text = "<option value=" + options[i].id_autor  +">"+ ( options[i].nombre_autor + " " +options[i].apaterno_autor ) + "</option>";
                $('#autor_select').append(text);
            }

    //        "<option value="1">Option 1</option>";
      //      Materialize.toast(JSON.parse(returnedData).message, 4000);
        });
});
$('#addLibroButton').click(function () {
    // Cargar el dropdown de seccion, editorial, autor y apartado
    $.post('../web_services/Sections.php', {
            action: 1
        },
        function (returnedData) {
            // console.log(JSON.parse(returnedData));
            options = JSON.parse(returnedData);

            console.log(options[0]);
            console.log(options.length);

            var text = "";
            for (var i = 0; i < options.length; i++){
                text = "<option value=" + options[i].id_seccion  +">"+ options[i].nombre_seccion + "</option>";
                $('#section_select').append(text);
            }

    //        "<option value="1">Option 1</option>";
      //      Materialize.toast(JSON.parse(returnedData).message, 4000);
        });
});
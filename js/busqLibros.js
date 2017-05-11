$('#addLibroButton').click(function () {
    // Cargar el dropdown de seccion, editorial, autor y apartado
    $.post('../web_services/Sections.php', {
            action: 1
        },
        function (returnedData) {
            // console.log(JSON.parse(returnedData));
            var options = JSON.parse(returnedData);
            var sections = options['sections'];

            var text = "";
            text = "<option value='3'>Test</option>";
            $('#section_select').append(text);

            for (var i = 0; i < sections.length; i++){
                var seccion = sections[i];
                // console.log(sections[i]);
                text = "<option value='" + seccion.id_seccion  +"'>"+ seccion.nombre_seccion + "</option>";
                $('#section_select').append(text);

                // Añadir también a los spans
                $('.dropdown-content .select-dropdown').append("<li><span>" + seccion.nombre_seccion  + "</span></li>");

            }

    //        "<option value="1">Option 1</option>";
      //      Materialize.toast(JSON.parse(returnedData).message, 4000);
        });
});
$('#addLibroButton').click(function () {
    // Cargar el dropdown de seccion, editorial, autor y apartado
    $.post('../web_services/Authors.php', {
            action: 1
        },
        function (returnedData) {
            console.log(returnedData);
            Materialize.toast(JSON.parse(returnedData).message, 4000);
        });
});
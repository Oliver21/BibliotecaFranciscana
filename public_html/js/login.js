$('#login').click(function () {
    var user = document.getElementById('usuario').value;
    var pass = document.getElementById('password').value;

    $.post('../web_services/Users.php', { action : 2, Username : user, Contrasena : pass},
        function(returnedData){
        	console.log( JSON.parse(returnedData).message);
        	if (JSON.parse(returnedData).message == "Usuario no encontrado."){
        		Materialize.toast('No se ha podido iniciar sesión!', 4000);
        	} else if (JSON.parse(returnedData).message == "Inicio exitoso."){
            Materialize.toast('Bienvenido ' + user, 40000);
            window.location.href = "../html/menu.html";
        }
        });
});
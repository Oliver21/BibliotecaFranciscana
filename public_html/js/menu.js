$('#adduser').click(function () {
	var nombre = document.getElementById('nombre').value;
	var apellidop = document.getElementById('apellidopaterno').value;
	var apellidom = document.getElementById('apellidomaterno').value;
	var username = document.getElementById('username').value;
	var contrasena = document.getElementById('password').value;
	var telefono = document.getElementById('telefono').value;
	var correo = document.getElementById('mail').value;
	var grado = document.getElementById('grado').value;
	var direccion = document.getElementById('direccion').value;
	var instituto = document.getElementById('instituto').value;
	var tipo = document.getElementById('tipo').value;

	    $.post('../web_services/Users.php', { action : 1, Nombre : nombre, Ap_Paterno : apellidop, Ap_Materno : apellidom,
	    	Username : username, Tipo_Usuario : tipo, Contrasena : contrasena, Grado : grado, Telefono : telefono,
	    	Correo : correo, Direccion : direccion, Instituto_Proveniencia : instituto},
        function(returnedData){
        	console.log(returnedData);
        	/*if (JSON.parse(returnedData).message == "Usuario no encontrado."){
        		Materialize.toast('No se ha podido iniciar sesión!', 4000);
        	} else if (JSON.parse(returnedData).message == "Inicio exitoso."){
            Materialize.toast('Bienvenido ' + user, 40000);
            window.location.href = "../html/menu.html"; 
        } */
        });

});
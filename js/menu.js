$('#adduser').click(function () {
	console.log("NUEVO USUARIO");
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


$('#addEditorial').click(function () {
	console.log("Agregamos Editorial");
	var nombre = document.getElementById('nombreEditorial').value;
	var lugar = document.getElementById('lugarEditorial').value;

	    $.post('../web_services/Editorial.php', { action : 4, nombre_editorial : nombre, nombre_direccion : lugar},
        function(returnedData){
        	console.log(returnedData);
        	if (JSON.parse(returnedData).message == "."){
        		Materialize.toast('Se necesita un nombre en la editorial!', 4000);
        	} else if (JSON.parse(returnedData).message == "Inicio exitoso."){
            Materialize.toast('Editorial agregada a la base de datos');
            window.location.href = "../html/menu.html"; 
        } 
        });
});



$('#addSeccion').click(function () {
	console.log("Agregamos Seccion");
	var nombre = document.getElementById('nombreSeccion').value;

	    $.post('../web_services/Sections.php', { action : 4, nombre_seccion : nombre},
        function(returnedData){
        	console.log(returnedData);
        	if (JSON.parse(returnedData).message == "No se recibió la sección."){
        		Materialize.toast('Se necesita un nombre en la seccion!', 4000);
        	} else if (JSON.parse(returnedData).message == "Inicio exitoso."){
            Materialize.toast('Seccion agregada a la base de datos');
            window.location.href = "../html/menu.html"; 
        } 
        });
});


$('#addLibro').click(function () {
	console.log("Agregamos Libro");
	var titulolibro = document.getElementById('titulolibro').value;
	var isbn = document.getElementById('isbn').value;
	var titulooriginallibro = document.getElementById('titulooriginallibro').value;
	var subtitulolibro = document.getElementById('subtitulolibro').value;
	var edicionlibro = document.getElementById('edicionlibro').value;
	var lugarpublicacionlibro = document.getElementById('lugarpublicacionlibro').value;
	var fechalibro = document.getElementById('fechalibro').value;
    var copiaslibro = document.getElementById('copiaslibro').value;
    var costolibro = document.getElementById('costolibro').value;
    var paginaslibro = document.getElementById('paginaslibro').value;
    var proovedorlibro = document.getElementById('proovedorlibro').value;
    var volumenlibro = document.getElementById('volumenlibro').value;
    var seccionlibro = document.getElementById('seccionlibro').value;
    var editoriallibro = document.getElementById('editoriallibro').value;
    var autorlibro = document.getElementById('autorlibro').value;
    var ilustraciones = document.getElementById('ilustraciones').checked;
    var graficas = document.getElementById('graficas').checked;
    var mapas = document.getElementById('mapas').checked;
    var bibliografia = document.getElementById('bibliografia').checked;
    var pasta = document.getElementById('pasta').checked;
    var planos = document.getElementById('planos').checked;
    var disponible = document.getElementById('disponible').checked;
    var indice = document.getElementById('indice').checked;
    var apartado = document.getElementById('apartado').value;

    if (ilustraciones){
        ilustraciones=1;
    } else{
        ilustraciones=0; }

    if (graficas){
        graficas=1;
    } else{
        graficas=0; }

    if (mapas){
        mapas=1;
    } else{
        mapas=0; }

    if (bibliografia){
        bibliografia=1;
    } else{
        bibliografia=0; }

    if (pasta){
        pasta=1;
    } else{
        pasta=0; }

    if (planos){
        planos=1;
    } else{
        planos=0; }

    if (disponible){
        disponible=1;
    } else{
        disponible=0; }

    if (indice){
        indice=1;
    } else{
        indice=0; }

    console.log(ilustraciones);

	$.post('../web_services/Books.php', { action : 4, ISBN : isbn, titulo_libro : titulolibro, subtitulo_libro : subtitulolibro,
        titulo_original : titulooriginallibro, numero_paginas : paginaslibro, id_editorial : editoriallibro,
        numero_edicion : edicionlibro, fecha_edicion : fechalibro, lugar_publicacion : lugarpublicacionlibro, costo_libro : costolibro,
        proovedor_libro : proovedorlibro, id_seccion : seccionlibro, ilustraciones : ilustraciones, graficas : graficas,
        mapas : mapas, bibliografia : bibliografia, pasta_blanda : pasta, planos : planos,
        numero_copias : copiaslibro, estatus : disponible, id_apartado : apartado, indice : indice, volumen_libro : volumenlibro},   
        function(returnedData){
        	console.log(returnedData);
        	if (JSON.parse(returnedData).message == "No se recibió la sección."){
        		Materialize.toast('Se necesita un nombre en la seccion!', 4000);
        	} else if (JSON.parse(returnedData).message == "Inicio exitoso."){
            Materialize.toast('Libro agregado a la base de datos');
            window.location.href = "../html/menu.html"; 
        } 
        });
});

$('#addRevista').click(function () {
    console.log("Agregamos Revista");
    var nombrerevista = document.getElementById('nombrerevista').value;
    var seccionrevista = document.getElementById('seccionrevista').value;
    var editorialrevista = document.getElementById('editorialrevista').value;
    var periodicidad = document.getElementById('periodicidad').value;
    var palabrasclave = document.getElementById('palabrasclave').value;
    var issnrevista = document.getElementById('issnrevista').value;
    var numeroejemplarrevista = document.getElementById('numeroejemplarrevista').value;
    var preciorevista = document.getElementById('preciorevista').value;
    var anorevista = document.getElementById('anorevista').value;
    var mesrevista = document.getElementById('mesrevista').value;
    var semanarevista = document.getElementById('semanarevista').value;


    console.log(issnrevista);

    // var nombre = document.getElementById('nombreSeccion').value;

/*    $.post('../web_services/Sections.php', { action : 4, nombre_seccion : nombre},
        function(returnedData){
            console.log(returnedData);
            if (JSON.parse(returnedData).message == "No se recibió la sección."){
                Materialize.toast('Se necesita un nombre en la seccion!', 4000);
            } else if (JSON.parse(returnedData).message == "Inicio exitoso."){
                Materialize.toast('Seccion agregada a la base de datos');
                window.location.href = "../html/menu.html";
            }
        });
        */
});
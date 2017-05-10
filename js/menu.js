
$('#pagNext').click(function () {

	var dataObject = $('#pagPrev').val();


	var nombre = document.getElementById('nombreSeccion').value;

	$.post('../web_services/Sections.php', { action : 4, nombre_seccion : nombre},
	function(returnedData){
		console.log(returnedData);
		Materialize.toast(JSON.parse(returnedData).message, 4000);
	});
});


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
			Materialize.toast(JSON.parse(returnedData).message, 4000);
		});
	});


	$('#addEditorial').click(function () {
		console.log("Agregamos Editorial");
		var nombre = document.getElementById('nombreEditorial').value;
		var lugar = document.getElementById('lugarEditorial').value;

		$.post('../web_services/Editorial.php', { action : 4, nombre_editorial : nombre, nombre_direccion : lugar},
		function(returnedData){
			console.log(returnedData);
			Materialize.toast(JSON.parse(returnedData).message, 4000);
		});
	});



	$('#addSeccion').click(function () {
		console.log("Agregamos Seccion");
		var nombre = document.getElementById('nombreSeccion').value;

		$.post('../web_services/Sections.php', { action : 4, nombre_seccion : nombre},
		function(returnedData){
			console.log(returnedData);
			Materialize.toast(JSON.parse(returnedData).message, 4000);
		});
	});


	$('#addColeccion').click(function () {
		console.log("Agregamos Seccion");
		var nombreColeccion = document.getElementById('nombreColeccion').value;
		var numeroColeccion = document.getElementById('numeroColeccion').value;
		var volumenColeccion = document.getElementById('volumenColeccion').value;
		var seccionColeccion = document.getElementById('seccionColeccion').value;

		$.post('../web_services/Collections.php', { action : 4, nombre_coleccion : nombreColeccion,
			numero_coleccion : numeroColeccion, volumenes : volumenColeccion, id_seccion : seccionColeccion},
		function(returnedData){
			console.log(returnedData);
			Materialize.toast(JSON.parse(returnedData).message, 4000);
		});
	});

		$('#addApartado').click(function () {
		console.log("Agregamos Seccion");
		var nombreApartado = document.getElementById('nombreApartado').value;
		var letraApartado = document.getElementById('letraApartado').value;
		var seccion = document.getElementById('seccionApartado').value;

		$.post('../web_services/Apartado.php', { action : 4, nombre_apartado :nombreApartado,
			id_apartado : letraApartado, id_seccion : seccion},
		function(returnedData){
			console.log(returnedData);
			Materialize.toast(JSON.parse(returnedData).message, 4000);
		});
	});


		$('#addObra').click(function () {
		console.log("Agregamos Seccion");
		var nombreObra = document.getElementById('nombreObra').value;
		var numeroTomo = document.getElementById('numeroTomo').value;

		$.post('../web_services/Work.php', { action : 4, nombre_obra : nombreObra,
			numero_tomo : numeroTomo},
		function(returnedData){
			console.log(returnedData);
			Materialize.toast(JSON.parse(returnedData).message, 4000);
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
												Materialize.toast(JSON.parse(returnedData).message, 4000);
											});
										});

										$('#addRevista').click(function () {
											console.log("Agregamos Revista");
											var nombrerevista = document.getElementById('nombrerevista').value;
											var seccionrevista = document.getElementById('seccionrevista').value;
											var editorialrevista = document.getElementById('editorialrevista').value;
											var periodicidad = document.getElementById('periodicidad').value;
											var palabrasclave = document.getElementById('palabrasclave').value;
											var notasadicionalesrevista = document.getElementById('notasrevista').value;

											/*
											-> esto va en ejemplar
											var numeroejemplarrevista = document.getElementById('numeroejemplarrevista').value;
											var preciorevista = document.getElementById('preciorevista').value;
											var anorevista = document.getElementById('anorevista').value;
											var mesrevista = document.getElementById('mesrevista').value;
											var semanarevista = document.getElementById('semanarevista').value; */

											$.post('../web_services/Magazines.php', { action : 4, id_seccion : seccionrevista, id_editorial: editorialrevista,
												nombre_revista: nombrerevista, periodicidad: periodicidad, palabras_clave: palabrasclave, notas_adicionales: notasadicionalesrevista},
												function(returnedData){
													console.log(returnedData);
													Materialize.toast(JSON.parse(returnedData).message, 4000);
												});

											});



											$('#addautor').click(function () {
												console.log("Agregamos Autor");
												var nombreautor = document.getElementById('nombreautor').value;
												var paternoautor = document.getElementById('paternoautor').value;
												var maternoautor = document.getElementById('maternoautor').value;
												var nacimientoautor = document.getElementById('nacimientoautor').value;
												var nacionalidadautor = document.getElementById('nacionalidadautor').value;

												$.post('../web_services/Authors.php', { action : 4, apaterno_autor : paternoautor, amaterno_autor: maternoautor,
													nombre_autor: nombreautor, nacimiento_autor: nacimientoautor, nacionalidad_autor: nacionalidadautor},
													function(returnedData){
														console.log(returnedData);
														Materialize.toast(JSON.parse(returnedData).message, 4000);
													});

												});

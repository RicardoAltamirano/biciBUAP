<?php	
    session_start();
	$tipoDeCuenta = $_SESSION['tipoCuenta'];
	if(!empty($tipoDeCuenta)){
		switch($tipoDeCuenta){
			default: //Ninguna cuenta
				//Redireccionar a login
				header('Location: ../index.php');
			break;	
			
			case 'VISITANTE': //Visitante
				//header('Location: ./admin/registro.php');
			break;
			
			case 'USUARIO': //Usuario
				//header('Location: ./admin/registro.php');
			break;
			
			case 'DASU': //DASU
				//header('Location: ./admin/registro.php');
			break;
			
			case 'ADMINISTRADOR': //Admin
				//No hacer nada
			break;
		}
	}else{
		//Redireccionar a login
		header('Location: ../index.php');
	}	
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>CABI</title>
  
  <!-- GOOGLE -->
  <meta name="google-signin-client_id" content="669947592480-5f5luj26v4tp1bg3tlmcu9oorm38vcvd.apps.googleusercontent.com">
  <meta name="google-signin-cookiepolicy" content="single_host_origin">
  <meta name="google-signin-scope" content="profile email">
  <!-- END GOOGLE -->

  <!-- Script JQUERY -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <!-- Material Design Theming -->
  <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.orange-indigo.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <script src="https://code.getmdl.io/1.1.3/material.min.js"></script>

  <!-- Diseño bootstrap -->
  <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->

  <!-- Google Sign In --> <!--Requerido para iniciar y cerrar sesion-->
  <script src="https://apis.google.com/js/platform.js" defer async></script>

  <!-- Import and configure the Firebase SDK -->
  <!-- These scripts are made available when the app is served or deployed on Firebase Hosting -->
  <!-- If you do not serve/host your project using Firebase Hosting see https://firebase.google.com/docs/web/setup -->
  <script src="https://www.gstatic.com/firebasejs/5.4.0/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/5.4.0/firebase-auth.js"></script>
  <script src="https://www.gstatic.com/firebasejs/5.4.0/firebase-database.js"></script>
  
  <!-- Font awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
  <!-- Bootstrap JS -->
  <script src="../js/bootstrap.js"></script>
  
  <!-- Bootstrap Estilos -->
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="../css/estilo.css">
  
  <!--FUENTE CHIDA-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet">
	  
	<!-- Generador de QR -->
	<script type="text/javascript" src="../js/jquery.qrcode.js"></script>
	<script type="text/javascript" src="../js/qrcode.js"></script>
	
	<!-- Notificaciones -->
	<link rel="stylesheet" type="text/css" href="../css/notify.css">
    <link rel="stylesheet" type="text/css" href="../css/prettify.css">
  
  <script>
  
	/*
	
	Tipos de cuenta:
	"usuario", "dasu","admin", "visitante"
	
	Los objetos de la BD son
	
	var objetoUsuario={
		uid: googleId,
		email: "correo@juan.com", //variable
		nombre: "Juan"
		aP: "Perez",
		aM: "Lopez",
		tipoCuenta: "usuario"
	};
	
	var objetoBicicleta={
		id: "AKSDAJS", //QR
		marca: "Bennotto"
		color: "Azul",
		rodada: "26",
		email: "correo@juan.com"
	};
	
	var objetoBitacora={
		id: "Adsasa", //variable
		idBici: "AKSDAJS"
		email: "correo@juan.com",
		fechaEntrada: "26/10/09 15:35:23",
		fechaSalida: "26/10/09 18:30:13"
		lugarEntrada: "A7"
		lugarSalida: "A1"
	};	
	*/
	
  
  
      // Initialize Firebase
      var config = {
		apiKey: "AIzaSyD1ukXpdQqBXv97Jus4V3F1jHTnSIQ9JHc",
		authDomain: "cabi-297ee.firebaseapp.com",
		databaseURL: "https://cabi-297ee.firebaseio.com",
		projectId: "cabi-297ee",
		storageBucket: "cabi-297ee.appspot.com",
		messagingSenderId: "669947592480"
	  };
      firebase.initializeApp(config);
	  
	  var secondaryApp = firebase.initializeApp(config, "Secondary");
	  	
	function getPerfilUsuario(){
		//Obtenemos el uid del usuario que está ya loggeado
		var userId = firebase.auth().currentUser.uid;
		//Se crea una referencia a la rama /usuarios
		var perfilRef = firebase.database().ref('/usuarios/'+userId);
		  
		//Se hace consulta por valor (cuando se quieren obtener todos)
		//perfilRef.orderByValue().on('value', function(snapshot) {
		//Esta es una busqueda específica, busca dentro de los objetos por nombre al que sea igual a Herbert
		//perfilRef.orderByChild("nombre").equalTo("Herbert").on('value', function(snapshot) { //on permite que el campo se actualice al instante sin necesidad de recargar
			//Consulta porque sabemos su uid (en la referencia) 
			perfilRef.orderByValue().on('value', function(snapshot) {
			//Se valida que exista nuestra consulta
			  if (snapshot.exists()){
				//Cuando solo tenemos un solo valor porque accedimos con su UID
					var objetoPerfil = snapshot.val();
					actualizarPerfil(objetoPerfil);

				//Si solo tenemos un valor pero sabemos su UID deberiamos acceder sin forEach
				
				//Cuando se van a mostrar todos los datos
				/*
					//Borrar datos anteriores
				  snapshot.forEach(function(child) {
					//y añadir dato por dato
				  });
				*/
			  }else{
				console.log('Busqueda no encontrada');
			  }
		  });
      }
	  
	function actualizarPerfil(perfil){
		var inputMuestraNombre = document.getElementById("inputMuestraNombre");
		inputMuestraNombre.innerHTML = perfil.nombre; //Se accede como atributo del objeto y se llena
	}
	
	function llenaTabla(){
          var location = window.location.href;
		  if(!location.includes("id")){
			  var perfilRef = firebase.database().ref('/usuarios');
			  perfilRef.orderByValue().on('value', function(snapshot) {
				  if (snapshot.exists()){
					  eliminarFilas();
					  snapshot.forEach(function(child) {
						  var objetoUsuario = child.val();
						  crearFila(objetoUsuario);
					  });
				  }
			  });
		}
	}

      function eliminarFilas(){
          var location = window.location.href;
          if(!location.includes("id")){
              var Table = document.getElementById("tableDatosUsuario");
              Table.innerHTML = "";
          }
      }
	  	  
	/*
	function isUserDisabled(uid){
		var user = firebase.auth().getUser(uid).then(function(){
			//Se obtuvo el usuario
			console.log(user.disabled);
		},function(){
			//No se pudo obtener dicho usuario
		});
	}
		  
	function suspenderCuenta(uid){		
		var user = firebase.auth().getUser(uid).then(function(){
			//Se obtuvo el usuario
			user.updateProfile({
				disabled: true
			}).then(function(mensaje) {
			  alert('El usuario se ha suspendido correctamente');
			},function(error) {
			  // An error happened.
			});
		},function(){
			//No se pudo obtener dicho usuario
		});
	}
	*/
		  
      function crearFila(usuario){
          var location = window.location.href;
          if(!location.includes("id")){
              var table = document.getElementById("tableDatosUsuario");
              var noFilas = table.rows.length;
              var row = table.insertRow(noFilas);
              var celdaTipoCuenta = row.insertCell(0);
              var celdaNombre = row.insertCell(1);
              var celdaAP = row.insertCell(2);
			  var celdaAM = row.insertCell(3);
			  var celdaEmail = row.insertCell(4);
			  var celdaBtnVer = row.insertCell(5);
			  //var celdaBtnSuspender = row.insertCell(6);

			  celdaTipoCuenta.innerHTML = usuario.tipoCuenta;
			  celdaNombre.innerHTML = usuario.nombre;
			  celdaAP.innerHTML = usuario.aP;
			  celdaAM.innerHTML = usuario.aM;
			  celdaEmail.innerHTML = usuario.email;
			  
              //Creando boton ver/editar
              var btnVer = document.createElement("BUTTON");			  
			  btnVer.className = "btn btn-info celdaBoton";
              btnVer.onclick = function(){
                  window.location.assign("http://cabi.dx.am/admin/usuarios.php?id="+usuario.uid);
              };
			  
              var tVer = document.createTextNode("Ver/Editar");
			  
			  //Creando boton suspender cuenta
			  /*
              var btnSuspender = document.createElement("BUTTON");
			  btnSuspender.className = "btn btn-danger celdaBoton";
			  btnSuspender.onclick = suspenderCuenta(usuario.uid);
			  
			  var tSuspender = document.createTextNode("Suspender");
			  */
			  
			  //Add texto
              btnVer.appendChild(tVer);		
			  //btnSuspender.appendChild(tSuspender);				  

              //Add boton a documento
              celdaBtnVer.appendChild(btnVer);
			  //celdaBtnSuspender.appendChild(btnSuspender);
          }
      }
	  	
	function getDatosUsuario(uid){
		//Se crea una referencia a la rama /usuarios
		var perfilRefUsuario = firebase.database().ref('/usuarios/'+uid);
		//Consulta porque sabemos su uid (en la referencia) 
		perfilRefUsuario.orderByValue().on('value', function(snapshot) {
		//Se valida que exista nuestra consulta
		  if (snapshot.exists()){
				//Cuando solo tenemos un solo valor porque accedimos con su UID			
				var objetoPerfil = snapshot.val();
				mostrarDatosUsuario(objetoPerfil);
			}else{
				console.log('Busqueda no encontrada');
			}
		});
	}
	
	function mostrarDatosUsuario(perfil){
		var inputNombre = document.getElementById("inputNombre");
		var inputAM = document.getElementById("inputAM");
		var inputAP = document.getElementById("inputAP");
		var inputTipoCuenta = document.getElementById("inputTipoCuenta");
		
		inputNombre.value = perfil.nombre; 
		inputAM.value = perfil.aM; 
		inputAP.value = perfil.aP; 
		
		switch(perfil.tipoCuenta){
			case 'USUARIO':
				inputTipoCuenta.selectedIndex = "0";
			break;
			
			case 'VISITANTE':
				inputTipoCuenta.selectedIndex = "1";
			break;
			
			case 'DASU':
				inputTipoCuenta.selectedIndex = "2";
			break;
			
			case 'ADMINISTRADOR':
				inputTipoCuenta.selectedIndex = "3";
			break;			
		}
	}
		
  function hayCamposVacios(){				
		var nombreUsuario = document.getElementById("inputNombre").value;
		var apUsuario = document.getElementById("inputAP").value;						
		return nombreUsuario == "" || apUsuario == "";
	}
		
		
	function actualizarDatosPerfil(uid){	
		if(!hayCamposVacios()){
			r = confirm('¿Actualizar los datos?');
			if(r == true){										
				var uidUser = uid;
				
				var seleccion = document.getElementById("inputTipoCuenta");
				var tipoCuenta = seleccion.options[seleccion.selectedIndex].text;
				
				/*
				var correo = document.getElementById('inputEmail').value;
				var pass = document.getElementById('inputPass').value;	
				*/
				
				var nombreUsuario = document.getElementById("inputNombre").value;
				var apUsuario = document.getElementById("inputAP").value;
				var amUsuario = document.getElementById("inputAM").value;
				
				var objetoUsuario = {
					nombre: nombreUsuario,
					aP: apUsuario,
					aM: amUsuario,
					tipoCuenta: tipoCuenta
				};

				/* Para cambiar email y password se debe reautenticar con una segunda instancia de BD
				secondaryApp.auth().signInWithEmailAndPassword(correo, pass).then(function(user){
					user.updateEmail('newyou@domain.com');
				});				
				
				var objetoUsuario = {
					email: correo, //variable
					nombre: nombreUsuario,
					aP: apUsuario,
					aM: amUsuario,
					tipoCuenta: tipoCuenta
				};	
				*/				
				
				firebase.database().ref('/usuarios/'+uidUser).update(objetoUsuario).then(function(mensaje){
					notificacion(' Usuario actualizado correctamente','success','bell'); //'danger','warning','success' : 'close','exclamation','bell'
				},function(error){
					notificacion(' Ha sucedido un error actualizando los datos del usuario','danger','close'); //'danger','warning','success' : 'close','exclamation','bell'
				});
				
				//Codigo para actualizar la bici
				/*
					var seleccionMarca = document.getElementById("inputMarcasBicicletas");
					var marcaBici = seleccionMarca.options[seleccionMarca.selectedIndex].text;
					
					var seleccionColor = document.getElementById("inputColores");
					var colorBici = seleccionColor.options[seleccionColor.selectedIndex].text;
					
					var rodadaBici = document.getElementById("inputRodada").value;
					
					//OBTENER QR AQUI
					var idBicicleta = "QRTEMPORAL"; //QR GENERADO, se puede usar UID y fecha y hora de registro
					
					var objetoBicicleta = {
						id: idBicicleta, //QR Solo si no tiene caracteres especiales
						marca: marcaBici,
						color: colorBici,
						rodada: rodadaBici,
						uid: uidUser
					};					
					firebase.database().ref('/bicicletas/'+uidUser+'/'+idBicicleta).set(objetoBicicleta).catch(function(error){
						alert('Ha sucedido un error registrando la bicicleta');
					});					
				*/				
			  }else{
				  //No continuar el registro
			  }
			  
		}else{
			notificacion(' Llena los capos marcados como obligatorios','warning','exclamation'); //'danger','warning','success' : 'close','exclamation','bell'
		}      
    }
		
	function handleInput(e) {
	   var ss = e.target.selectionStart;
	   var se = e.target.selectionEnd;
	   e.target.value = e.target.value.toUpperCase();
	   e.target.selectionStart = ss;
	   e.target.selectionEnd = se;
	}	
		
	function initApp() {
		// Auth state changes.
		// [START authstatelistener]
		firebase.auth().onAuthStateChanged(function(user){
			if (user) {
				getPerfilUsuario();
				llenaTabla();
			}else{
				//No ha accedido el usuario y no puede acceder a esta página
				$.ajax({ url: '../process/userManagement.php',
					data: {action: 'logout',tipoCuenta: ''},
					type: 'post',
					success:
						function() {
							window.location.replace("http://cabi.dx.am/");
						}
				});
              }
          });
		 		  
		document.getElementById('btnCerrarSesion').addEventListener('click', handleSignOut, false);
      }

      window.onload = function() {
		  gapi.load('auth2', function() {
			  gapi.auth2.init();
		  });
          initApp();
      };

  </script>
</head>

<body>

	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	  </button>
	  <a class="navbar-brand" href="registro.php">CABI</a>

	  <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
		<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
		  <li class="nav-item">
			<a class="nav-link" href="registro.php">Registro </a>
		  </li>
		  <li class="nav-item active">
			<a class="nav-link" href="usuarios.php">Usuarios</a>
		  </li>
		</ul>
		<form class="form-inline my-2 my-lg-0">
		  <button id="btnCerrarSesion" class="btn btn-danger my-2 my-sm-0" >Cerrar sesión <i class="fa fa-sign-out"></i></button>
		</form>
	  </div>
	</nav>

	<h1 class="text-center espacioArriba">BIENVENIDO <span id="inputMuestraNombre"><span></h1>
	<h2 class="text-center espacioArriba">Usuarios</h2>
			<?php
            if(isset($_GET["id"])){
                if(!empty($_GET["id"])){					
					$idUsuario = $_GET["id"];
					echo"
					<div class='contenedorCampos'>
						<h3 class='espacioArriba'>Actualizar usuario</h3>
						<div id='contenedorRegistroUsuario' class='text-center form-group required'>
							<!--
							<div class='contenedorEtiquetaCampo text-left'><span><b>Email </b></span><label class='control-label'></label></div><input id='inputEmail' type='mail'  >
							<div class='contenedorEtiquetaCampo text-left'><span><b>Contraseña </b></span><label class='control-label'></label></div><input id='inputPass' type='password'  >
							-->
							<div class='contenedorEtiquetaCampo text-left'><span><b>Nombre(s) </b></span><label class='control-label'></label></div><input id='inputNombre' type='mail'   oninput='handleInput(event)'>
							<div class='contenedorEtiquetaCampo text-left'><span><b>Apellido paterno </b></span><label class='control-label'></label></div><input id='inputAP' type='text'  oninput='handleInput(event)'>
							<div class='contenedorEtiquetaCampo text-left'><span><b>Apellido materno </b></span></div><input id='inputAM' type='text' oninput='handleInput(event)'>
							<div class='contenedorEtiquetaCampo text-left'><span><b>Tipo de cuenta </b></span></div><select id='inputTipoCuenta' name='inputTipoCuenta'>
								<option value='USUARIO'>USUARIO</option>
								<option value='VISITANTE'>VISITANTE</option>
								<option value='DASU'>DASU</option>
								<option value='ADMINISTRADOR'>ADMINISTRADOR</option>
							</select> <br>	
						</div>
					";
					?>
						<div class="text-center">
						<button id='btnActualizarDatos' class="btn btn-success aceptar" onclick="actualizarDatosPerfil(<?php echo"'$idUsuario'" ?>);return false;">Actualizar datos</button>
						</div>
					</div>
					<script type='text/javascript'>
						<?php
							echo "getDatosUsuario('$idUsuario');";
						?>
					</script>					
				<?php
				}else{
					echo"
                        <div id='contenedorInvalido'>
                            <h3>ID inválido</h3>
                            <p>El ID se encuentra vacío</p>
                        </div>
                    ";
				}			
			}else{
				//Mostrar tabla con usuarios
				?>				
				<div class='contenedorCamposTabla'>
					<h3 class='espacioArriba'>Todos los usuarios</h3>
					<input class="form-control" id="inputBusqueda" type="text" placeholder="Busca un usuario..."><br>
					<div class="table-responsive">
						<table id="tablaUsuarios" class="table table-bordered">
							<thead class="thead-light">
							  <tr>
								<th scope="col">Tipo Cuenta</th>
								<th scope="col">Nombre</th>
								<th scope="col">AP</th>
								<th scope="col">AM</th>
								<th scope="col">Correo</th>
								<th scope="col">Ver/Editar</th>
								<!--
								<th scope="col">Suspender</th>
								-->
							  </tr>
							</thead>					
							<tbody id='tableDatosUsuario'>
								<!-- Datos Insertados en java script -->
							</tbody>
					  </table>
				  </div>
				</div>
		<?php } ?>

	<!-- Scripts de notificaciones -->
	<script src="../js/notify.js"></script>
    <script src="../js/prettify.js"></script>		
		
  <!-- End custom js for this page-->
  <script type="text/javascript">
	function notificacion(mensaje,tipo,icono){			
		$.notify(mensaje, 
		{
			type: tipo, //'danger','warning','success'
			delay: 6000,
			animation : true,
			close: true,
			icon: icono //'close','exclamation','bell'					
		});			
	}
	  
	  function handleSignOut() {
          var googleAuth = gapi.auth2.getAuthInstance();
          googleAuth.signOut().then(function() {
              firebase.auth().signOut();
          });
      }	  
	  
	  $(document).ready(function(){
		  $("#inputBusqueda").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#tableDatosUsuario tr").filter(function() {
			  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		  });
		});	  
  </script>
</body>

</html>

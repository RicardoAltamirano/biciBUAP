<?php	
    session_start();
	$tipoDeCuenta = $_SESSION['tipoCuenta'];
	if(!empty($tipoDeCuenta)){
		switch($tipoDeCuenta){
			default: //Ninguna cuenta
				//Redireccionar a login
				header('Location: ../index.php');
			break;	
			
			case 'visitante': //Visitante
				//header('Location: ./admin/registro.php');
			break;
			
			case 'usuario': //Usuario
				//header('Location: ./admin/registro.php');
			break;
			
			case 'dasu': //DASU
				//header('Location: ./admin/registro.php');
			break;
			
			case 'admin': //Admin
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
              var Table = document.getElementById("tablaUsuarios");
              Table.innerHTML = "";
          }
      }
	  	  
      function crearFila(usuario){

          var location = window.location.href;
          if(!location.includes("id")){
              var table = document.getElementById("tablaUsuarios");
              var noFilas = table.rows.length;
              var row = table.insertRow(noFilas);
              var celdaTipoCuenta = row.insertCell(0);
              var celdaNombre = row.insertCell(1);
              var celdaAP = row.insertCell(2);
			  var celdaAM = row.insertCell(3);
			  var celdaEmail = row.insertCell(4);
			  var celdaBtnVer = row.insertCell(5);

			  celdaTipoCuenta.innerHTML = usuario.tipoCuenta;
			  celdaNombre.innerHTML = usuario.nombre;
			  celdaAP.innerHTML = usuario.aP;
			  celdaAM.innerHTML = usuario.aM;
			  celdaEmail.innerHTML = usuario.email;
			  
              //Creando boton ver/editar
              var btnVer = document.createElement("BUTTON");
              //btnVer.className = "btn btn-outline-primary";
              btnVer.onclick = function(){
                  window.location.assign("http://cabi.dx.am/admin/usuarios.php?id="+usuario.uid);
              };
			  
              var t = document.createTextNode("Ver/Editar");
              btnVer.appendChild(t);			  

              //Añadiento boton a documento
              celdaBtnVer.appendChild(btnVer);
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
			case 'usuario':
				inputTipoCuenta.selectedIndex = "0";
			break;
			
			case 'visitante':
				inputTipoCuenta.selectedIndex = "1";
			break;
			
			case 'dasu':
				inputTipoCuenta.selectedIndex = "2";
			break;
			
			case 'admin':
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
				
				var nombreUsuario = document.getElementById("inputNombre").value;
				var apUsuario = document.getElementById("inputAP").value;
				var amUsuario = document.getElementById("inputAM").value;
				
				var objetoUsuario = {
					nombre: nombreUsuario,
					aP: apUsuario,
					aM: amUsuario,
					tipoCuenta: tipoCuenta
				};				
				
				firebase.database().ref('/usuarios/'+uidUser).update(objetoUsuario).then(function(mensaje){
					alert('Usuario actualizado correctamente');
				},function(error){
					alert('Ha sucedido un error actualizando los datos del usuario');
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
			alert("Llena los capos marcados como obligatorios");
		}      
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
	<h1>Usuarios</h1>
	<p>Bienvenido <span id="inputMuestraNombre">Nombre<span></p>
			<?php
            if(isset($_GET["id"])){
                if(!empty($_GET["id"])){					
					$idUsuario = $_GET["id"];
					echo"
					<h2>Editar usuario</h2>
					<input id='inputNombre' type='mail'  placeholder='nombre'> <br>
					<input id='inputAP' type='text' placeholder='ap'> <br>
					<input id='inputAM' type='text' placeholder='am'> <br>
					<select id='inputTipoCuenta' name='inputTipoCuenta'>
						<option value='usuario'>usuario</option>
						<option value='visitante'>visitante</option>
						<option value='dasu'>dasu</option>
						<option value='admin'>admin</option>		
					</select> <br>
					";
					?>
					
					<button id='btnActualizarDatos' onclick="actualizarDatosPerfil(<?php echo"'$idUsuario'" ?>);return false;">Actualizar datos</button>
					
					<script type='text/javascript'>
						<?php
							echo "getDatosUsuario('$idUsuario');";
						?>
					</script>					
				<?php
				}else{
					echo"
                        <div id='contenedorInvalido'>
                            <h2>ID inválido</h2>
                            <p>El ID se encuentra vacío</p>
                        </div>
                    ";
				}			
			}else{
				//Mostrar tabla con usuarios
				?>				
				<h2>Estos son todos los usuarios</h2>
				<table>
					<thead>
					  <tr>
						<th>Tipo Cuenta</th>
						<th>Nombre</th>
						<th>AP</th>
						<th>AM</th>
						<th>Correo</th>
						<th>Ver/Editar</th>
					  </tr>
					</thead>
				</table>
				<table id='tablaUsuarios'>
				  <tbody>
					  <!-- Datos Insertados en java script -->
				  </tbody>
				</table>				
		<?php } ?>
	
	
	<button id="btnCerrarSesion">Cerrar sesión</button>
	
	
  <!-- End custom js for this page-->
  <script type="text/javascript">
      function handleSignOut() {
          var googleAuth = gapi.auth2.getAuthInstance();
          googleAuth.signOut().then(function() {
              firebase.auth().signOut();
          });
      }
	  
	  
  </script>
</body>

</html>

<?php	
    session_start();
	$tipoDeCuenta = $_SESSION['tipoCuenta'];
	if(!empty($tipoDeCuenta)){
		switch($tipoDeCuenta){
			default: //Sin cuenta
				//Redireccionar a una pantalla de error
				//header('Location: ./error/cuenta.php');
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
				header('Location: ./admin/registro.php');
			break;
		}
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
  <script src="./js/bootstrap.js"></script>
  
  <!-- Bootstrap Estilos -->
  <link rel="stylesheet" type="text/css" href="./css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="./css/estilo.css">
  
  <!--FUENTE CHIDA-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet">
  
  <!-- Notificaciones -->
	<link rel="stylesheet" type="text/css" href="./css/notify.css">
    <link rel="stylesheet" type="text/css" href="./css/prettify.css">
  
  <script>
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

	  function accederPorCorreo() {
		var email = document.getElementById('inputEmail').value;
		var pass = document.getElementById('inputPass').value;
				
		if(email != "" && pass != ""){
			firebase.auth().signInWithEmailAndPassword(email,pass).then(function(credencial){
				notificacion(' ¡Bienvenido!','success','bell'); //'danger','warning','success' : 'close','exclamation','bell'
			},			
			function(error) {
				var errorCode = error.code;
				var errorMessage = error.message;
				console.log(errorCode);			
				
				switch(errorCode){
					case 'auth/invalid-email':
						notificacion(' El email ingresado es inválido','warning','exclamation'); //'danger','warning','success' : 'close','exclamation','bell'
					break;
					case 'auth/user-disabled':
						notificacion(' Usuario deshabilitado','warning','exclamation'); //'danger','warning','success' : 'close','exclamation','bell'
					break;
					case 'auth/user-not-found':
						notificacion(' Usuario no encontrado','warning','exclamation'); //'danger','warning','success' : 'close','exclamation','bell'
					break;
					case 'auth/wrong-password':
						notificacion(' La contraseña es incorrecta','warning','exclamation'); //'danger','warning','success' : 'close','exclamation','bell'
					break;
					default:
						notificacion(' Se ha producido un error, intenta más tarde','warning','exclamation'); //'danger','warning','success' : 'close','exclamation','bell'
					break;			
				}				
			  });
		}else{
			notificacion(' Todos los campos son obligatorios','warning','exclamation'); //'danger','warning','success' : 'close','exclamation','bell'
		}      
    }
	
	function manejaUsuarioExistente(){
		
	var userId = firebase.auth().currentUser.uid;
	var referenciaInicio = firebase.database().ref('/usuarios/' + userId);

	referenciaInicio.once('value').then(function(snapshot) {
        if (snapshot.exists()){
			var tipoCuentaActual = (snapshot.val() && snapshot.val().tipoCuenta) || 'Anonymous';
			switch(tipoCuentaActual){
				case 'VISITANTE':
					alert('Aún no existe esta página');
					break;
				case 'USUARIO':
					alert('Aún no existe esta página');
					break;
				case 'DASU':
					alert('Aún no existe esta página');
					break;
				case 'ADMINISTRADOR':
					//Es Admin
					//Redirijimos a la cuenta de admin
					$.ajax({ url: './process/userManagement.php',
							data: {action: 'login',tipoCuenta: tipoCuentaActual},
							type: 'post',
							success:
								function() {							
									window.location.replace("http://cabi.dx.am/admin/");
								}
						});
					break;
			  default: break;
		  }
        }else{
			console.log('No existe usuario en nuestra BD');
        }
      });
    }
	  
      function initApp() {
          // Auth state changes.
          // [START authstatelistener]
          firebase.auth().onAuthStateChanged(function(user){
              if (user) {
					console.log("Hay un usuario");					
					document.getElementById('btnCerrarSesion').disabled = false;
					document.getElementById('btnAccederConCorreo').disabled = true;
					manejaUsuarioExistente();
              } else {
					console.log("El usuario no ha iniciado sesión");
					document.getElementById('btnCerrarSesion').disabled = true;
					document.getElementById('btnAccederConCorreo').disabled = false;
              }
          });
		  document.getElementById('btnCerrarSesion').addEventListener('click', cerrarSesion, false);
		  document.getElementById('btnAccederConCorreo').addEventListener('click', accederPorCorreo, false);
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
	<h1>CABI: Control de Acceso de Bicicletas</h1>
	
	<h2>Ingresar</h2>
	<input id="inputEmail" type="mail" class="form-control" placeholder="juan@mail.com">
	<input id="inputPass" type="password" class="form-control" placeholder="*********">
	<button id="btnAccederConCorreo">Acceder</button> 
	<button id="btnCerrarSesion">Cerrar sesión</button>
	
	<!-- Scripts de notificaciones -->
	<script src="./js/notify.js"></script>
    <script src="./js/prettify.js"></script>
	
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
	  
	  function cerrarSesion() {
          var googleAuth = gapi.auth2.getAuthInstance();
          googleAuth.signOut().then(function() {
              firebase.auth().signOut();
          });
      }
  </script>
</body>

</html>

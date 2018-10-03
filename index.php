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
				alert('Iniciaste sesión correctamente');
			},			
			function(error) {
				var errorCode = error.code;
				var errorMessage = error.message;
				console.log(errorCode);			
				
				switch(errorCode){
					case 'auth/invalid-email':
						alert("El email es inválido");
					break;
					case 'auth/user-disabled':
						alert("Usuario deshabilitado");
					break;
					case 'auth/user-not-found':
						alert("Usuario no encontrado");
					break;
					case 'auth/wrong-password':
						alert("La contraseña es incorrecta");
					break;
					default:
						alert("Se ha producido un error. Intenta más tarde.");
					break;			
				}				
			  });
		}else{
			alert("Todos los campos son obligatorios");
		}      
    }
	  
      function initApp() {
          // Auth state changes.
          // [START authstatelistener]
          firebase.auth().onAuthStateChanged(function(user){
              if (user) {
					console.log("Hay un usuario");
					document.getElementById('btnCerrarSesion').disabled = false;
					document.getElementById('btnAccederConCorreo').disabled = true;
                  // User is signed in
				  /*
                  protocoloDeSeguridad();
                  getPerfilUsuario();
				  */
              } else {
					console.log("El usuario no ha iniciado sesión");
					document.getElementById('btnCerrarSesion').disabled = true;
					document.getElementById('btnAccederConCorreo').disabled = false;
                  //Logout
				  /*
                  $.ajax({ url: '../process/userManagement.php',
                      data: {action: 'logout',tipoCuenta: ''},
                      type: 'post',
                      success: function() {
                                  window.location.replace("http://socketpwr.com/fisioterapp/");
                              }
                  });
				  */
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
	<p>Hola mundo!</p>
	<button id="btnCerrarSesion">Cerrar sesión</button>
	<p>ingresar</p>
	<input id="inputEmail" type="mail" class="form-control" placeholder="juan@mail.com">
	<input id="inputPass" type="password" class="form-control" placeholder="*********">
	<button id="btnAccederConCorreo">Acceder</button> 
	
	
  <!-- End custom js for this page-->
  <script type="text/javascript">
      function cerrarSesion() {
          var googleAuth = gapi.auth2.getAuthInstance();
          googleAuth.signOut().then(function() {
              firebase.auth().signOut();
          });
      }
  </script>
</body>

</html>

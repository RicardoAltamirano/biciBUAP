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
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
		uid: google account,
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
		uid: google uid
	};
	
	var objetoBitacora={
		id: "Adsasa", //variable
		idBici: "AKSDAJS"
		uid: google uid,
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

	  function hayCamposVacios(){
		 	var correo = document.getElementById('inputEmail').value;
			var pass = document.getElementById('inputPass').value;					
			var nombreUsuario = document.getElementById("inputNombre").value;
			var apUsuario = document.getElementById("inputAP").value;			
			var rodada = document.getElementById("inputRodada").value;
			
		if(!cuentaUsuarioSeleccionado()){			
			return correo == "" || pass == "" || nombreUsuario == "" || apUsuario == "";
		}else{
			return correo == "" || pass == "" ||
			nombreUsuario == "" || apUsuario == "" ||
			rodada == "";
		}
	  }
	  
	  function esRodadaValida() {
			let maximo = 29;
			let minimo = 14;
			var numeroActual = document.getElementById('inputRodada').value;
			if (numeroActual <= 13 || numeroActual > maximo) {
				return false;
			}else{return true;}
		}
	  
	  function obtenerFechaHora(){
		//Obtenemos fecha y hora
		var currentdate = new Date(); 
		var datetime = ''+currentdate.getDate()
						+(currentdate.getMonth()+1)
						+ currentdate.getFullYear()
						+ currentdate.getHours() 
						+ currentdate.getMinutes()
						+ currentdate.getSeconds();
		return datetime;
	  }
	  
      function registroPorCorreo() {
		var correo = document.getElementById('inputEmail').value;
		var pass = document.getElementById('inputPass').value;		
		
		var continuar = false;
		if(cuentaUsuarioSeleccionado()){
			if(esRodadaValida()){
				continuar = true;
			}
		}else{
			continuar = true;
		}
		
		if(!hayCamposVacios() && continuar){
			r = confirm('Comprueba que los datos sean correctos\n¿Continuar con el registro?');
			if(r == true){		
		
			secondaryApp.auth().createUserWithEmailAndPassword(correo,pass).then(function(credential){
				
				//Negamos que se inicie la sesion de esta cuenta creada
				secondaryApp.auth().signOut();
				
				//console.log("El UID es: "+credential.user.uid);
				//Los campos restantes deberan obtenerse mediante inputs obligatorios para registrar usuarios
				var uidUser = credential.user.uid;
				
				var seleccion = document.getElementById("inputTipoCuenta");
				var tipoCuenta = seleccion.options[seleccion.selectedIndex].text;
				
				var nombreUsuario = document.getElementById("inputNombre").value;
				var apUsuario = document.getElementById("inputAP").value;
				var amUsuario = document.getElementById("inputAM").value;
				
				var objetoUsuario = {
					uid: uidUser,
					email: correo, //variable
					nombre: nombreUsuario,
					aP: apUsuario,
					aM: amUsuario,
					tipoCuenta: tipoCuenta
				};				
				
				firebase.database().ref('/usuarios/'+uidUser).set(objetoUsuario).then(function(mensaje){
					notificacion(' Usuario actualizado correctamente','success','bell'); //'danger','warning','success' : 'close','exclamation','bell'
				},function(error){
					notificacion(' Ha sucedido un error registrando al usuario','danger','close'); //'danger','warning','success' : 'close','exclamation','bell'
				});
				
				if(cuentaUsuarioSeleccionado()){
					
					var seleccionMarca = document.getElementById("inputMarcasBicicletas");
					var marcaBici = seleccionMarca.options[seleccionMarca.selectedIndex].text;
					
					var seleccionColor = document.getElementById("inputColores");
					var colorBici = seleccionColor.options[seleccionColor.selectedIndex].text;
					
					var rodadaBici = document.getElementById("inputRodada").value;
					
					//Obtenemos fecha y hora
					
					
					//OBTENER QR AQUI
					var idBicicleta = uidUser +'_'+ obtenerFechaHora();
					
					var objetoBicicleta = {
						id: idBicicleta, //QR Solo si no tiene caracteres especiales
						marca: marcaBici,
						color: colorBici,
						rodada: rodadaBici,
						uid: uidUser
					};					
					firebase.database().ref('/bicicletas/'+uidUser+'/'+idBicicleta).set(objetoBicicleta).then(function(mensaje){						
						jQuery('#qrCanvas').qrcode({
							text	: idBicicleta
						});
						
						var contenedorBotonNuevo = document.getElementById('contenedorBotonNuevo');
						var btn = document.createElement("BUTTON");        // Create a <button> element
						
						var t = document.createTextNode("Registrar otro usuario");       // Create a text node
						btn.setAttribute("id","btnRegistrarOtroUsuario");
						btn.className = "btn btn-info aceptar";
						btn.addEventListener('click', ocultaInformacionCreada, false);
						btn.appendChild(t);                         // Append the text to <button>
						contenedorBotonNuevo.appendChild(btn);                    // Append <button> to <body>
							
						//Scroll al final de la página
						window.scrollTo(0,document.body.scrollHeight);
						
						notificacion(' Bicicleta registrada corretamente','success','bell'); //'danger','warning','success' : 'close','exclamation','bell'
						
					},function(error){
						notificacion(' Ha sucedido un error registrando la bicicleta','danger','close'); //'danger','warning','success' : 'close','exclamation','bell'
					});	
				}				
			},function(error) {
				var errorCode = error.code;
				var errorMessage = error.message;
				console.log(errorCode);			
				
				switch(errorCode){
					case "auth/email-already-in-use":
						notificacion(' El email ingresado ya está en uso','warning','exclamation'); //'danger','warning','success' : 'close','exclamation','bell'
					break;
					case "auth/invalid-email":
						notificacion(' Ingresa un email válido','warning','exclamation'); //'danger','warning','success' : 'close','exclamation','bell'
					break;
					case "auth/operation-not-allowed":
						notificacion(' Operación no autorizada','warning','exclamation'); //'danger','warning','success' : 'close','exclamation','bell'
					break;
					case "auth/weak-password":
						notificacion(' Contraseña débil, intenta ingresando otra','warning','exclamation'); //'danger','warning','success' : 'close','exclamation','bell'
					break;
					default:
						notificacion(' Se ha producido un error, intenta más tarde','warning','exclamation'); //'danger','warning','success' : 'close','exclamation','bell'
					break;			
				}				
			  });
			  }else{
				  //No continuar el registro
			  }
			  
		}else{
			if(!esRodadaValida()){
				notificacion(' Ingresa una rodada entre 14 y 29','warning','exclamation'); //'danger','warning','success' : 'close','exclamation','bell'
			}else{
				notificacion(' Llena los campos marcados como obligatorios','warning','exclamation'); //'danger','warning','success' : 'close','exclamation','bell'
			}			
		}      
    }
	
	function ocultaInformacionCreada(){
		var inputEmail = document.getElementById('inputEmail');
		var inputNombre = document.getElementById('inputNombre');
		var inputPass = document.getElementById('inputPass');
		var inputAP = document.getElementById('inputAP');
		var inputAM = document.getElementById('inputAM');
		var inputRodada = document.getElementById('inputRodada');
		
		var contenedorBotonNuevo = document.getElementById('contenedorBotonNuevo');		
		var contenedorQR = document.getElementById('qrCanvas');		
	
		while(contenedorBotonNuevo.firstChild){
			contenedorBotonNuevo.removeChild(contenedorBotonNuevo.firstChild);
		}
		
		while(contenedorQR.firstChild){
			contenedorQR.removeChild(contenedorQR.firstChild);
		}
		
		inputEmail.value = '';
		inputNombre.value = '';
		inputPass.value = '';
		inputAP.value = '';
		inputAM.value = '';
		inputRodada.value = '';	

		window.scrollTo(0,0);		
	}
	
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
	
	function listenerSelectTipoCuenta(){
		document.getElementById("inputTipoCuenta").onchange = function(e) {
			var divBici = document.getElementById("contenedorRegistroBici");			
			if(this[this.selectedIndex].text != "USUARIO"){
				divBici.style.display = "none";
			}else{
				divBici.style.display = "block";
			}
		};		
	}
	
	function cuentaUsuarioSeleccionado(){
		var seleccionaBici = document.getElementById("inputTipoCuenta");
		return seleccionaBici.options[seleccionaBici.selectedIndex].text == "USUARIO"; 		
	}	
	
	function handleInput(e) {
	   var ss = e.target.selectionStart;
	   var se = e.target.selectionEnd;
	   e.target.value = e.target.value.toUpperCase();
	   e.target.selectionStart = ss;
	   e.target.selectionEnd = se;
	}
	
	function enterPresionado(event) {
		event.preventDefault();
		if (event.keyCode === 13) {
			document.getElementById("btnRegistrarUsuario").click();
		}
	}
	
	function initApp() {
		// Auth state changes.
		// [START authstatelistener]
		firebase.auth().onAuthStateChanged(function(user){
			if (user) {
				getPerfilUsuario();
				listenerSelectTipoCuenta();
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
		  
		//Mandamos a llamar las funciones para los elementos
		  
		document.getElementById('btnRegistrarUsuario').addEventListener('click', registroPorCorreo, false);
		document.getElementById('btnCerrarSesion').addEventListener('click', handleSignOut, false);
		document.getElementById("inputEmail").addEventListener("keyup", enterPresionado,false);
		document.getElementById("inputPass").addEventListener("keyup", enterPresionado,false);
		document.getElementById("inputNombre").addEventListener("keyup", enterPresionado,false);
		document.getElementById("inputAP").addEventListener("keyup", enterPresionado,false);
		document.getElementById("inputAM").addEventListener("keyup", enterPresionado,false);
		document.getElementById("inputRodada").addEventListener("keyup", enterPresionado,false);		
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
		  <li class="nav-item active">
			<a class="nav-link" href="registro.php">Registro</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="usuarios.php">Usuarios</a>
		  </li>
		</ul>
		<form class="form-inline my-2 my-lg-0">
		  <button id="btnCerrarSesion" class="btn btn-danger my-2 my-sm-0" >Cerrar sesión <i class="fa fa-sign-out"></i></button>
		</form>
	  </div>
	</nav>

	<h1 class="text-center espacioArriba">BIENVENIDO <span id="inputMuestraNombre"><span></h1>
	<h2 class="text-center espacioArriba">Registro</h2>
	
	<div class="contenedorCampos">
	<h3 class="espacioArriba">Registrar usuario</h3>
	
		<div id="contenedorRegistroUsuario" class="text-center form-group required">
			<div class="contenedorEtiquetaCampo text-left"><span><b>Email </b></span><label class='control-label'></label></div><input id="inputEmail" type="mail"  >
			<div class="contenedorEtiquetaCampo text-left"><span><b>Contraseña </b></span><label class='control-label'></label></div><input id="inputPass" type="password"  > <br>
			<div class="contenedorEtiquetaCampo text-left"><span><b>Nombre(s) </b></span><label class='control-label'></label></div><input id="inputNombre" type="mail"   oninput="handleInput(event)">
			<div class="contenedorEtiquetaCampo text-left"><span><b>Apellido paterno </b></span><label class='control-label'></label></div><input id="inputAP" type="text"  oninput="handleInput(event)">
			<div class="contenedorEtiquetaCampo text-left"><span><b>Apellido materno </b></span></div><input id="inputAM" type="text" oninput="handleInput(event)">
			<div class="contenedorEtiquetaCampo text-left"><span><b>Tipo de cuenta </b></span></div><select id="inputTipoCuenta" name="inputTipoCuenta">
				<option value="USUARIO">USUARIO</option>
				<option value="VISITANTE">VISITANTE</option>
				<option value="DASU">DASU</option>
				<option value="ADMINISTRADOR">ADMINISTRADOR</option>
			</select> <br>	
		</div>
		
		<div id="contenedorRegistroBici" class="text-center form-group required">
			<h3 class="espacioArriba segundoTitulo text-left">Registrar bicicleta de este usuario</h3>
			<!--Lista completa de marcas de bicicletas-->
			<!--Lista obtenida de https://bikeindex.org/manufacturers-->
			
			<div class="contenedorEtiquetaCampo text-left"><span><b>Marca </b></span></div><select id='inputMarcasBicicletas'>
		  <option value="24seven">24seven</option>
		  <option value="333Fab">333Fab</option>
		  <option value="3G">3G</option>
		  <option value="6KU">6KU</option>
		  <option value="9 zero 7">9 zero 7</option>
		  <option value="A-bike">A-bike</option>
		  <option value="A2B e-bikes">A2B e-bikes</option>
		  <option value="Abici">Abici</option>
		  <option value="Accell">Accell</option>
		  <option value="Access">Access</option>
		  <option value="Acstar">Acstar</option>
		  <option value="Adams (Trail a bike)">Adams (Trail a bike)</option>
		  <option value="Advocate">Advocate</option>
		  <option value="Aegis">Aegis</option>
		  <option value="Aerofix Cycles">Aerofix Cycles</option>
		  <option value="Affinity Cycles">Affinity Cycles</option>
		  <option value="AGang">AGang</option>
		  <option value="Airborne">Airborne</option>
		  <option value="Aist Bicycles">Aist Bicycles</option>
		  <option value="ALAN">ALAN</option>
		  <option value="Alien Bikes">Alien Bikes</option>
		  <option value="All City">All City</option>
		  <option value="Alliance">Alliance</option>
		  <option value="Alton">Alton</option>
		  <option value="Amoeba">Amoeba</option>
		  <option value="Apollo">Apollo</option>
		  <option value="Ares">Ares</option>
		  <option value="Argon 18">Argon 18</option>
		  <option value="Asama">Asama</option>
		  <option value="Atomlab">Atomlab</option>
		  <option value="Author">Author</option>
		  <option value="Avalon">Avalon</option>
		  <option value="Avanti">Avanti</option>
		  <option value="Aventón">Aventón</option>
		  <option value="Azonic">Azonic</option>
		  <option value="Azor">Azor</option>
		  <option value="Azub">Azub</option>
		  <option value="Azzuri">Azzuri</option>
		  <option value="b'Twin">b'Twin</option>
		  <option value="Bacchetta">Bacchetta</option>
		  <option value="Backward Circle">Backward Circle</option>
		  <option value="Badger Bikes">Badger Bikes</option>
		  <option value="Bailey">Bailey</option>
		  <option value="Bamboocycles">Bamboocycles</option>
		  <option value="Banshee Bikes">Banshee Bikes</option>
		  <option value="Bantam (Bantam Bicycle Works)">Bantam (Bantam Bicycle Works)</option>
		  <option value="Barracuda">Barracuda</option>
		  <option value="Basso">Basso</option>
		  <option value="Batavus">Batavus</option>
		  <option value="Bazooka">Bazooka</option>
		  <option value="Bearclaw Bicycle Co.">Bearclaw Bicycle Co.</option>
		  <option value="Beater Bikes">Beater Bikes</option>
		  <option value="beixo">beixo</option>
		  <option value="BeOne">BeOne</option>
		  <option value="Bergamont">Bergamont</option>
		  <option value="BH Bikes (Beistegui Hermanos)">BH Bikes (Beistegui Hermanos)</option>
		  <option value="Bianchi">Bianchi</option>
		  <option value="Big Cat Bikes">Big Cat Bikes</option>
		  <option value="Big Shot">Big Shot</option>
		  <option value="Bike Friday">Bike Friday</option>
		  <option value="Bike Mielec">Bike Mielec</option>
		  <option value="Bilenky Cycle Works">Bilenky Cycle Works</option>
		  <option value="Biomega">Biomega</option>
		  <option value="Birdy">Birdy</option>
		  <option value="Biria">Biria</option>
		  <option value="Bishop">Bishop</option>
		  <option value="Black Market">Black Market</option>
		  <option value="Black Mountain Cycles">Black Mountain Cycles</option>
		  <option value="Black Sheep Bikes">Black Sheep Bikes</option>
		  <option value="Blix">Blix</option>
		  <option value="Blue (Blue Competition Cycles)">Blue (Blue Competition Cycles)</option>
		  <option value="Blue Sky Cycle Carts">Blue Sky Cycle Carts</option>
		  <option value="BMC">BMC</option>
		  <option value="Boardman Bikes">Boardman Bikes</option>
		  <option value="Bobbin">Bobbin</option>
		  <option value="Bohemian Bicycles">Bohemian Bicycles</option>
		  <option value="Boo Bicycles">Boo Bicycles</option>
		  <option value="Boreal">Boreal</option>
		  <option value="Borealis (fat bikes)">Borealis (fat bikes)</option>
		  <option value="Borile">Borile</option>
		  <option value="Bottecchia">Bottecchia</option>
		  <option value="Boulder Bicycles">Boulder Bicycles</option>
		  <option value="Box Bike Collective">Box Bike Collective</option>
		  <option value="Brasil & Movimento">Brasil & Movimento</option>
		  <option value="Breadwinner">Breadwinner</option>
		  <option value="Breakbrake17 Bicycle Co.">Breakbrake17 Bicycle Co.</option>
		  <option value="Breezer">Breezer</option>
		  <option value="Brennabor">Brennabor</option>
		  <option value="Bridgestone">Bridgestone</option>
		  <option value="Brilliant Bicycle">Brilliant Bicycle</option>
		  <option value="Broakland">Broakland</option>
		  <option value="Brodie">Brodie</option>
		  <option value="Broke Bikes">Broke Bikes</option>
		  <option value="Brompton Bicycle">Brompton Bicycle</option>
		  <option value="Brooklyn Bicycle Co.">Brooklyn Bicycle Co.</option>
		  <option value="Brunswick Corporation">Brunswick Corporation</option>
		  <option value="BSP">BSP</option>
		  <option value="Budnitz">Budnitz</option>
		  <option value="BULLS Bikes">BULLS Bikes</option>
		  <option value="Burley Design">Burley Design</option>
		  <option value="Calfee Design">Calfee Design</option>
		  <option value="Caloi">Caloi</option>
		  <option value="Canfield Brothers">Canfield Brothers</option>
		  <option value="Cannondale">Cannondale</option>
		  <option value="Canyon bicycles">Canyon bicycles</option>
		  <option value="Carrera bicycles">Carrera bicycles</option>
		  <option value="Catrike">Catrike</option>
		  <option value="Cayne">Cayne</option>
		  <option value="Centurion">Centurion</option>
		  <option value="Cervélo">Cervélo</option>
		  <option value="CETMA Cargo">CETMA Cargo</option>
		  <option value="Charge">Charge</option>
		  <option value="Chariot">Chariot</option>
		  <option value="Christiania Bikes">Christiania Bikes</option>
		  <option value="Chromag">Chromag</option>
		  <option value="CHUMBA Racing">CHUMBA Racing</option>
		  <option value="Cicielios">Cicielios</option>
		  <option value="Cicli Barco">Cicli Barco</option>
		  <option value="Cicli Olympia">Cicli Olympia</option>
		  <option value="Cielo">Cielo</option>
		  <option value="Cinelli">Cinelli</option>
		  <option value="Ciocc">Ciocc</option>
		  <option value="Citizen Bike">Citizen Bike</option>
		  <option value="City Bicycles Company">City Bicycles Company</option>
		  <option value="Civia">Civia</option>
		  <option value="Claud Butler">Claud Butler</option>
		  <option value="Co-Motion">Co-Motion</option>
		  <option value="Coker Tire">Coker Tire</option>
		  <option value="Colnago">Colnago</option>
		  <option value="Colony">Colony</option>
		  <option value="Colossi">Colossi</option>
		  <option value="Columbus Tubing">Columbus Tubing</option>
		  <option value="Condor">Condor</option>
		  <option value="Conor">Conor</option>
		  <option value="Cooper Bikes">Cooper Bikes</option>
		  <option value="Corima">Corima</option>
		  <option value="Corratec">Corratec</option>
		  <option value="Cortina Cycles">Cortina Cycles</option>
		  <option value="Cove">Cove</option>
		  <option value="Crank2">Crank2</option>
		  <option value="Create">Create</option>
		  <option value="Creme Cycles">Creme Cycles</option>
		  <option value="Crew">Crew</option>
		  <option value="Critical Cycles">Critical Cycles</option>
		  <option value="Crumpton">Crumpton</option>
		  <option value="Cruzbike">Cruzbike</option>
		  <option value="Cube">Cube</option>
		  <option value="Cuevas">Cuevas</option>
		  <option value="Cult">Cult</option>
		  <option value="Currie Technology (Currietech)">Currie Technology (Currietech)</option>
		  <option value="Currys">Currys</option>
		  <option value="Curtlo">Curtlo</option>
		  <option value="CVLN (Civilian)">CVLN (Civilian)</option>
		  <option value="Cycle Force Group">Cycle Force Group</option>
		  <option value="Cycles Fanatic">Cycles Fanatic</option>
		  <option value="Cycles Toussaint">Cycles Toussaint</option>
		  <option value="Cycleurope">Cycleurope</option>
		  <option value="Cyfac">Cyfac</option>
		  <option value="Da Bomb Bikes">Da Bomb Bikes</option>
		  <option value="Daccordi">Daccordi</option>
		  <option value="Dahon">Dahon</option>
		  <option value="Davidson">Davidson</option>
		  <option value="Dawes Cycles">Dawes Cycles</option>
		  <option value="de Fietsfabriek">de Fietsfabriek</option>
		  <option value="De Rosa">De Rosa</option>
		  <option value="DEAN">DEAN</option>
		  <option value="DeBernardi">DeBernardi</option>
		  <option value="Decathlon">Decathlon</option>
		  <option value="Deity">Deity</option>
		  <option value="Del Sol">Del Sol</option>
		  <option value="Della Santa">Della Santa</option>
		  <option value="Den Beste Sykkel">Den Beste Sykkel</option>
		  <option value="Dengfu">Dengfu</option>
		  <option value="Derby Cycle">Derby Cycle</option>
		  <option value="DeSalvo Cycles">DeSalvo Cycles</option>
		  <option value="Detroit Bikes">Detroit Bikes</option>
		  <option value="Devinci">Devinci</option>
		  <option value="DHS">DHS</option>
		  <option value="Di Blasi Industriale">Di Blasi Industriale</option>
		  <option value="Diadora">Diadora</option>
		  <option value="Diamant">Diamant</option>
		  <option value="Diamondback">Diamondback</option>
		  <option value="Dillenger">Dillenger</option>
		  <option value="DK Bikes">DK Bikes</option>
		  <option value="DMR Bikes">DMR Bikes</option>
		  <option value="Doberman">Doberman</option>
		  <option value="Dodici Milano">Dodici Milano</option>
		  <option value="Dolan">Dolan</option>
		  <option value="Dorel Industries">Dorel Industries</option>
		  <option value="Downtube">Downtube</option>
		  <option value="Dynacraft">Dynacraft</option>
		  <option value="Dynamic Bicycles">Dynamic Bicycles</option>
		  <option value="EAI (Euro Asia Imports)">EAI (Euro Asia Imports)</option>
		  <option value="East Germany">East Germany</option>
		  <option value="Eastern">Eastern</option>
		  <option value="Easy Motion">Easy Motion</option>
		  <option value="Ebisu">Ebisu</option>
		  <option value="Eddy Merckx">Eddy Merckx</option>
		  <option value="Edinburgh Bicycle Co-operative">Edinburgh Bicycle Co-operative</option>
		  <option value="eflow (Currietech)">eflow (Currietech)</option>
		  <option value="EG Bikes (Metronome)">EG Bikes (Metronome)</option>
		  <option value="EighthInch">EighthInch</option>
		  <option value="Electra">Electra</option>
		  <option value="Elliptigo">Elliptigo</option>
		  <option value="Ellis">Ellis</option>
		  <option value="Ellis Briggs">Ellis Briggs</option>
		  <option value="Ellsworth">Ellsworth</option>
		  <option value="EMC Bikes">EMC Bikes</option>
		  <option value="Engin Cycles">Engin Cycles</option>
		  <option value="Enigma Titanium">Enigma Titanium</option>
		  <option value="Erickson Bikes">Erickson Bikes</option>
		  <option value="Europa">Europa</option>
		  <option value="Evelo">Evelo</option>
		  <option value="Evil">Evil</option>
		  <option value="Evo">Evo</option>
		  <option value="EZ Pedaler (EZ Pedaler electric bikes)">EZ Pedaler (EZ Pedaler electric bikes)</option>
		  <option value="Ezee">Ezee</option>
		  <option value="eZip">eZip</option>
		  <option value="Faggin">Faggin</option>
		  <option value="Failure">Failure</option>
		  <option value="Fairdale">Fairdale</option>
		  <option value="Falco Bikes">Falco Bikes</option>
		  <option value="Falcon">Falcon</option>
		  <option value="Faraday">Faraday</option>
		  <option value="Fatback">Fatback</option>
		  <option value="FBM">FBM</option>
		  <option value="Federal">Federal</option>
		  <option value="Felt">Felt</option>
		  <option value="Fetish">Fetish</option>
		  <option value="Fezzari">Fezzari</option>
		  <option value="Field">Field</option>
		  <option value="Firefly Bicycles">Firefly Bicycles</option>
		  <option value="Firefox">Firefox</option>
		  <option value="Firmstrong">Firmstrong</option>
		  <option value="Fleet Velo">Fleet Velo</option>
		  <option value="FLX">FLX</option>
		  <option value="Flying Pigeon">Flying Pigeon</option>
		  <option value="Flying Scot">Flying Scot</option>
		  <option value="Flyxii">Flyxii</option>
		  <option value="Focale44">Focale44</option>
		  <option value="Focus">Focus</option>
		  <option value="Fondriest">Fondriest</option>
		  <option value="Forge Bikes">Forge Bikes</option>
		  <option value="Fortified (lights)">Fortified (lights)</option>
		  <option value="Foundry Cycles">Foundry Cycles</option>
		  <option value="Fram">Fram</option>
		  <option value="FRAMED">FRAMED</option>
		  <option value="Frances">Frances</option>
		  <option value="Francesco Moser (F. Moser)">Francesco Moser (F. Moser)</option>
		  <option value="Free Agent">Free Agent</option>
		  <option value="Fuji">Fuji</option>
		  <option value="Fyxation">Fyxation</option>
		  <option value="Gary Fisher">Gary Fisher</option>
		  <option value="Gavin">Gavin</option>
		  <option value="Gazelle">Gazelle</option>
		  <option value="Gendron Bicycles">Gendron Bicycles</option>
		  <option value="Genesis">Genesis</option>
		  <option value="GenZe">GenZe</option>
		  <option value="Geotech">Geotech</option>
		  <option value="Gepida">Gepida</option>
		  <option value="Ghost">Ghost</option>
		  <option value="Giant (and LIV)">Giant (and LIV)</option>
		  <option value="Gilmour">Gilmour</option>
		  <option value="Giordano">Giordano</option>
		  <option value="Gitane">Gitane</option>
		  <option value="Gladiator Cycle Company">Gladiator Cycle Company</option>
		  <option value="Globe">Globe</option>
		  <option value="Gocycle">Gocycle</option>
		  <option value="Graflex">Graflex</option>
		  <option value="Gravity">Gravity</option>
		  <option value="Greenspeed">Greenspeed</option>
		  <option value="Growler Performance Bikes">Growler Performance Bikes</option>
		  <option value="GT Bicycles">GT Bicycles</option>
		  <option value="Gudereit">Gudereit</option>
		  <option value="Guerciotti">Guerciotti</option>
		  <option value="Gunnar">Gunnar</option>
		  <option value="Guru">Guru</option>
		  <option value="Habanero">Habanero</option>
		  <option value="Haibike (Currietech)">Haibike (Currietech)</option>
		  <option value="Hallstrom">Hallstrom</option>
		  <option value="Hampsten Cycles">Hampsten Cycles</option>
		  <option value="Handsome Cycles">Handsome Cycles</option>
		  <option value="Hanford">Hanford</option>
		  <option value="Haro">Haro</option>
		  <option value="Harvey Cycle Works">Harvey Cycle Works</option>
		  <option value="Hasa">Hasa</option>
		  <option value="Hase bikes">Hase bikes</option>
		  <option value="HBBC (Huntington Beach Bicycle">Co)</option>
		  <option value="Heinkel">Heinkel</option>
		  <option value="Helkama">Helkama</option>
		  <option value="Heritage">Heritage</option>
		  <option value="Herkelmann">Herkelmann</option>
		  <option value="Hero Cycles Ltd">Hero Cycles Ltd</option>
		  <option value="Heron">Heron</option>
		  <option value="Hetchins">Hetchins</option>
		  <option value="Hija de la Coneja">Hija de la Coneja</option>
		  <option value="Hillman">Hillman</option>
		  <option value="Hinton Cycles">Hinton Cycles</option>
		  <option value="Hoffman">Hoffman</option>
		  <option value="Holdsworth">Holdsworth</option>
		  <option value="HP Velotechnik">HP Velotechnik</option>
		  <option value="Huffy">Huffy</option>
		  <option value="Hufnagel">Hufnagel</option>
		  <option value="Humber">Humber</option>
		  <option value="Humble Frameworks">Humble Frameworks</option>
		  <option value="Hunter">Hunter</option>
		  <option value="Hurtu">Hurtu</option>
		  <option value="Hyper">Hyper</option>
		  <option value="IBEX">IBEX</option>
		  <option value="Ibis">Ibis</option>
		  <option value="ICE Trikes (Inspired Cycle Engineering )">ICE Trikes (Inspired Cycle Engineering )</option>
		  <option value="Ideal Bikes">Ideal Bikes</option>
		  <option value="Identiti">Identiti</option>
		  <option value="Independent Fabrication">Independent Fabrication</option>
		  <option value="Inglis (Retrotec)">Inglis (Retrotec)</option>
		  <option value="Innerlight Cycles">Innerlight Cycles</option>
		  <option value="Inspired">Inspired</option>
		  <option value="Intense">Intense</option>
		  <option value="Iride Bicycles">Iride Bicycles</option>
		  <option value="IRO Cycles">IRO Cycles</option>
		  <option value="Iron Horse Bicycles">Iron Horse Bicycles</option>
		  <option value="Islabikes">Islabikes</option>
		  <option value="Italvega">Italvega</option>
		  <option value="IZIP (Currietech)">IZIP (Currietech)</option>
		  <option value="Jamis">Jamis</option>
		  <option value="Jan Jansen">Jan Jansen</option>
		  <option value="Javelin">Javelin</option>
		  <option value="JMC Bicycles">JMC Bicycles</option>
		  <option value="John Cherry bicycles">John Cherry bicycles</option>
		  <option value="Jorg & Olif">Jorg & Olif</option>
		  <option value="Juiced Riders">Juiced Riders</option>
		  <option value="Juliana Bicycles">Juliana Bicycles</option>
		  <option value="k.bedford customs">k.bedford customs</option>
		  <option value="K2">K2</option>
		  <option value="Kalkhoff">Kalkhoff</option>
		  <option value="Keith Bontrager">Keith Bontrager</option>
		  <option value="Kelly">Kelly</option>
		  <option value="Kellys Bicycles">Kellys Bicycles</option>
		  <option value="Kent">Kent</option>
		  <option value="Kestrel">Kestrel</option>
		  <option value="Kettler">Kettler</option>
		  <option value="KHS Bicycles">KHS Bicycles</option>
		  <option value="Kinesis">Kinesis</option>
		  <option value="Kinesis Industry">Kinesis Industry</option>
		  <option value="Kink">Kink</option>
		  <option value="Kinn">Kinn</option>
		  <option value="Kirk">Kirk</option>
		  <option value="Kish Fabrication">Kish Fabrication</option>
		  <option value="Knolly">Knolly</option>
		  <option value="Koga-Miyata">Koga-Miyata</option>
		  <option value="Kona">Kona</option>
		  <option value="Kron">Kron</option>
		  <option value="Kronan">Kronan</option>
		  <option value="Kross SA">Kross SA</option>
		  <option value="KTM">KTM</option>
		  <option value="Kuota">Kuota</option>
		  <option value="Kuwahara">Kuwahara</option>
		  <option value="KW Bicycle">KW Bicycle</option>
		  <option value="Land Shark">Land Shark</option>
		  <option value="Lapierre">Lapierre</option>
		  <option value="Larry Vs Harry">Larry Vs Harry</option>
		  <option value="LDG (Livery Design Gruppe)">LDG (Livery Design Gruppe)</option>
		  <option value="Leader Bikes">Leader Bikes</option>
		  <option value="Legacy Frameworks">Legacy Frameworks</option>
		  <option value="LeMond Racing Cycles">LeMond Racing Cycles</option>
		  <option value="Leopard">Leopard</option>
		  <option value="Lightning Cycle Dynamics">Lightning Cycle Dynamics</option>
		  <option value="Lightspeed">Lightspeed</option>
		  <option value="Linus">Linus</option>
		  <option value="Liotto (Cicli Liotto Gino & Figli)">Liotto (Cicli Liotto Gino & Figli)</option>
		  <option value="Litespeed">Litespeed</option>
		  <option value="Liteville">Liteville</option>
		  <option value="Loekie">Loekie</option>
		  <option value="Look">Look</option>
		  <option value="Louis Garneau">Louis Garneau</option>
		  <option value="LOW//">LOW//</option>
		  <option value="Lycoming Engines">Lycoming Engines</option>
		  <option value="Lynskey">Lynskey</option>
		  <option value="Madsen">Madsen</option>
		  <option value="Magna">Magna</option>
		  <option value="Malvern Star">Malvern Star</option>
		  <option value="Mango">Mango</option>
		  <option value="Manhattan">Manhattan</option>
		  <option value="ManKind">ManKind</option>
		  <option value="Map Bicycles">Map Bicycles</option>
		  <option value="Maraton">Maraton</option>
		  <option value="Marin Bikes">Marin Bikes</option>
		  <option value="Marinoni">Marinoni</option>
		  <option value="Mars Cycles">Mars Cycles</option>
		  <option value="Maruishi">Maruishi</option>
		  <option value="Marukin">Marukin</option>
		  <option value="Masi">Masi</option>
		  <option value="Matra">Matra</option>
		  <option value="Maverick">Maverick</option>
		  <option value="Maxcom">Maxcom</option>
		  <option value="MBK">MBK</option>
		  <option value="MEC (Mountain Equipment Co-op)">MEC (Mountain Equipment Co-op)</option>
		  <option value="Melon Bicycles">Melon Bicycles</option>
		  <option value="Mercian Cycles">Mercian Cycles</option>
		  <option value="Mercier">Mercier</option>
		  <option value="Merida Bikes">Merida Bikes</option>
		  <option value="Merit Bikes">Merit Bikes</option>
		  <option value="Merlin">Merlin</option>
		  <option value="MetaBikes">MetaBikes</option>
		  <option value="Metrofiets">Metrofiets</option>
		  <option value="Micargi">Micargi</option>
		  <option value="Miele bicycles">Miele bicycles</option>
		  <option value="Mikkelsen">Mikkelsen</option>
		  <option value="Milwaukee Bicycle Co.">Milwaukee Bicycle Co.</option>
		  <option value="MirraCo">MirraCo</option>
		  <option value="Mission Bicycles">Mission Bicycles</option>
		  <option value="Miyata">Miyata</option>
		  <option value="MMR">MMR</option>
		  <option value="Momentum">Momentum</option>
		  <option value="Monark">Monark</option>
		  <option value="Mondia">Mondia</option>
		  <option value="Mondraker">Mondraker</option>
		  <option value="Mongoose">Mongoose</option>
		  <option value="Montague">Montague</option>
		  <option value="Moots Cycles">Moots Cycles</option>
		  <option value="Mosaic">Mosaic</option>
		  <option value="Moser Cicli">Moser Cicli</option>
		  <option value="Mosso">Mosso</option>
		  <option value="Moth Attack">Moth Attack</option>
		  <option value="Motiv">Motiv</option>
		  <option value="Motobecane">Motobecane</option>
		  <option value="Moulton Bicycle">Moulton Bicycle</option>
		  <option value="Muddy Fox">Muddy Fox</option>
		  <option value="Mutiny">Mutiny</option>
		  <option value="Müsing (Musing)">Müsing (Musing)</option>
		  <option value="Nakamura">Nakamura</option>
		  <option value="Naked">Naked</option>
		  <option value="Nashbar">Nashbar</option>
		  <option value="National">National</option>
		  <option value="Neil Pryde">Neil Pryde</option>
		  <option value="Neobike">Neobike</option>
		  <option value="New Albion">New Albion</option>
		  <option value="Next">Next</option>
		  <option value="Niner">Niner</option>
		  <option value="Nirve">Nirve</option>
		  <option value="Nishiki">Nishiki</option>
		  <option value="Nomad Cargo">Nomad Cargo</option>
		  <option value="Norco Bikes">Norco Bikes</option>
		  <option value="Norman Cycles">Norman Cycles</option>
		  <option value="Northrock">Northrock</option>
		  <option value="NS Bikes">NS Bikes</option>
		  <option value="Nukeproof">Nukeproof</option>
		  <option value="OHM">OHM</option>
		  <option value="Oleary">Oleary</option>
		  <option value="Olmo">Olmo</option>
		  <option value="Omnium">Omnium</option>
		  <option value="On-One (On One)">On-One (On One)</option>
		  <option value="Opel">Opel</option>
		  <option value="Opus">Opus</option>
		  <option value="Orange mountain bikes">Orange mountain bikes</option>
		  <option value="Orbea">Orbea</option>
		  <option value="Orbit">Orbit</option>
		  <option value="Orient Bikes">Orient Bikes</option>
		  <option value="Origin8 (Origin-8)">Origin8 (Origin-8)</option>
		  <option value="Otis Guy">Otis Guy</option>
		  <option value="Oyama">Oyama</option>
		  <option value="Pacific Cycle">Pacific Cycle</option>
		  <option value="Pake">Pake</option>
		  <option value="Paper Bicycle">Paper Bicycle</option>
		  <option value="Parkpre">Parkpre</option>
		  <option value="Parlee">Parlee</option>
		  <option value="Pasculli">Pasculli</option>
		  <option value="Pashley Cycles">Pashley Cycles</option>
		  <option value="Patria">Patria</option>
		  <option value="Pedego">Pedego</option>
		  <option value="Pedersen bicycle">Pedersen bicycle</option>
		  <option value="Pegasus">Pegasus</option>
		  <option value="Pegoretti">Pegoretti</option>
		  <option value="Pereira">Pereira</option>
		  <option value="Performance">Performance</option>
		  <option value="Peugeot">Peugeot</option>
		  <option value="Phat Cycles">Phat Cycles</option>
		  <option value="Phillips Cycles">Phillips Cycles</option>
		  <option value="Phoenix">Phoenix</option>
		  <option value="Pierce-Arrow">Pierce-Arrow</option>
		  <option value="Pilen">Pilen</option>
		  <option value="Pinarello">Pinarello</option>
		  <option value="Pinnacle (Evans Cycles)">Pinnacle (Evans Cycles)</option>
		  <option value="Pivot">Pivot</option>
		  <option value="Planet X">Planet X</option>
		  <option value="Pogliaghi">Pogliaghi</option>
		  <option value="Polygon">Polygon</option>
		  <option value="Premium">Premium</option>
		  <option value="Price">Price</option>
		  <option value="Primus Mootry (PM Cycle Fabrication)">Primus Mootry (PM Cycle Fabrication)</option>
		  <option value="Principia">Principia</option>
		  <option value="Priority Bicycles">Priority Bicycles</option>
		  <option value="Procycle Group">Procycle Group</option>
		  <option value="Prodeco">Prodeco</option>
		  <option value="Propain">Propain</option>
		  <option value="Prophete">Prophete</option>
		  <option value="PUBLIC bikes">PUBLIC bikes</option>
		  <option value="Puch">Puch</option>
		  <option value="Pure City">Pure City</option>
		  <option value="Pure Fix">Pure Fix</option>
		  <option value="Python">Python</option>
		  <option value="Python Pro">Python Pro</option>
		  <option value="Quintana Roo">Quintana Roo</option>
		  <option value="Rabeneick">Rabeneick</option>
		  <option value="Rad Power Bikes">Rad Power Bikes</option>
		  <option value="Radio Bike Co">Radio Bike Co</option>
		  <option value="Radio Flyer">Radio Flyer</option>
		  <option value="Raleigh">Raleigh</option>
		  <option value="Ram">Ram</option>
		  <option value="Rambler">Rambler</option>
		  <option value="Rans Designs">Rans Designs</option>
		  <option value="Ratking">Ratking</option>
		  <option value="Rawland Cycles">Rawland Cycles</option>
		  <option value="Razor">Razor</option>
		  <option value="Redline">Redline</option>
		  <option value="Regina">Regina</option>
		  <option value="REI (Co-op)">REI (Co-op)</option>
		  <option value="René Herse">René Herse</option>
		  <option value="Republic">Republic</option>
		  <option value="Retrospec">Retrospec</option>
		  <option value="Ribble">Ribble</option>
		  <option value="Ridgeback">Ridgeback</option>
		  <option value="Ridley">Ridley</option>
		  <option value="Riese und Müller">Riese und Müller</option>
		  <option value="RIH">RIH</option>
		  <option value="Ritchey">Ritchey</option>
		  <option value="Ritte">Ritte</option>
		  <option value="Rivendell Bicycle Works">Rivendell Bicycle Works</option>
		  <option value="Roberts Cycles">Roberts Cycles</option>
		  <option value="Robin Hood">Robin Hood</option>
		  <option value="Rock Lobster">Rock Lobster</option>
		  <option value="Rodriguez">Rodriguez</option>
		  <option value="Rosko">Rosko</option>
		  <option value="Ross">Ross</option>
		  <option value="Rossin">Rossin</option>
		  <option value="Rover Company">Rover Company</option>
		  <option value="Rowbike">Rowbike</option>
		  <option value="Royce Union">Royce Union</option>
		  <option value="RRB (Rat Rod Bikes)">RRB (Rat Rod Bikes)</option>
		  <option value="S and M (S&M)">S and M (S&M)</option>
		  <option value="Sage Titanium Bicycles">Sage Titanium Bicycles</option>
		  <option value="Salsa">Salsa</option>
		  <option value="Samchuly">Samchuly</option>
		  <option value="Sanderson">Sanderson</option>
		  <option value="Santa Cruz">Santa Cruz</option>
		  <option value="Santana Cycles">Santana Cycles</option>
		  <option value="Saracen Cycles">Saracen Cycles</option>
		  <option value="Scania AB">Scania AB</option>
		  <option value="Scapin">Scapin</option>
		  <option value="Scattante">Scattante</option>
		  <option value="Schindelhauer">Schindelhauer</option>
		  <option value="Schwinn">Schwinn</option>
		  <option value="SCOTT">SCOTT</option>
		  <option value="SE Bikes (SE Racing)">SE Bikes (SE Racing)</option>
		  <option value="Season">Season</option>
		  <option value="Serotta">Serotta</option>
		  <option value="Sette">Sette</option>
		  <option value="Seven Cycles">Seven Cycles</option>
		  <option value="Shadow Conspiracy">Shadow Conspiracy</option>
		  <option value="Shinola">Shinola</option>
		  <option value="Shredder">Shredder</option>
		  <option value="silverback">silverback</option>
		  <option value="Simcoe">Simcoe</option>
		  <option value="Simplon">Simplon</option>
		  <option value="Sinclair Research">Sinclair Research</option>
		  <option value="Six-Eleven">Six-Eleven</option>
		  <option value="sixthreezero">sixthreezero</option>
		  <option value="Sohrab Cycles">Sohrab Cycles</option>
		  <option value="Solex">Solex</option>
		  <option value="Solé (Sole bicycles)">Solé (Sole bicycles)</option>
		  <option value="Soma">Soma</option>
		  <option value="Somec">Somec</option>
		  <option value="Sondors">Sondors</option>
		  <option value="Sonoma">Sonoma</option>
		  <option value="Soulcraft">Soulcraft</option>
		  <option value="Specialized">Specialized</option>
		  <option value="Spectrum">Spectrum</option>
		  <option value="Spicer">Spicer</option>
		  <option value="Splendid Cycles">Splendid Cycles</option>
		  <option value="Spooky">Spooky</option>
		  <option value="Spot">Spot</option>
		  <option value="Stages cycling (Power meters)">Stages cycling (Power meters)</option>
		  <option value="Staiger">Staiger</option>
		  <option value="Standard Byke">Standard Byke</option>
		  <option value="Stanridge Speed">Stanridge Speed</option>
		  <option value="State Bicycle Co.">State Bicycle Co.</option>
		  <option value="Steelman Cycles">Steelman Cycles</option>
		  <option value="Stein Trikes">Stein Trikes</option>
		  <option value="Steve Potts">Steve Potts</option>
		  <option value="Stevens">Stevens</option>
		  <option value="Stevenson Custom Bicycles">Stevenson Custom Bicycles</option>
		  <option value="Stinner">Stinner</option>
		  <option value="Stoemper">Stoemper</option>
		  <option value="Strada Customs">Strada Customs</option>
		  <option value="Stradalli Cycles">Stradalli Cycles</option>
		  <option value="Strawberry Bicycle">Strawberry Bicycle</option>
		  <option value="Strider">Strider</option>
		  <option value="Stromer">Stromer</option>
		  <option value="Strong Frames">Strong Frames</option>
		  <option value="Stålhästen">Stålhästen</option>
		  <option value="Subrosa">Subrosa</option>
		  <option value="Sunday">Sunday</option>
		  <option value="Sunn">Sunn</option>
		  <option value="SunRace">SunRace</option>
		  <option value="Supercross">Supercross</option>
		  <option value="Surly">Surly</option>
		  <option value="Surrey">Surrey</option>
		  <option value="Sweetpea Bicycles">Sweetpea Bicycles</option>
		  <option value="Swingset">Swingset</option>
		  <option value="Swobo">Swobo</option>
		  <option value="SyCip">SyCip</option>
		  <option value="Takara">Takara</option>
		  <option value="Tati Cycles">Tati Cycles</option>
		  <option value="Taylor Bicycles (Paul Taylor)">Taylor Bicycles (Paul Taylor)</option>
		  <option value="Tern">Tern</option>
		  <option value="Terra Trike">Terra Trike</option>
		  <option value="Terrible One">Terrible One</option>
		  <option value="Terry">Terry</option>
		  <option value="TET Cycles (Tom Teesdale Bikes)">TET Cycles (Tom Teesdale Bikes)</option>
		  <option value="The Bicycle Forge">The Bicycle Forge</option>
		  <option value="Throne Cycles">Throne Cycles</option>
		  <option value="Thruster">Thruster</option>
		  <option value="Thule">Thule</option>
		  <option value="Ti Cycles">Ti Cycles</option>
		  <option value="Time">Time</option>
		  <option value="Titan">Titan</option>
		  <option value="Titus">Titus</option>
		  <option value="Tokyobike">Tokyobike</option>
		  <option value="Tomac">Tomac</option>
		  <option value="Tommasini">Tommasini</option>
		  <option value="Tommaso">Tommaso</option>
		  <option value="Tony Hawk">Tony Hawk</option>
		  <option value="Torelli">Torelli</option>
		  <option value="Tour de France">Tour de France</option>
		  <option value="Tout Terrain">Tout Terrain</option>
		  <option value="Toyo">Toyo</option>
		  <option value="Traitor">Traitor</option>
		  <option value="Transition Bikes">Transition Bikes</option>
		  <option value="Trayl">Trayl</option>
		  <option value="Trek">Trek</option>
		  <option value="Tribe Bicycle Co">Tribe Bicycle Co</option>
		  <option value="Trinx">Trinx</option>
		  <option value="Tubus">Tubus</option>
		  <option value="Turin">Turin</option>
		  <option value="Turner Bicycles">Turner Bicycles</option>
		  <option value="Twin Six">Twin Six</option>
		  <option value="Umberto Dei">Umberto Dei</option>
		  <option value="Upland">Upland</option>
		  <option value="van Andel/Bakfiets">van Andel/Bakfiets</option>
		  <option value="Van Dessel">Van Dessel</option>
		  <option value="Van Herwerden">Van Herwerden</option>
		  <option value="Vanilla">Vanilla</option>
		  <option value="Vanmoof">Vanmoof</option>
		  <option value="Vassago">Vassago</option>
		  <option value="Velo Orange">Velo Orange</option>
		  <option value="Velorbis">Velorbis</option>
		  <option value="Verde">Verde</option>
		  <option value="Vicini">Vicini</option>
		  <option value="Vilano">Vilano</option>
		  <option value="Virtue">Virtue</option>
		  <option value="Vision">Vision</option>
		  <option value="Viva">Viva</option>
		  <option value="Vivente">Vivente</option>
		  <option value="Volagi">Volagi</option>
		  <option value="Voodoo">Voodoo</option>
		  <option value="Vortrieb">Vortrieb</option>
		  <option value="VSF Fahrradmanufaktur">VSF Fahrradmanufaktur</option>
		  <option value="Wabi Cycles">Wabi Cycles</option>
		  <option value="Waterford">Waterford</option>
		  <option value="Weehoo">Weehoo</option>
		  <option value="WeeRide">WeeRide</option>
		  <option value="Wilier Triestina">Wilier Triestina</option>
		  <option value="Windsor">Windsor</option>
		  <option value="Winora">Winora</option>
		  <option value="Winter Bicycles">Winter Bicycles</option>
		  <option value="WordLock">WordLock</option>
		  <option value="WorkCycles">WorkCycles</option>
		  <option value="Worksman Cycles">Worksman Cycles</option>
		  <option value="X-Treme">X-Treme</option>
		  <option value="Xds">Xds</option>
		  <option value="Xtracycle">Xtracycle</option>
		  <option value="Yamaguchi Bicycles">Yamaguchi Bicycles</option>
		  <option value="Yeti">Yeti</option>
		  <option value="Yuba">Yuba</option>
		  <option value="Zinn Cycles">Zinn Cycles</option>
		  <option value="Zycle Fix">Zycle Fix</option>
		  <option value="3rensho">3rensho</option>
		  <option value="Adolphe Clément">Adolphe Clément</option>
		  <option value="Alcyon">Alcyon</option>
		  <option value="Alexander Leutner & Co.">Alexander Leutner & Co.</option>
		  <option value="Alldays & Onions">Alldays & Onions</option>
		  <option value="American Bicycle Company">American Bicycle Company</option>
		  <option value="American Machine and Foundry">American Machine and Foundry</option>
		  <option value="American Star Bicycle">American Star Bicycle</option>
		  <option value="AMF">AMF</option>
		  <option value="Atala">Atala</option>
		  <option value="Benotto">Benotto</option>
		  <option value="Bertoni">Bertoni</option>
		  <option value="Bickerton">Bickerton</option>
		  <option value="Bike4Life">Bike4Life</option>
		  <option value="BikeE recumbents">BikeE recumbents</option>
		  <option value="Birmingham Small Arms Company">Birmingham Small Arms Company</option>
		  <option value="British Eagle">British Eagle</option>
		  <option value="Browning">Browning</option>
		  <option value="Calcott Brothers">Calcott Brothers</option>
		  <option value="Campion Cycle Company">Campion Cycle Company</option>
		  <option value="CCM">CCM</option>
		  <option value="Chater-Lea">Chater-Lea</option>
		  <option value="Chicago Bicycle Company">Chicago Bicycle Company</option>
		  <option value="Cignal">Cignal</option>
		  <option value="Cilo">Cilo</option>
		  <option value="Clark-Kent">Clark-Kent</option>
		  <option value="Columbia">Columbia</option>
		  <option value="Cook Bros. Racing">Cook Bros. Racing</option>
		  <option value="Cyclamatic">Cyclamatic</option>
		  <option value="CyclePro">CyclePro</option>
		  <option value="Cycles Follis">Cycles Follis</option>
		  <option value="di Florino">di Florino</option>
		  <option value="Dyno">Dyno</option>
		  <option value="E. C. Stearns Bicycle Agency">E. C. Stearns Bicycle Agency</option>
		  <option value="Eagle Bicycle Manufacturing Company">Eagle Bicycle Manufacturing Company</option>
		  <option value="Emilio Bozzi">Emilio Bozzi</option>
		  <option value="Fat City Cycles">Fat City Cycles</option>
		  <option value="Fausto Coppi">Fausto Coppi</option>
		  <option value="Firenze">Firenze</option>
		  <option value="Fit bike Co.">Fit bike Co.</option>
		  <option value="Fleetwing">Fleetwing</option>
		  <option value="Fokhan">Fokhan</option>
		  <option value="Folmer & Schwing">Folmer & Schwing</option>
		  <option value="Freddie Grubb">Freddie Grubb</option>
		  <option value="Free Spirit">Free Spirit</option>
		  <option value="Gardin">Gardin</option>
		  <option value="GMC">GMC</option>
		  <option value="Gnome et Rhône">Gnome et Rhône</option>
		  <option value="Gomier">Gomier</option>
		  <option value="Gormully & Jeffery">Gormully & Jeffery</option>
		  <option value="Harry Quinn">Harry Quinn</option>
		  <option value="Head">Head</option>
		  <option value="Hercules Fahrrad GmbH & Co">Hercules Fahrrad GmbH & Co</option>
		  <option value="Hirschfeld">Hirschfeld</option>
		  <option value="Industrieverband Fahrzeugbau">Industrieverband Fahrzeugbau</option>
		  <option value="Itera plastic bicycle">Itera plastic bicycle</option>
		  <option value="Iver Johnson">Iver Johnson</option>
		  <option value="Iverson">Iverson</option>
		  <option value="John Deere">John Deere</option>
		  <option value="Klein Bikes">Klein Bikes</option>
		  <option value="Kogswell Cycles">Kogswell Cycles</option>
		  <option value="Kustom Kruiser">Kustom Kruiser</option>
		  <option value="Laurin & Klement">Laurin & Klement</option>
		  <option value="Lotus">Lotus</option>
		  <option value="Louison Bobet">Louison Bobet</option>
		  <option value="Madwagon">Madwagon</option>
		  <option value="Matchless">Matchless</option>
		  <option value="Micajah C. Henley">Micajah C. Henley</option>
		  <option value="Mizutani">Mizutani</option>
		  <option value="Mosh">Mosh</option>
		  <option value="Moulden">Moulden</option>
		  <option value="Mountain Cycles">Mountain Cycles</option>
		  <option value="Murray">Murray</option>
		  <option value="Novara">Novara</option>
		  <option value="Nymanbolagen">Nymanbolagen</option>
		  <option value="Other">Other</option>
		  <option value="Panasonic">Panasonic</option>
		  <option value="Pocket Bicycles">Pocket Bicycles</option>
		  <option value="Pope Manufacturing Company">Pope Manufacturing Company</option>
		  <option value="Private label">Private label</option>
		  <option value="Quadrant Cycle Company">Quadrant Cycle Company</option>
		  <option value="Redlof">Redlof</option>
		  <option value="Republic of China">Republic of China</option>
		  <option value="Roadmaster">Roadmaster</option>
		  <option value="Rocky Mountain Bicycles">Rocky Mountain Bicycles</option>
		  <option value="Rudge-Whitworth">Rudge-Whitworth</option>
		  <option value="Sancineto">Sancineto</option>
		  <option value="Sears Roebuck">Sears Roebuck</option>
		  <option value="Sekai">Sekai</option>
		  <option value="Sekine">Sekine</option>
		  <option value="Shelby Cycle Company">Shelby Cycle Company</option>
		  <option value="Shogun">Shogun</option>
		  <option value="Simson">Simson</option>
		  <option value="Skyway">Skyway</option>
		  <option value="Spalding Bicycles">Spalding Bicycles</option>
		  <option value="Sparta B.V.">Sparta B.V.</option>
		  <option value="Speedwell bicycles">Speedwell bicycles</option>
		  <option value="St. Tropez">St. Tropez</option>
		  <option value="Stelber Cycle Corp">Stelber Cycle Corp</option>
		  <option value="Stella">Stella</option>
		  <option value="Sterling Bicycle Co.">Sterling Bicycle Co.</option>
		  <option value="Stolen Bicycle Co.">Stolen Bicycle Co.</option>
		  <option value="Strida">Strida</option>
		  <option value="Sun">Sun</option>
		  <option value="Suzuki">Suzuki</option>
		  <option value="Talisman">Talisman</option>
		  <option value="Terrot">Terrot</option>
		  <option value="The Arthur Pequegnat Clock Company">The Arthur Pequegnat Clock Company</option>
		  <option value="Thorn Cycles">Thorn Cycles</option>
		  <option value="TI Cycles of India">TI Cycles of India</option>
		  <option value="Torker">Torker</option>
		  <option value="Triumph Cycle">Triumph Cycle</option>
		  <option value="Tunturi">Tunturi</option>
		  <option value="Univega">Univega</option>
		  <option value="Unknown">Unknown</option>
		  <option value="Urago">Urago</option>
		  <option value="Utopia">Utopia</option>
		  <option value="Valdora">Valdora</option>
		  <option value="Velo Vie">Velo Vie</option>
		  <option value="Velomotors">Velomotors</option>
		  <option value="Villy Customs">Villy Customs</option>
		  <option value="Vindec High Riser">Vindec High Riser</option>
		  <option value="Viner">Viner</option>
		  <option value="Viscount">Viscount</option>
		  <option value="Vitus">Vitus</option>
		  <option value="Volae">Volae</option>
		  <option value="Volume">Volume</option>
		  <option value="VéloSoleX">VéloSoleX</option>
		  <option value="WeThePeople">WeThePeople</option>
		  <option value="Wilderness Trail Bikes">Wilderness Trail Bikes</option>
		  <option value="Witcomb Cycles">Witcomb Cycles</option>
		  <option value="Wright Cycle Company">Wright Cycle Company</option>
		  <option value="Xootr">Xootr</option>
		  <option value="Yamaha">Yamaha</option>
		  <option value="Zigo">Zigo</option>
		</select>
			
			<div class="contenedorEtiquetaCampo text-left"><span><b>Color </b></span></div><select id="inputColores" class="mitades">>
				<option value="Negro">Blanco</option>
				<option value="Blanco">Verde</option>
				<option value="dasu">Azul</option>
				<option value="admin">Rojo</option>
				<option value="admin">Naranja</option>
				<option value="admin">Amarillo</option>
				<option value="admin">Negro</option>
			</select> <br>
			
			<div class="contenedorEtiquetaCampo text-left"><span><b>Rodada </b></span><label class='control-label'></label></div><input id="inputRodada" type="number" min="14" max="29" onkeypress='return isNumberKey(event)' placeholder="14 - 29" > <br>
			<div id="contenedorBotonNuevo"></div>
			<br>
			<p class="text-center">Aquí se mostrará el QR al registrar la bicicleta</p>
			<div id="qrCanvas"></div>
		</div>

		<div class="text-center">		
			<button id="btnRegistrarUsuario" class="btn btn-success aceptar" >Registrar usuario</button> <br>	
		</div>		
	</div>	
	
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
	  
	function isNumberKey(evt) {
		var e = evt || window.event; //window.event is safer
		var charCode = e.which || e.keyCode;

		if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
		if (e.shiftKey) return false;
		return true;
	}
	  
  </script>
</body>

</html>

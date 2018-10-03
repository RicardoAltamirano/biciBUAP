<?php
    if(isset($_POST['action']) && !empty($_POST['action'])) {
        $action = $_POST['action'];
        switch($action) {
            case 'login' :
                $tipoDeCuenta = $_POST['tipoCuenta'];
                logIn($tipoDeCuenta);
            break;

            case 'logout' : logOut();break;
            
            default: break;
        }
    }
    
    function logIn($tipoCuenta){
        //Validar sesión
        session_start();
        $_SESSION['tipoCuenta'] = $tipoCuenta;
    }

    function logOut(){
        //Destruye todas las variables globales
        session_start();
        $_SESSION = array();
        session_destroy();   
		return 0;
    }
?>
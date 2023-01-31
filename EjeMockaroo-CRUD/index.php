<?php
session_start();
define ('FPAG',10); // Número de filas por página


require_once 'app/helpers/util.php';
require_once 'app/helpers/fpdf.php';
require_once 'app/config/configDB.php';
require_once 'app/models/Cliente.php';
require_once 'app/models/AccesoDatos.php';
require_once 'app/controllers/crudclientes.php';

//---- PAGINACIÓN ----
$midb = AccesoDatos::getModelo();
$totalfilas = $midb->numClientes();
if ( $totalfilas % FPAG == 0){
    $posfin = $totalfilas - FPAG;
} else {
    $posfin = $totalfilas - $totalfilas % FPAG;
}

if ( !isset($_SESSION['posini']) ){
  $_SESSION['posini'] = 0;
}
$posAux = $_SESSION['posini'];
//------------

//Contador de las ordenaciones 
if ( (!isset($_SESSION['contorden']) || ($_SESSION['contorden'] == 2)) ){
    $_SESSION['contorden'] = 0;
}
  
  //Ordenacion por defecto
if ( !isset($_SESSION['ordenacion']) ){
    $_SESSION['ordenacion']="id";
}
  


ob_start(); // La salida se guarda en el bufer
if ($_SERVER['REQUEST_METHOD'] == "GET" ){
    
    // Proceso las ordenes de navegación
    if ( isset($_GET['nav'])) {
        switch ( $_GET['nav']) {
            case "Primero"  : $posAux = 0; break;
            case "Siguiente": $posAux +=FPAG; if ($posAux > $posfin) $posAux=$posfin; break;
            case "Anterior" : $posAux -=FPAG; if ($posAux < 0) $posAux =0; break;
            case "Ultimo"   : $posAux = $posfin;
        }
        $_SESSION['posini'] = $posAux;
    }


     // Proceso las ordenes de navegación en detalles
     if ( isset($_GET['nav-detalles']) && isset($_GET['id']) ) {
        switch ( $_GET['nav-detalles']) {
            case "Siguiente": crudDetallesSiguiente($_GET['id']); break;
            case "Anterior" : crudDetallesAnterior($_GET['id']); break;   
        }
    }


     // Proceso las ordenes de navegación en modificar
    if ( isset($_GET['nav-modificar']) && isset($_GET['id']) ) {
        switch ( $_GET['nav-modificar']) {
            case "Siguiente": crudModificarSiguiente($_GET['id']); break;
            case "Anterior" : crudModificarAnterior($_GET['id']); break;
        }
    }


    //Imprimir cliente
    if ( isset($_GET['imprimirPDF']) && isset($_GET['id']) ) {
        $db = AccesoDatos::getModelo();
        $db->pdfCliente($_GET['id']);
    }
    

    // Proceso de ordenes de CRUD clientes
    if ( isset($_GET['orden'])){
        switch ($_GET['orden']) {
            case "Nuevo"    : crudAlta(); break;
            case "Borrar"   : crudBorrar   ($_GET['id']); break;
            case "Modificar": crudModificar($_GET['id']); break;
            case "Detalles" : crudDetalles ($_GET['id']);break;
            case "Terminar" : crudTerminar(); break;
            case "Ordenar"  : $_SESSION['ordenacion']=$_GET['valor']; $_SESSION['contorden']++; break;
        }
    }
} 
// POST Formulario de alta o de modificación
else {
    if (  isset($_POST['orden'])){
         switch($_POST['orden']) {
             case "Nuevo"    : crudPostAlta(); break;
             case "Modificar": crudPostModificar(); break;
             case "Detalles":; // No hago nada
         }
    }
}

// Si no hay nada en la buffer 
// Cargo genero la vista con la lista por defecto

/* if (!(isset($_SESSION['valido']))){
    $_SESSION['valido'] = false;
}

if ($_SESSION['valido'] == false){
    include_once ("app/views/acceso.php");
}

if (isset($_POST["enviar"])){

    if (!(isset($_SESSION['contador']))){
        $_SESSION['contador'] = 0;
    }

    if ($_SESSION['contador'] >= 3){
        $_SESSION['limite']=2;
        
    }

    if (isset($_SESSION['limite'])){
        print ("Reinicie el navegador");
        exit();
    }

    $login      = $_POST['user'];
    $password   = $_POST['pass'];

    $db = AccesoDatos::getModelo();
    $acceso = $db->comprobarUser($login,$password);

    if (empty($acceso)){
        if ($_SESSION['contador'] < 3){
            print "<br>Usuario o contraseña incorrectos<br>";
            $_SESSION['contador'] = $_SESSION['contador'] + 1;
            print "intento nº: ".$_SESSION['contador']."<br>";
        }
    }else{
        $_SESSION['contador']=0;
        $_SESSION['valido'] = true;
        $user = $_POST['user'];
        $pass = $_POST['pass'];
        print_r($user." ". $pass);
    }
} */

if ( ob_get_length() == 0 /* && $_SESSION['valido'] == true */){
    $db = AccesoDatos::getModelo();
    $ordenacion = $_SESSION['ordenacion'];
    $posini = $_SESSION['posini'];
    $contorden = $_SESSION['contorden'];
    $tvalores = $db->getClientes($posini,FPAG,$ordenacion,$contorden);
    require_once "app/views/list.php";    
}
$contenido = ob_get_clean();

// Muestro la página principal con el contenido generado
require_once "app/views/principal.php";




<?php

function crudBorrar ($id){    
    $db = AccesoDatos::getModelo();
    $tuser = $db->borrarCliente($id);
}

function crudTerminar(){
    AccesoDatos::closeModelo();
    session_destroy();
}
 
function crudAlta(){
    $cli = new Cliente();
    $orden= "Nuevo";
    include_once "app/views/formulario.php";
}

function crudDetalles($id){
    $db = AccesoDatos::getModelo();
    $cli = $db->getCliente($id);
    $bandera= $db->getFlag($id);
    $foto= $db->imagenCliente($id);
    $coordenadas = $db->MapaIP($id);
    include_once "app/views/detalles.php";
}

function crudDetallesSiguiente($id){
    $db = AccesoDatos::getModelo();
    $filas = $db->numClientes();
    if ($id < $filas){
        $id = $id + 1;
    }
    $cli = $db->getCliente($id);
    $bandera= $db->getFlag($id);
    $foto= $db->imagenCliente($id);
    $coordenadas = $db->MapaIP($id);
    include_once "app/views/detalles.php";
}

function crudDetallesAnterior($id){
    $db = AccesoDatos::getModelo();
    if ($id > 1){
        $id = $id - 1;
    }
    $cli = $db->getCliente($id);
    $bandera = $db->getFlag($id);
    $foto = $db->imagenCliente($id);
    $coordenadas = $db->MapaIP($id);
    include_once "app/views/detalles.php";
}


function crudModificar($id){
    $db = AccesoDatos::getModelo();
    $cli = $db->getCliente($id);
    $orden="Modificar";
    include_once "app/views/modificar.php";
}

function crudModificarSiguiente($id){
    $db = AccesoDatos::getModelo();
    $filas = $db->numClientes();
    if ($id < $filas){
        $id = $id + 1;
    }
    $cli = $db->getCliente($id);
    $orden="Modificar";
    include_once "app/views/modificar.php";

}

function crudModificarAnterior($id){
    $db = AccesoDatos::getModelo();
    if ($id > 1){
        $id = $id - 1;
    }
    $cli = $db->getCliente($id);
    $orden="Modificar";
    include_once "app/views/modificar.php";
}


function crudPostAlta(){
    limpiarArrayEntrada($_POST); //Evito la posible inyección de código
    $cli = new Cliente();
    $cli->id            =$_POST['id'];
    $cli->first_name    =$_POST['first_name'];
    $cli->last_name     =$_POST['last_name'];
    $cli->email         =$_POST['email'];	
    $cli->gender        =$_POST['gender'];
    $cli->ip_address    =$_POST['ip_address'];
    $cli->telefono      =$_POST['telefono'];
    $db = AccesoDatos::getModelo();
    $db->todoValidado($cli,"Nuevo");
    
}

function crudPostModificar(){
    limpiarArrayEntrada($_POST); //Evito la posible inyección de código
    $cli = new Cliente();

    $cli->id            =$_POST['id'];
    $cli->first_name    =$_POST['first_name'];
    $cli->last_name     =$_POST['last_name'];
    $cli->email         =$_POST['email'];	
    $cli->gender        =$_POST['gender'];
    $cli->ip_address    =$_POST['ip_address'];
    $cli->telefono      =$_POST['telefono'];
    $db = AccesoDatos::getModelo();
    $db->todoValidado($cli,"Modificar");
    
}

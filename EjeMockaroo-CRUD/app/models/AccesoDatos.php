<?php

/*
 * Acceso a datos con BD Usuarios : 
 * Usando la librería PDO *******************
 * Uso el Patrón Singleton :Un único objeto para la clase
 * Constructor privado, y métodos estáticos 
 */
class AccesoDatos {
    
    private static $modelo = null;
    private $dbh = null;
    
    public static function getModelo(){
        if (self::$modelo == null){
            self::$modelo = new AccesoDatos();
        }
        return self::$modelo;
    }
    
    

   // Constructor privado  Patron singleton
   
    private function __construct(){
        try {
            $dsn = "mysql:host=".DB_SERVER.";dbname=".DATABASE.";charset=utf8";
            $this->dbh = new PDO($dsn,DB_USER,DB_PASSWD);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            echo "Error de conexión ".$e->getMessage();
            exit();
        }  

    }

    // Cierro la conexión anulando todos los objectos relacioanado con la conexión PDO (stmt)
    public static function closeModelo(){
        if (self::$modelo != null){
            $obj = self::$modelo;
            // Cierro la base de datos
            $obj->dbh = null;
            self::$modelo = null; // Borro el objeto.
        }
    }


    // Devuelvo cuantos filas tiene la tabla

    public function numClientes ():int {
      $result = $this->dbh->query("SELECT id FROM Clientes");
      $num = $result->rowCount();  
      return $num;
    } 
    

    // SELECT Devuelvo la lista de Usuarios
    public function getClientes ($primero,$cuantos, $ordenacion, $numorden):array {
        $tuser = [];
        // Crea la sentencia preparada dependiendo de la ordenacion (asc o desc) por el numdeorden
        // echo "<h1> $primero : $cuantos  </h1>";
        if ($numorden == 1) {
            $stmt_usuarios  = $this->dbh->prepare("select * from Clientes order by $ordenacion Desc limit $primero,$cuantos");
        } else {
            $stmt_usuarios  = $this->dbh->prepare("select * from Clientes order by $ordenacion  limit $primero,$cuantos");
        }
        $stmt_usuarios->setFetchMode(PDO::FETCH_CLASS, 'Cliente');
        if ( $stmt_usuarios->execute() ){
           if ( $obj = $stmt_usuarios->fetchAll()){
              $tuser = $obj;
           }
        }
        // Devuelvo el array de objetos
        return $tuser;

    }
    
      
    // SELECT Devuelvo un usuario o false
    public function getCliente (int $id) {
        $cli = false;
        $stmt_cli   = $this->dbh->prepare("select * from Clientes where id=:id");
        $stmt_cli->setFetchMode(PDO::FETCH_CLASS, 'Cliente');
        $stmt_cli->bindParam(':id', $id);
        if ( $stmt_cli->execute() ){
             if ( $obj = $stmt_cli->fetch()){
                $cli= $obj;
            }
        }
        return $cli;
    }

     
    public function getClienteSiguiente($id){

        $cli = false;
        
        $stmt_cli   = $this->dbh->prepare("select * from Clientes where id >? limit 1");
        // Enlazo $id con el primer ? 
        $stmt_cli->bindParam(1,$id);
        $stmt_cli->setFetchMode(PDO::FETCH_CLASS, 'Cliente');
        if ( $stmt_cli->execute() ){
            if ( $obj = $stmt_cli->fetch()){
               $cli= $obj;
           }
       }
        return $cli;

    }

    public function getClienteAnterior($id){

        $cli = false;
        
        $stmt_cli   = $this->dbh->prepare("select * from Clientes where id <? order by id DESC limit 1");
       // Enlazo $id con el primer ? 
        $stmt_cli->bindParam(1,$id);
        $stmt_cli->setFetchMode(PDO::FETCH_CLASS, 'Cliente');
        if ( $stmt_cli->execute() ){
           if ( $obj = $stmt_cli->fetch()){
              $cli= $obj;
          }
        }
       
    return $cli;

    }


    //EJERCICIO 3

    public function getIP($id):String{

        $tvalores = [];

        $stmt = $this->dbh->prepare("SELECT ip_address FROM clientes WHERE id=:id");
        $stmt->bindValue(':id',$id);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Cliente');
        if ( $stmt->execute() ){
           if ( $obj = $stmt->fetch()){
              $tvalores = $obj;
           }
        }
        return($tvalores->ip_address);

    }

    public function getCountryCode($datos_cliente){
        $apiKey = "912efa80785bf1f4ba8d79e96c49f8ed";
        $datos_cliente = json_decode($datos_cliente, true);
        $ip = $datos_cliente['ip'];

        $respuesta = file_get_contents("https://ipapi.co/$ip/country_code?api_key=$apiKey%22");
        return $respuesta;

    }

    public function getFlag($id){
        $db = AccesoDatos::getModelo();
        $datos_cliente = new stdClass();
        $datos_cliente -> ip = $db->getIP($id);
        $datos_cliente = json_encode($datos_cliente);
        $countryName1 = $db->getCountryCode($datos_cliente);
        $countryName = strtolower($countryName1);
        print_r("CountryName: " .$countryName . "<br>");
        return $countryName;
    }


    //EJERCICIO 4

    public function validarCorreo($cliente){

        $valido=false;
        
        $db = AccesoDatos::getModelo();
        $correo = $cliente->email;

        $stmt = $this->dbh->prepare("SELECT email FROM clientes WHERE email=:correo");
        $stmt->bindValue(':correo',$correo);
        $stmt->execute();

        $resultado = $stmt->rowCount();
        if ($resultado != 0){
            //CORREO INVALIDO
        }else{
            //CORREO VALIDO
            $valido=true;
        }
        return $valido;
    }


    public function validarIP($cliente){

        $valido=false;

        $db = AccesoDatos::getModelo();
        $ip = $cliente->ip_address;

        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            //IP VALIDA
            $valido=true;
        } else {
            //IP INVALIDA
        }
        return $valido;
    }


    public function validarTelefono($cliente){

        $valido=false;

        $db = AccesoDatos::getModelo();
        $telefono = $cliente->telefono;

        $reg = "/\d{3}-\d{3}-\d{4}/";

        $resultado = preg_match($reg, $telefono);
        
        if ($resultado === 1){
            //TELEFONO VALIDO
            $valido=true;
        }else{
            //TELEFONO INVALIDO
        }
        return $valido;
    }
    

    public function todoValidado($cliente, $orden){        
        $db = AccesoDatos::getModelo();
        $correo     = $db -> validarCorreo($cliente);
        $ip         = $db -> validarIP($cliente);
        $telefono   = $db -> validarTelefono($cliente);

/*         print_r("Correo: " .$correo . "<br>");
        print_r("IP: " .$ip . "<br>");
        print_r("Telefono: " .$telefono . "<br>"); */

        if ($correo === true && $ip === true && $telefono === true){
            if($orden === "Nuevo"){
                $db->addCliente($cliente);
            }elseif ($orden = "Modificar") {
                $db->modCliente($cliente);
            }
            $db->valido();
            $db->volver();
        }else{
            $db->error();
            $db->volver();
        }
    }

    public function valido(){
        echo "CAMPOS VALIDOS <br>";
        echo "USUARIO AÑADIDO A LA BD";
    }

    public function volver(){
        header("refresh:2;url=index.php");
    }

    public function error(){
        echo "ERROR: CAMPOS NO VALIDOS";
    }


    //EJERCICIO 5

    public function imagenCliente($id){
        $caracteres = strlen($id);
        $ceros = 8 - $caracteres;

        $imagen = "app/uploads/";
        for ($i = 0; $i < $ceros; $i++) {
            $imagen .= "0";
        }
        $imagen .= $id;
        $imagen .= ".jpg";

        
        if (file_exists($imagen)) {
            print_r("Imagen: " .$imagen);
            return $imagen;
        }else{
            $db = AccesoDatos::getModelo();
            return $db->robohash();
        }

    }

    public function robohash(){
        $arr = ["1","2","3","4","5","6","7","8","9"];
        $random = array_rand($arr,3);
        $robohash = "https://robohash.org/".$arr[$random[0]]. $arr[$random[1]]. $arr[$random[2]].".png";
        print_r("RoboHash: " .$robohash);
        return $robohash;
    }


    //EJERCICIO 7

    public function pdfCliente($id){

        $tvalores = [];

        $stmt = $this->dbh->prepare("SELECT * FROM clientes WHERE id=$id");
        /* $stmt->bindValue(':id',$id); */

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Cliente');
        
        if ( $stmt->execute() ){
           if ( $obj = $stmt->fetch()){
              $tvalores = $obj;
           }
        }

        $db = AccesoDatos::getModelo();$db = AccesoDatos::getModelo();
        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetMargins(20, 20, 20);
        $pdf->Cell(10,10, "ID usuario: ".$tvalores->id,0,1,'L');
        $pdf->Cell(10,10, "Nombre: "    .$tvalores->first_name,0,1,'L');
        $pdf->Cell(10,10, "Apellido: "  .$tvalores->last_name,0,1,'L');
        $pdf->Cell(10,10, "Email: "     .$tvalores->email,0,1,'L');
        $pdf->Cell(10,10, "Genero: "    .$tvalores->gender,0,1,'L');
        $pdf->Cell(10,10, "Direccion IP: ".$tvalores->ip_address,0,1,'L');
        $pdf->Cell(10,10, "Telefono: "  .$tvalores->telefono,0,1,'L');

        $resultado = $pdf->Output('DatosCliente.php','I');

        return($resultado);
    }


    //EJERCICIO 8

    public function comprobarUser($login,$password){
        
        /* print_r($login ." - ". $password ."<br>"); */

        $tuser = [];

        $stmt_login = $this->dbh->prepare('SELECT * FROM user WHERE login = :login AND password = :password');
        $stmt_login->bindParam(':login',$login);
        $stmt_login->bindParam(':password',$password);
        $stmt_login->execute();

        if ( $stmt_login->execute() ){
            while ( $user = $stmt_login->fetch()){
                $tuser = $user;
            }
        }
        return $tuser;

    }


    //EJERCICIO 10

    public function MapaIP($id) {
        $db = AccesoDatos::getModelo();
        $datos_cliente = new stdClass();
        $datos_cliente -> ip = $db->getIP($id);
        $datos_cliente = json_encode($datos_cliente);
        $latLng = $db->getLatLng($datos_cliente);
        
        return $latLng;
    }

    public function getLatLng($datos_cliente){
        $apiKey = "912efa80785bf1f4ba8d79e96c49f8ed";
        $datos_cliente = json_decode($datos_cliente, true);
        $ip = $datos_cliente['ip'];

        $lat = file_get_contents("https://ipapi.co/$ip/latitude?api_key=$apiKey%22");
        $lng = file_get_contents("https://ipapi.co/$ip/longitude?api_key=$apiKey%22");
        print_r("<br>".$lat." ".$lng);

        $coords="https://maps.google.com/?q=$lat,$lng&z=15&output=embed";
       
        return $coords;
    }


    // UPDATE TODO
    public function modCliente($cli):bool{
      
        $stmt_moduser   = $this->dbh->prepare("update Clientes set first_name=:first_name,last_name=:last_name".
        ",email=:email,gender=:gender, ip_address=:ip_address,telefono=:telefono WHERE id=:id");
        $stmt_moduser->bindValue(':first_name', $cli->first_name);
        $stmt_moduser->bindValue(':last_name'   ,$cli->last_name);
        $stmt_moduser->bindValue(':email'       ,$cli->email);
        $stmt_moduser->bindValue(':gender'      ,$cli->gender);
        $stmt_moduser->bindValue(':ip_address'  ,$cli->ip_address);
        $stmt_moduser->bindValue(':telefono'    ,$cli->telefono);
        $stmt_moduser->bindValue(':id'          ,$cli->id);

        $stmt_moduser->execute();
        $resu = ($stmt_moduser->rowCount () == 1);
        return $resu;
    }

  
    //INSERT 
    public function addCliente($cli):bool{
       
        // El id se define automáticamente por autoincremento.
        $stmt_crearcli  = $this->dbh->prepare(
            "INSERT INTO `Clientes`( `first_name`, `last_name`, `email`, `gender`, `ip_address`, `telefono`)".
            "Values(?,?,?,?,?,?)");
        $stmt_crearcli->bindValue(1,$cli->first_name);
        $stmt_crearcli->bindValue(2,$cli->last_name);
        $stmt_crearcli->bindValue(3,$cli->email);
        $stmt_crearcli->bindValue(4,$cli->gender);
        $stmt_crearcli->bindValue(5,$cli->ip_address);
        $stmt_crearcli->bindValue(6,$cli->telefono);    
        $stmt_crearcli->execute();
        $resu = ($stmt_crearcli->rowCount () == 1);
        return $resu;
    }

   
    //DELETE 
    public function borrarCliente(int $id):bool {


        $stmt_boruser   = $this->dbh->prepare("delete from Clientes where id =:id");

        $stmt_boruser->bindValue(':id', $id);
        $stmt_boruser->execute();
        $resu = ($stmt_boruser->rowCount () == 1);
        return $resu;
        
    }   
    
    
     // Evito que se pueda clonar el objeto. (SINGLETON)
    public function __clone()
    { 
        trigger_error('La clonación no permitida', E_USER_ERROR); 
    }

    
}




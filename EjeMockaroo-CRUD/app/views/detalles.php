<head>
    <link href='mapbox://styles/mapbox/streets-v12' rel='stylesheet' />
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.2.0/mapbox-gl.js'></script>
</head>


<hr>
<button onclick="location.href='./'" > Volver </button>
<br><br>

<table>
    <tr>
        <td>id:</td> 
        <td><input type="number" name="id" value="<?=$cli->id ?>"  readonly > </td>
        <td rowspan="7"><img src="<?= $foto ?>" alt="foto"></td>
        <td rowspan="7"><img src="https://flagcdn.com/h240/<?= $bandera ?>.png" height="240" alt="bandera"></td>
    </tr>
    <tr>
        <td>first_name:</td> 
        <td><input type="text" name="first_name" value="<?=$cli->first_name ?>" readonly ></td>
    </tr>
    <tr>
        <td>last_name:</td> 
        <td><input type="text" name="last_name" value="<?=$cli->last_name ?>" readonly ></td>
    </tr>
    <tr>
        <td>email:</td> 
        <td><input type="email" name="email" value="<?=$cli->email ?>"   readonly ></td>
    </tr>
    <tr>
        <td>gender</td> 
        <td><input type="text" name="gender" value="<?=$cli->gender ?>" readonly ></td>
    </tr>
    <tr>
        <td>ip_address:</td> 
        <td><input type="text" name="ip_address" value="<?=$cli->ip_address ?>" readonly ></td>
    </tr>
    <tr>
        <td>telefono:</td> 
        <td><input type="tel" name="telefono" value="<?=$cli->telefono ?>" readonly ></td>
    </tr>
</table>

<form>
<input type="hidden"  name="id" value="<?=$cli->id ?>">
<button type="submit" name="nav-detalles" value="Anterior"> Anterior << </button>
<button type="submit" name="nav-detalles" value="Siguiente"> Siguiente >> </button>
<button type="submit" name="imprimirPDF"  value="imprimirPDF"> Imprimir </button>
</form> 
<br><hr><br>

<div id="map">
    <iframe width="100%" src="<?= $coordenadas ?>"></iframe>
</div>

# Entorno-Servidor

## EjeMockaroo-CRUD

**EjeMockaroo-CRUD** consiste en un CRUD realizado en PHP siguiendo el MVC, este incluye distintas funcionalidades y se hace frente a una base de datos Clientes estándar;

![](https://i0.wp.com/www.unica360.com/wp-content/uploads/base_datos_clientes_marketing_analisis_comp.jpg)

<h3>Usar</h3>
- Debe cambiar el usuario y la contraseña de acceso a la BD si estas no son
<b><i>'root' y  ' '</b></i> respectivamente.
- Importar en MySQL las BBDD proporcionadas.
- Copiar la carpeta a la ruta de instalación de PHP.

<h3>Implementaciones</h3>
EjeMockaroo-CRUD incluye las siguientes mejoras:
<ul>
  <li>1. Mostrar en detalles y en modificar la opción de siguiente y anterior.</li>
  <li>2. Mostrar la lista de clientes con distintos modos de ordenación: nombre, apellido, correo electrónico, género o IP y poder navegar por ella.</li>
  <li>3. Mostrar en detalles una bandera del país asociado a la IP ( utilizar geoip y https://flagpedia.net/ ).</li>
  <li>4. Mejorar las operaciones de Nuevo y Modificar para que chequee que los datos son correctos: correo electrónico (no repetido), IP y teléfono con formato 999-999-9999.</li>
  <li>5. Mostrar una imagen asociada al cliente almacenada previamente en uploads o una imagen por defecto aleatoria generada por https://robohasp.org. sin no existe. En nombre de las fotos tiene el formato 00000XXX.jpg para el cliente con id XXX.</li>
  <li>7. Generar un PDF con los todos detalles de un cliente ( Incluir un botón que indique imprimir).</li>
  <li>10. Utilizar geoip y el api para javascript https://openlayers.org o similar para mostrar la localización geográfica del cliente en un mapa en función de su IP.</li>
</ul>

<h1 class="nombre-pagina">Olvidé la contraseña</h1>
<p class="descripcion-pagina">Reestablece tu contraseña escribiendo tu correo a continuación</p>

<?php 

    include_once __DIR__ . "/../templates/alertas.php";

?>

<form action="/olvide" class="formulario" method="post">
    <div class="campo">
        <label for="email">Tu correo electrónico</label>
        <input type="email" id="email" name="email" placeholder="Tu correo electrónico">
    </div>
    <div class="centrar-boton">
        <input type="submit" class="boton" value="Enviar instrucciones">
    </div>
</form>


<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
</div>
<h1 class="nombre-pagina">BARBER P</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>
<?php 

    include_once __DIR__ . "/../templates/alertas.php";

?>
<form method="post" action="/">
    <div class="campo">
        <label for="email">Correo electrónico</label>
        <input 
            type="email"
            id="email"
            placeholder="Tu correo electrónico"
            name="email"
            value="<?php echo s($auth->email); ?>"
        />
    </div>
    <div class="campo">
        <label for="password">Contraseña</label>
        <input 
            type="password"
            id="password"
            placeholder="Tu contraseña"
            name="password"
        />
    </div>
    <div class="centrar-boton">
        <input type="submit" class="boton" value="Iniciar sesión">
    </div>
    


</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes cuenta? Crear una</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>
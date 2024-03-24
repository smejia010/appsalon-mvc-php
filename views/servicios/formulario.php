<div class="campo">
    <label for="nombre">Nombre</label>
    <input 
        type="text" 
        id="nombre" 
        placeholder="Nombre del servicio" 
        name="nombre"
        value="<?php echo $servicio->nombre; ?>"
    /> <!-- FIN DEL INPUT -->
</div>
<div class="campo">
    <label for="precio">Precio</label>
    <input 
        type="text" 
        id="precio" 
        placeholder="Precio del servicio" 
        name="precio" 
        value="<?php echo $servicio->precio; ?>"
    /> <!-- FIN DEL INPUT -->
</div>
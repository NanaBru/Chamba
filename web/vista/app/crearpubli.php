<?php
// Variables: $mensaje, $categorias
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/stylesNav.css">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/crearpubli.css">
<link rel="shortcut icon" href="/chamba/web/vista/assets/img/logopng.png" type="image/x-icon">
<title>Nueva Publicación</title>
</head>
<body>

<nav id="menu">
    <div class="logoDIV1">
        <img class="logo" src="/chamba/web/vista/assets/img/logopng.png" alt="logo">
        <a href="/chamba/web/router.php?page=inicio"><h1 class="logoNombre">Chamba</h1></a>
    </div>
    <div class="LogoDIV2">
        <div class="hamburguesa" onclick="toggleMenu()">
            <div></div><div></div><div></div>
        </div>
        <ul id="navLinks">
            <li><a href="/chamba/web/router.php?page=inicio">Inicio</a></li>
            <li><a href="/chamba/web/router.php?page=perfil">Perfil</a></li>
            <li><a href="/chamba/web/vista/app/usuario/logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>   
</nav>

<main class="crear-publicacion-main">
    <h1 class="page-title">Crear Nueva Publicación</h1>
    
    <form method="post" enctype="multipart/form-data" class="form-publicacion">
        
        <div class="form-group">
            <label for="titulo">Título del Servicio</label>
            <input type="text" name="titulo" id="titulo" maxlength="150" placeholder="Ej: Clases de guitarra para principiantes" required>
        </div>

       <div class="form-group">
    <label for="categoria_search">Categoría</label>
    <div class="categoria-search-wrapper">
        <input type="text" 
               id="categoria_search" 
               placeholder="Buscar categoría... (ej: Carpintería)"
               autocomplete="off"
               oninput="filtrarCategorias()">
        <input type="hidden" name="categoria_id" id="categoria_id_hidden">
        <div id="categorias-lista" class="categorias-dropdown">
            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $cat): ?>
                    <div class="categoria-item" 
                         data-id="<?= $cat['id'] ?>" 
                         data-nombre="<?= strtolower($cat['nombre']) ?>"
                         onclick="seleccionarCategoria(<?= $cat['id'] ?>, '<?= htmlspecialchars($cat['nombre']) ?>')">
                        <span class="cat-icono"><?= htmlspecialchars($cat['icono']) ?></span>
                        <span class="cat-nombre"><?= htmlspecialchars($cat['nombre']) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div id="categoria-seleccionada" class="categoria-selected" style="display:none;"></div>
</div>


        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="6" placeholder="Describe tu servicio en detalle..." required></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="precio">Precio en pesos</label>
                <input type="number" name="precio" id="precio" step="0.01" min="0" placeholder="0.00" required>
            </div>

            <div class="form-group">
                <label for="imagen">Imagen del Servicio</label>
                <input type="file" name="imagen" id="imagen" accept="image/*">
                <small>Formatos aceptados: JPG, PNG, WebP</small>
            </div>
        </div>

        <button type="submit" class="btn-publicar">Publicar Servicio</button>

        <?php if(!empty($mensaje)): ?>
            <div class="msg <?= strpos($mensaje, 'éxito') !== false ? 'msg-success' : 'msg-error' ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
    </form>
</main>

<script src="/chamba/web/vista/assets/js/script.js"></script>


<script>
let categoriaSeleccionada = false;

function filtrarCategorias() {
    const input = document.getElementById('categoria_search');
    const filtro = input.value.toLowerCase();
    const lista = document.getElementById('categorias-lista');
    const items = lista.getElementsByClassName('categoria-item');
    
    if (filtro === '') {
        lista.style.display = 'none';
        return;
    }
    
    lista.style.display = 'block';
    let hayResultados = false;
    
    for (let item of items) {
        const nombre = item.getAttribute('data-nombre');
        if (nombre.includes(filtro)) {
            item.style.display = 'flex';
            hayResultados = true;
        } else {
            item.style.display = 'none';
        }
    }
    
    if (!hayResultados) {
        lista.innerHTML = '<div class="sin-resultados">No se encontraron categorías</div>';
        lista.style.display = 'block';
    }
}

function seleccionarCategoria(id, nombre) {
    document.getElementById('categoria_id_hidden').value = id;
    document.getElementById('categoria_search').value = '';
    document.getElementById('categorias-lista').style.display = 'none';
    
    const seleccionadaDiv = document.getElementById('categoria-seleccionada');
    seleccionadaDiv.innerHTML = `
        <span>Categoría seleccionada: <strong>${nombre}</strong></span>
        <button type="button" onclick="limpiarCategoria()">✖</button>
    `;
    seleccionadaDiv.style.display = 'flex';
    categoriaSeleccionada = true;
}

function limpiarCategoria() {
    document.getElementById('categoria_id_hidden').value = '';
    document.getElementById('categoria_search').value = '';
    document.getElementById('categoria-seleccionada').style.display = 'none';
    categoriaSeleccionada = false;
}

// Cerrar dropdown al hacer clic fuera
document.addEventListener('click', function(e) {
    const wrapper = document.querySelector('.categoria-search-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('categorias-lista').style.display = 'none';
    }
});
</script>

</body>
</html>

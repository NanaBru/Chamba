<?php
// web/vista/app/inicio.php
// Variables esperadas: $usuario (o null) y $publicaciones (array)
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/stylesNav.css">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/inicio.css">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/footer.css">
<link rel="shortcut icon" href="/chamba/web/vista/assets/img/logopng.png" type="image/x-icon">
<title>Inicio - Chamba</title>
</head>
<body>

<nav id="menu">
    <div class="logoDIV1">
        <img class="logo" src="/chamba/web/vista/assets/img/logopng.png" alt="logo">
        <a href="/chamba/web/router.php?page=inicio"><h1 class="logoNombre">Chamba</h1></a>
    </div>
    
    <!-- Barra de búsqueda con autocompletado -->
    <div class="search-container">
        <form action="/chamba/web/router.php" method="get" class="search-form" id="searchForm">
            <input type="hidden" name="page" value="inicio">
            <div class="autocomplete-wrapper">
                <input type="text" 
                       name="buscar" 
                       id="searchInput"
                       placeholder="Buscar servicios..." 
                       value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>"
                       class="search-input"
                       autocomplete="off">
                <div id="autocomplete-list" class="autocomplete-items"></div>
            </div>
            <button type="submit" class="search-btn">🔍</button>
        </form>
    </div>
    
    <div class="LogoDIV2">
        <div class="hamburguesa" onclick="toggleMenu()">
            <div></div><div></div><div></div>
        </div>
        <ul id="navLinks">
            <li><a href="/chamba/web/router.php?page=inicio">Inicio</a></li>
            <?php if(isset($_SESSION['usuario_id'])): ?>
                <li><a href="/chamba/web/router.php?page=perfil">Perfil</a></li>
                <li><a href="/chamba/web/router.php?page=crear-publicacion">Crear Publicación</a></li>
                <li><a href="/chamba/web/vista/app/usuario/logout.php">Cerrar Sesión</a></li>
            <?php else: ?>
                <li><a href="/chamba/web/router.php?page=sesion">Iniciar Sesión</a></li>
            <?php endif; ?>
        </ul>
    </div>   
</nav>


<!-- Mostrar mensaje si hay búsqueda -->
<?php if (!empty($busqueda)): ?>
    <div class="search-info">
        <p>Resultados para: <strong>"<?= htmlspecialchars($busqueda) ?>"</strong> 
           (<?= count($publicaciones) ?> <?= count($publicaciones) === 1 ? 'resultado' : 'resultados' ?>)
        </p>
        <a href="/chamba/web/router.php?page=inicio" class="clear-search">Limpiar búsqueda</a>
    </div>
<?php endif; ?>


<main>
    <section>
        <h2>Publicaciones disponibles</h2>

        <?php if (!empty($publicaciones)): ?>
            <div class="publicaciones">
                <?php foreach ($publicaciones as $p): ?>
                    <div class="card" onclick="window.location.href='/chamba/web/router.php?page=publicacion&id=<?= urlencode((string)($p['id'] ?? '')) ?>'">
                        <?php if (!empty($p['imagen'])): ?>
                            <img src="/chamba/web/datos/publicasiones/<?= htmlspecialchars($p['imagen']) ?>" alt="imagen de la publicación">
                        <?php endif; ?>

                        <h4><?= htmlspecialchars($p['titulo'] ?? 'Sin título') ?></h4>

                        <?php
                        // Render de estrellas (requiere que el modelo retorne 'rating' con AVG)
                        $rating = is_numeric($p['rating'] ?? null) ? (float)$p['rating'] : 0.0;
                        $full   = (int)floor($rating);
                        $half   = ($rating - $full) >= 0.5 ? 1 : 0;
                        $empty  = 5 - $full - $half;
                        ?>
                        <div class="stars" aria-label="Calificación <?= htmlspecialchars(number_format($rating,1)) ?> de 5">
                            <?php for ($i = 0; $i < $full; $i++): ?>
                                <span class="star full">★</span>
                            <?php endfor; ?>
                            <?php if ($half): ?>
                                <span class="star half">☆</span>
                            <?php endif; ?>
                            <?php for ($i = 0; $i < $empty; $i++): ?>
                                <span class="star empty">✩</span>
                            <?php endfor; ?>
                            <span class="rating-num">(<?= htmlspecialchars(number_format($rating,1)) ?>)</span>
                        </div>

                        <?php if (!empty($p['precio'])): ?>
                            <span class="precio"><?= htmlspecialchars($p['precio']) ?></span>
                        <?php endif; ?>

                        <?php if (!empty($p['descripcion'])): ?>
                            <p><?= nl2br(htmlspecialchars($p['descripcion'])) ?></p>
                        <?php endif; ?>

                        <small>
                            <?php
                                $autor = trim(($p['nombre'] ?? '') . ' ' . ($p['apellido'] ?? ''));
                                $autor = $autor !== '' ? $autor : 'Autor desconocido';
                                $fecha = $p['fecha'] ?? '';
                                echo htmlspecialchars("Por: {$autor}" . ($fecha ? " — {$fecha}" : ''));
                            ?>
                        </small>

                        <div class="hover-text">Ver detalles</div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No hay publicaciones aún.</p>
        <?php endif; ?>
    </section>
</main>

<?php include __DIR__ . '/../secciones/footer.php'; ?>
</body>
<script>
// Autocompletado en tiempo real
const searchInput = document.getElementById('searchInput');
const autocompleteList = document.getElementById('autocomplete-list');
let currentFocus = -1;

searchInput.addEventListener('input', function() {
    const val = this.value.trim();
    closeAllLists();
    
    if (val.length < 2) return;
    
    currentFocus = -1;
    
    // Petición AJAX
    fetch(`/chamba/web/api/buscarSugerencias.php?q=${encodeURIComponent(val)}`)
        .then(response => response.json())
        .then(sugerencias => {
            if (sugerencias.length === 0) return;
            
            sugerencias.forEach(item => {
                const div = document.createElement('div');
                div.classList.add('autocomplete-item');
                
                // Resaltar texto coincidente
                const match = item.toLowerCase().indexOf(val.toLowerCase());
                if (match !== -1) {
                    div.innerHTML = 
                        item.substr(0, match) + 
                        '<strong>' + item.substr(match, val.length) + '</strong>' + 
                        item.substr(match + val.length);
                } else {
                    div.textContent = item;
                }
                
                div.addEventListener('click', function() {
                    searchInput.value = item;
                    closeAllLists();
                    document.getElementById('searchForm').submit();
                });
                
                autocompleteList.appendChild(div);
            });
        })
        .catch(err => console.error('Error:', err));
});

// Navegación con teclado
searchInput.addEventListener('keydown', function(e) {
    let items = autocompleteList.getElementsByClassName('autocomplete-item');
    
    if (e.keyCode === 40) { // Flecha abajo
        currentFocus++;
        addActive(items);
    } else if (e.keyCode === 38) { // Flecha arriba
        currentFocus--;
        addActive(items);
    } else if (e.keyCode === 13) { // Enter
        e.preventDefault();
        if (currentFocus > -1 && items[currentFocus]) {
            items[currentFocus].click();
        }
    }
});

function addActive(items) {
    if (!items) return false;
    removeActive(items);
    if (currentFocus >= items.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = items.length - 1;
    items[currentFocus].classList.add('autocomplete-active');
}

function removeActive(items) {
    for (let i = 0; i < items.length; i++) {
        items[i].classList.remove('autocomplete-active');
    }
}

function closeAllLists() {
    autocompleteList.innerHTML = '';
    currentFocus = -1;
}

// Cerrar al hacer clic fuera
document.addEventListener('click', function(e) {
    if (e.target !== searchInput) {
        closeAllLists();
    }
});
</script>

</html>

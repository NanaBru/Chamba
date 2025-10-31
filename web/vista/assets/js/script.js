function toggleMenu() {
            const nav = document.getElementById("navLinks");
            nav.classList.toggle("show");
        }

function noEspacios(event) {
            if (event.key === ' ' || event.key === 'Spacebar') {
                event.preventDefault();
            } 
}
       
function soloLetras(event) {

         if (/^[0-9]$/.test(event.key)) {

                event.preventDefault();
            }
}

function mostrarNotificacion() {
   
    alert("Contactanos por correo: chambaserviciosuy@gmail.com");
    header("Location: ../vista/usuario/perfil.php");
    
}
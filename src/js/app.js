const mobileMenuBtn = document.querySelector('#mobile-menu');
const cerrarMenuBtn = document.querySelector('#cerrar-menu');

const sideBar = document.querySelector('.sidebar');
if(mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', () => {
        sideBar.classList.toggle('mostrar');
    });
}

if(cerrarMenuBtn) {
    cerrarMenuBtn.addEventListener('click', () => {
        sideBar.classList.add('ocultar');

        setTimeout(() => {
            sideBar.classList.remove('mostrar');
            sideBar.classList.remove('ocultar');
        }, 500);
    })
}

// Elimina la clase de mostrar en un tamaño de tablet o mayores
const anchoPantalla = document.body.clientWidth;
window.addEventListener('resize', function() {
    const anchoPantalla = document.body.clientWidth;
    if(anchoPantalla >= 768) {
        sideBar.classList.remove('mostrar');
    }
})
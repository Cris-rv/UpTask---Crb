    const btnEliminarProyecto = document.querySelector('#eliminar-proyecto');
    btnEliminarProyecto.addEventListener('click', function() {
        mostrarFormularioEliminar();
    })
    
    function mostrarFormularioEliminar() {
        const modalProyecto = document.createElement('DIV');
        modalProyecto.classList.add('modal');
        modalProyecto.innerHTML = `
            <form class="formulario nueva-tarea">
            <legend> Eliminar Proyecto </legend>
                <div class="campo">
                    <h3>¿Estas Seguro de Eliminar el Proyecto?</h3>
                </div>
                <div class="opciones">
                    <input
                    type="submit"
                    id="submit-eliminar-proyecto"
                    class="submit-eliminar-proyecto"
                    value="Eliminar Proyecto"
                    />
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form> `;

            setTimeout(() => {
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('animar');
            }, 0);

            modalProyecto.addEventListener('click', function(e) {
                e.preventDefault();
                if(e.target.classList.contains('cerrar-modal')) {
                    const formulario = document.querySelector('.formulario');
                    formulario.classList.add('cerrar');
                    setTimeout(() => {
                        modalProyecto.remove();
                    }, 500);
                }

                if(e.target.classList.contains('submit-eliminar-proyecto')) {
                    urlProyecto = obtenerProyecto();
                    EliminarProyecto(urlProyecto)
                }
            })

        document.querySelector('.dashboard').appendChild(modalProyecto);
    }

    async function EliminarProyecto(urlproyecto) {
        const datos = new FormData();
        datos.append('urlProyecto', urlproyecto);

        try {
            const url = `${location.origin}/api/proyecto/eliminar`;

            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            })
            const resultado = await respuesta.json();
            if(resultado.tipo === 'exito') {
                Swal.fire(
                    resultado.mensaje,
                    resultado.mensaje,
                    'success'
                ).then(() => {
                    window.location.href = resultado.redirect;
                });

                const modal = document.querySelector('.modal');
                if(modal) {
                    modal.remove();
                }
            }
        } catch (error) {
            console.log(error)
        }
    }

    function obtenerProyecto() { // Obtenemos la url de la ventana actual mediante su query string
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
    }
( function() { // IIFE Immediately Invoked Function Expression

    obtenerTareas();
    let tareas = [];
    let filtradas = []; // Filtradas es un arreglo vacio para poder iterar las tareas como un filter

    // Boton para mostrar el modal de agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', function() {
        mostrarFormulario();
    });

    async function obtenerTareas() {
        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?id=${id}`
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            tareas = resultado.tareas;
            mostrarTareas();
        } catch (error) {
            console.log(error);
        }
    };

    // Filtros de busqueda 
    const filtros = document.querySelectorAll('#filtros input[type="radio"]');
    filtros.forEach( radio => {
        radio.addEventListener('input', filtrarTareas);
    } )

    function filtrarTareas(e) {
     const filtro = e.target.value;
     
     if(filtro !== '') {
        // Filter solo funciona para arreglos, por eso filtradas es un arreglo vacio y es una variable global
        filtradas = tareas.filter(tareaFiltradas => tareaFiltradas.estado === filtro);
     } else {
        filtradas = [];
     }

     mostrarTareas();
    }


    function mostrarTareas() {
        limpiarTareas();
        totalPendientes();
        totalCompletadas();

        // Si el arreglo de filtradas tiene algo entonces muestra las tareas filtradas, pero si esta vacia muestra todas las tareas
        const arrayTareas = filtradas.length ? filtradas : tareas;

        if(arrayTareas.length === 0) {
            const contenedorTareas = document.querySelector('#listado-tareas');

            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = "No hay tareas";
            textoNoTareas.classList.add('no-tareas');

            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        const estados = {
            0: 'Pendiente',
            1: 'Completa'
        }
        arrayTareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');

            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function() {
                mostrarFormulario(editar = true, {...tarea});
            }
            contenedorTarea.appendChild(nombreTarea);

            const opcionesDIV = document.createElement('DIV');
            opcionesDIV.classList.add('opciones');

            // Botones
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`)
            // Este diccionario detecta si es 0 o 1 para mostrar si la tarea esta pendiente o completa
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function() {
                cambiarEstadoTarea({...tarea});
            }

            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = "Eliminar";
            btnEliminarTarea.ondblclick = function() {
                confirmarEliminarTarea({...tarea});
            }

            opcionesDIV.appendChild(btnEstadoTarea);
            opcionesDIV.appendChild(btnEliminarTarea);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDIV);

            const listadoTareas = document.querySelector('#listado-tareas');
            listadoTareas.appendChild(contenedorTarea);
        })
    }

    function totalPendientes() {
        const totalPendientes = tareas.filter(tarea => tarea.estado === '0');
        const pendientesRadio = document.querySelector('#pendientes');

        if (totalPendientes.length === 0) {
            pendientesRadio.disabled = true;
        } else {
            pendientesRadio.disabled = false;
        }
    }

    function totalCompletadas() {
        const totalCompletadas = tareas.filter(tarea => tarea.estado === '1');
        const completadasRadio = document.querySelector('#completadas');

        if (totalCompletadas.length === 0) {
            completadasRadio.disabled = true;
        } else {
            completadasRadio.disabled = false;
        }
    }

    function mostrarFormulario(editar = false, tarea = {}) {
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
            <legend> ${editar ? 'Editar Tarea' : 'Añade una nueva Tarea'} </legend>
                <div class="campo">
                    <label for="tarea">Tarea</label>
                    <input
                        type="text"
                        id="tarea"
                        name="tarea"
                        placeholder="${tarea.nombre ? 'Edita la Tarea' : 'Añadir Tarea Al Proyecto Actual'}"
                        value="${tarea.nombre ? tarea.nombre : ''}"
                    />
                </div>
                <div class="opciones">
                    <input
                    type="submit"
                    class="submit-nueva-tarea"
                    value="${editar ? 'Guardar Cambios' : 'Añadir Tarea'}"
                    />
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form> `;

            setTimeout(() => {
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('animar');
            }, 0);

            modal.addEventListener('click', function(e) {
                e.preventDefault();
                if(e.target.classList.contains('cerrar-modal')) {
                    const formulario = document.querySelector('.formulario');
                    formulario.classList.add('cerrar');
                    setTimeout(() => {
                        modal.remove();
                    }, 500);
                }
                
                if(e.target.classList.contains('submit-nueva-tarea')) {
                    const nombreTarea = document.querySelector('#tarea').value.trim();
                    if(nombreTarea === '') {
                        // Mostrar alerta de error
                        mostrarAlerta('El nombre de la tarea es obligatorio', 'error', document.querySelector('.formulario legend'));
                        return;
                    } 

                    if(editar) {
                        tarea.nombre = nombreTarea;
                        actualizarTarea(tarea);
                    } else {
                        agregarTarea(nombreTarea);
                    }

                }

            })

        document.querySelector('.dashboard').appendChild(modal);
    }

    // Muestra un mensaje en la interfaz
    function mostrarAlerta(mensaje, tipo, referencia) {
        // Previene la creacion de multiples alertas
        const alertaPrevia = document.querySelector('.alerta');

        if(alertaPrevia) {
            alertaPrevia.remove();
        }

        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;

        // Inserta la alerta antes del legend
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        setTimeout(() => {
            alerta.remove();
        }, 3000);
        
    }

    // Consultar el Servidor para añadir una nueva tarea al proyecto acutal
   async function agregarTarea(tarea) {
        // Construir la petición
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('urlProyecto', obtenerProyecto());  

        try {
            const url = 'http://localhost:3000/api/tarea'; // Traemos la url de la API que queramos consultar
            const respuesta = await fetch(url, { // El primer await es para la conexion con el API
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();
            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));

            if(resultado.tipo === 'exito') {
                const modal = document.querySelector('.modal');

                setTimeout(() => {
                    modal.remove();
                }, 1000);

                //Agregar el objeto de tarea al global
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                }
                
                tareas = [...tareas, tareaObj];
                mostrarTareas();
            }
        } catch (error) {
            console.log(error);
        }
    }

    function cambiarEstadoTarea(tarea) {
        // Si el estado es igual a 1 su nuevo valor sera 0 caso contrario retorna 1
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEstado
        actualizarTarea(tarea);
    }

    async function actualizarTarea(tarea) {
        const {estado, id, nombre} = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = 'http://localhost:3000/api/tarea/actualizar';

            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            })
            const resultado = await respuesta.json();
            if(resultado.respuesta.tipo === 'exito') {
                Swal.fire(
                    resultado.respuesta.mensaje,
                    resultado.respuesta.mensaje,
                    'success'
                );

                const modal = document.querySelector('.modal');
                if(modal) {
                    modal.remove();
                }

                // Map crea un arreglo temporal para evitar modificar el arreglo original y es como si compararamos tareas.id === id y retornamos tareaMemoria para obtener 
                tareas = tareas.map(tareaMemoria => {
                    if(tareaMemoria.id === id) { // Colocamos estado ya que fue modificado en el global asi que el estado solo contiene el cambio de 0 a 1 o 1 a 0
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }
                    // retornamos a tareas el arreglo modificado para mostrar el cambio 
                    return tareaMemoria;
                });
                // mostrarTareas reconstruye las tareas con los cambios que realizamos
                mostrarTareas();
            }
        } catch (error) {
            console.log(error)
        }

    }

    function confirmarEliminarTarea(tarea) {
        Swal.fire({
        title: "¿Eliminar Tarea?",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "No"
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            eliminarTarea(tarea);
        }
        });
    }

    async function eliminarTarea(tarea) {

        const {estado, id, nombre} = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = 'http://localhost:3000/api/tarea/eliminar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            
            const resultado = await respuesta.json();
            if(resultado.resultado) {
                // mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.contenedor-nueva-tarea'));

                swal.fire('Eliminado!', resultado.mensaje, 'success');

                // Trae todas las que sean diferentes al que le di eliminar
                tareas = tareas.filter( tareaMemoria => tareaMemoria.id !== tarea.id );

                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }
    }

    function obtenerProyecto() { // Obtenemos la url de la ventana actual mediante su query string
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
    }
    
    function limpiarTareas() {
        const listadoTareas = document.querySelector('#listado-tareas');

        while(listadoTareas.firstChild) {
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }
})(); 
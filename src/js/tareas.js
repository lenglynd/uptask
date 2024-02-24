
(function () {
    
    obtenerTareas();
    
    let tareas = [];
    let filtradas = [];
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', function () {
        mostrarFormulario();
    });
    const filtros = document.querySelectorAll('#filtros input[type="radio"]');
    filtros.forEach(radio => {
        radio.addEventListener('input', filtrarTareas);
    });

    function filtrarTareas(e) {
        const filtro = e.target.value;
        if (filtro !=='') {
            filtradas = tareas.filter(tarea => tarea.estado === filtro);
        } else {
            filtradas = [];
        }
        mostrarTareas();
    }
    async function obtenerTareas() {
        
        try {
            const id = obtenerProyecto();
            const url = `${Location.origin}/api/tareas?id=${id}`;
            
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();
            //    const { tareas } = resultado;
            tareas = resultado.tareas;
            
            //    mostrarTareas(tareas);
            mostrarTareas();
        } catch (error) {
            
        }
    }
    
    function mostrarTareas() {
        limpiarTareas();
        totalPendientes();
        totalCompletas();
        const arrayTareas = filtradas.length ? filtradas : tareas;
        if (arrayTareas.length === 0) {
            const contenedor = document.querySelector('#listado-tareas');
            const textNoTareas = document.createElement('LI');
            textNoTareas.textContent = 'No hay tareas';
            textNoTareas.classList.add('no-tareas');
            contenedor.append(textNoTareas);
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
            nombreTarea.ondblclick = function () {
                mostrarFormulario(true, { ...tarea });
            }
            
            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function () {
                cambiarEstadoTarea({...tarea });
            }
            
            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.ondblclick = function () {
                confimarEliminarTarea({...tarea});
            }
            
            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);
            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);
            const listadoTarea = document.querySelector('#listado-tareas');
            listadoTarea.appendChild(contenedorTarea);
            
        });
        
    }
    function totalPendientes() {
        const totalPendientes = tareas.filter(tarea => tarea.estado === "0");
        const pendientRadio = document.querySelector('#pendientes');
        if (totalPendientes.length === 0) {
            pendientRadio.disabled = true; 
        } else {
            pendientRadio.disabled = false; 
            
        }
    }
    function totalCompletas() {
        const totalCompletas = tareas.filter(tarea => tarea.estado === "1");
        const pendientRadio = document.querySelector('#completadas');
        if (totalCompletas.length === 0) {
            pendientRadio.disabled = true; 
        } else {
            pendientRadio.disabled = false; 
            
        }
        
    }
    
    function mostrarFormulario(editar = false, tarea = {}) {
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
        <form class="formulario nueva-tarea">
        <legend>${editar ? 'Editar tarea' : 'Añade una nueva tarea'}</legend>
        <div class="campo">
        <label>Tarea</label>
        <input
        type="text"
        name="tarea"
        placeholder="${tarea.nombre ? 'editar tarea' : 'Añadir tarea al Proyecto Final'}"
        id="tarea"
        value="${tarea.nombre ? tarea.nombre : ''}"
        />
        </div>
        <div class="opciones">
        <input
        type="submit"
        class="submit-nueva-tarea"
        value="${tarea.nombre ? 'Guardar Cambio' : 'Añadir Tarea'}"
        />
        <button type="button" class="cerrar-modal">Cancelar</button>
        
        </div>
        </form>
        `;
        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);
        /*Delegation es cuando tenemos contenido html generado por js no podemos asociarle una funcion a algo que no existe aun por ello existe DELEGATION*/
        modal.addEventListener('click',function (e) {
            e.preventDefault();
            if (e.target.classList.contains('cerrar-modal')) {
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    //  window.location.reload(); para recargar la pagina al hacer algun cambio en los datos y vuelve a hacer consulta y mostrar los cambios 
                    modal.remove();
                }, 600);
                
            }
            if (e.target.classList.contains('submit-nueva-tarea')){
                const nombreTarea = document.querySelector('#tarea').value.trim();
                if (nombreTarea === '') {
                    mostrarAlerta('El nombre de la tarea es obligatorio','error', document.querySelector('.formulario legend'));
                    return;
                }  
                if (editar) {
                    tarea.nombre = nombreTarea;
                    acturalizarTarea(tarea);
                } else {
                    agregarTarea(nombreTarea);
                    
                }
            }
        })
        
        document.querySelector('.dashboard').appendChild(modal);
    }
    
    function mostrarAlerta(mensaje, tipo, referencia) {
        
        const alertaPrevia = document.querySelector('.alerta');
        if (alertaPrevia) {
            alertaPrevia.remove();
        }
        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;
        
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);
        // referencia.parentElement}
        // referencia.nextElementSibling
        setTimeout(() => {
            alerta.remove();
        }, 5000);
    }
    async  function agregarTarea(tarea) {
        limpiarTareas();
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoid', obtenerProyecto());
        
        
        
        
        try {
            const url = `${Location.origin}/api/tareas`;
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            mostrarAlerta(resultado.mensaje,resultado.tipo, document.querySelector('.formulario legend'));
            if (resultado.tipo === 'exito') {
                limpiarTareas();
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                    
                }, 3000);
                
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoid: resultado.proyectoid
                }
                
                tareas = [...tareas, tareaObj];
                
                mostrarTareas();
            }
        } catch (error) {
            
        }
    }
    function cambiarEstadoTarea(tarea) {
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEstado;
        
        acturalizarTarea(tarea);
    }
    async function acturalizarTarea(tarea) {
        const { estado, id, nombre, proyectoid } = tarea;
        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoid', obtenerProyecto());
        // for (let  valor of datos.values) {
        //     const valor; lleer los valeres de datos en consola 
        
        // }
        try {
            //ha ver si llos cambio suben o no
            const url = `${Location.origin}/api/tareas/actualizar`;
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            
            
            if (resultado.tipo === 'exito') {
                
                // mostrarAlerta(resultado.respuesta.mensaje, resultado.respuesta.tipo, document.querySelector('.contenedor-nueva-tarea'));
                Swal.fire(resultado.mensaje, resultado.mensaje, 'success');
                const modal = document.querySelector('.modal');
                if (modal) {
                    modal.remove();
                    
                }
            }
            tareas = tareas.map(tareaMemoria => {
                if (tareaMemoria.id === id) {
                    tareaMemoria.estado = estado;     
                    tareaMemoria.nombre = nombre;     
                }
                return tareaMemoria;
            });
            mostrarTareas();
        } catch (error) {
            
        }
    }
    function confimarEliminarTarea(tarea) {
        Swal.fire({
            title: "Eliminar Tarea",
            showCancelButton: true,
            confirmButtonText: "Si",
            cancelButtonText: `No`
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            }
        });
    }
    async function eliminarTarea(tarea) {
        const { estado, id, nombre } = tarea;
        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoid', obtenerProyecto());
        try {
            const url = `${Location.origin}/api/tareas/eliminar`;
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
       
           
                    
            
            if (resultado.resultado) {
                
                // mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.contenedor-nueva-tarea'));
                Swal.fire('Eliminado!', resultado.mensaje, 'success');
                
                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);
                mostrarTareas();
            }
        } catch (error) {
            
        }
    }
    function obtenerProyecto() {
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        
        //   se utiliza para leer las entidades de un objeto
        return proyecto.id;
    }
    function limpiarTareas() {
        const listatoTareas = document.querySelector('#listado-tareas');
        // listatoTareas.innerHTML = ''; este por ser muy intensivo en el uso de recursos en mejor el codigo de abajo
        while (listatoTareas.firstChild) {
            listatoTareas.removeChild(listatoTareas.firstChild);
            
        }
    }
    
})();
/**
 * 
 * @author : jorge bonett   jbn19860220@gmail.com
 * 
 */
document.addEventListener('DOMContentLoaded', function () {
    Crear();
    crearPerfil();
    window.datosEventoid = 0;
    window.datosPerfilid = 0;
});

//crea el evento
function Crear() {
    const form = document.getElementById('form-event');

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const nombre = form.nombre.value.trim();
        const color = form.color.value.trim();
        const fechainicio = form.fechainicio.value.trim();
        const fechafin = form.fechafin.value.trim();

        // Validar que todos los campos estén completos
        if (nombre === '' || color === '' || fechainicio === '' || fechafin === '') {
            Swal.fire({
                title: 'Info!',
                text: `Por favor complete todos los campos.`,
                icon: 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                titleColor:'#5a6268',
                iconColor:'#5a6268'
            });
            return;
        }

        // Validar que la fecha de inicio no sea menor que la actual
        const fechaActual = new Date().toISOString().split('T')[0]; 
        if (fechainicio < fechaActual) {
            Swal.fire({
                title: 'Info!',
                text: `La fecha de inicio no puede ser menor a la fecha actual.`,
                icon: 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                titleColor:'#5a6268',
                iconColor:'#5a6268'
            });
            return;
        }

        // Validar que la fecha de fin no sea menor que la fecha de inicio
        if (fechafin < fechainicio) {
            Swal.fire({
                title: 'Info!',
                text: `La fecha de fin no puede ser menor que la fecha de inicio.`,
                icon: 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                titleColor:'#5a6268',
                iconColor:'#5a6268'
            });
            return;
        }

        const formData = new FormData(form);
        formData.append('nombre', nombre);
        formData.append('color', color);
        formData.append('fechainicio', fechainicio);
        formData.append('fechafin', fechafin);
        formData.append('accion', 'crear');

        if (form.img.files.length > 0) {
            formData.append('img', form.img.files[0]);
        }

        fetch('controllers/evento.php', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al enviar la petición ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if(data.error){
                Swal.fire({
                    title: 'Oops!',
                    text: data.message,
                    icon: 'info',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#5a6268'
                });
                return 
            }
          
                /* window.datosEventoid = data.id.eventoid; */
                Swal.fire({
                    title: 'OK',
                    text: `Operación exitosamente`,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#5a6268',
                    titleColor:'#5a6268',
                    iconColor:'#5a6268'
                });
                // Remueve la clase 'active' del tab de "Evento"
                document.getElementById('evento-tab').classList.remove('active');
                document.getElementById('evento-tab').setAttribute('aria-selected', 'false');
                document.getElementById('evento-tab').removeAttribute('data-toggle');
                document.getElementById('evento-tab').setAttribute('href', '#');

                // Activa el tab de "Perfil"
                document.getElementById('perfil-tab').classList.add('active');
                document.getElementById('perfil-tab').setAttribute('aria-selected', 'true');
                document.getElementById('perfil-tab').setAttribute('data-toggle', 'tab');
                document.getElementById('perfil-tab').setAttribute('href', '#perfil');

                // Muestra el contenido de la pestaña "Perfil" y oculta "Evento"
                document.getElementById('evento').classList.remove('show', 'active');
                document.getElementById('perfil').classList.add('show', 'active');

                // Establece el valor seleccionado en el select de eventos
                // data.datos.eventoid;
                SelectOpciones('selectEventoId',data.datos,['eventoid','nombre']);
                document.getElementById('selectEventoId').value=data.id;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
}
const SelectOpciones = (elementoHtml, opciones, [value, text]) => {
    if (!document.getElementById(elementoHtml)) {
      return;
    }
  
    let opcionesHtml = '<option value="">Seleccione una opción</option>';
    if (opciones) {
      opcionesHtml += opciones
        .map((opcion) => {
          return `<option value="${opcion[value]}">${opcion[text]}</option>`;
        })
        .join(" ");
      document.getElementById(elementoHtml).innerHTML = opcionesHtml;
     /*  if (!$(`#${elementoHtml}`).attr("data-select2-id")) {
        $(`#${elementoHtml}`).select2();
      } */
    }
  };
//listar
function listarPerfiles(data) {
    const tableBody = document.getElementById('perfilTableBody');
    const btnNext = document.getElementById('btn-next');
    tableBody.innerHTML = '';
    if (data.datos.length > 0) {
        data.datos.forEach(perfil => {
            const newRow = `<tr data-perfilid="${perfil.perfilid}">
                <td>${perfil.nombre}</td>
                <td>
                    <input type="checkbox" id="checkBono${perfil.perfilid}" ${perfil.bono === '1' ? 'checked' : ''} onchange="toggleBono(${perfil.perfilid})">
                </td>
                <td>
                ${perfil.fechacreacion}
            </td>
                <td>
                    <button  class="btn btn-danger btn-xs btn-min" onclick="eliminarPerfil(${perfil.perfilid}, ${perfil.eventoid})">
                    <i class="fa fa-trash" aria-hidden="true"></i> Eliminar
                    </button>
                </td>
            </tr>`;
            tableBody.insertAdjacentHTML('beforeend', newRow);
        });
        btnNext.style.display = 'block';
    } else {
        const emptyRow = `<tr><td colspan="4" style="text-align: center;">Este evento no tiene perfiles asignados.</td></tr>`;
        tableBody.insertAdjacentHTML('beforeend', emptyRow);
        btnNext.style.display = 'none';
    }
}


//crear el perfil sin bonos ni puntos
function crearPerfil() {
    const formProfile = document.getElementById('form-profile');
    formProfile.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Verificar si se ha seleccionado un evento
        if (formProfile.selectEventoId.value.trim() === '') {
            Swal.fire({
                title: 'Info!',
                text: `Por favor seleccione un evento.`,
                icon: 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                titleColor:'#5a6268',
                iconColor:'#5a6268'
            });
            return;
        }

        // Verificar si se ha ingresado un nombre para el perfil
        if (formProfile.nombrePerfil.value.trim() === '') {
            Swal.fire({
                title: 'Info!',
                text: `Por favor Por favor ingrese un nombre para el perfil.`,
                icon: 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                titleColor:'#5a6268',
                iconColor:'#5a6268'
            });
            return;
        }

        const formData = new FormData(formProfile);
        formData.append('eventoid', formProfile.selectEventoId.value);
        formData.append('nombrePerfil', formProfile.nombrePerfil.value);
        formData.append('accion', 'crear');

        fetch('controllers/perfilEvento.php', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al enviar la petición ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    window.datosPerfilid = data.datos.perfilid;
                    Swal.fire({
                        title: 'Info!',
                        text: `Perfil creado exitosamente`,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#5a6268',
                        titleColor:'#5a6268',
                        iconColor:'#5a6268'
                    });
                    document.getElementById('nombrePerfil').value = '';
                    listarPerfiles(data);
                } else if (data.datos === 'existe') {
                    Swal.fire({
                        title: 'Info!',
                        text: `El nombre del perfil ya existe. Por favor, elija otro nombre.`,
                        icon: 'info',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#5a6268',
                        titleColor:'#5a6268',
                        iconColor:'#5a6268'
                    });
                } else {
                    alert('Error al crear el perfil: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
}


function editarPerfil(perfilId,bono) {
    const formData = new URLSearchParams();
    formData.append('perfilid', perfilId);
    formData.append('bono', bono ? 1 : 0);
    formData.append('accion', 'editar');
    fetch('controllers/perfilEvento.php', {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al enviar la petición ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Info!',
                    text: `Operacion exitosa`,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#5a6268',
                    titleColor:'#5a6268',
                    iconColor:'#5a6268'
                });
            } else {
                alert('Error actualizando el perfil: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
//para eliminar perfiles
function eliminarPerfil(perfilId, eventoId) {
    Swal.fire({
        icon:'warning',
        text: '¿Está seguro de que desea eliminar este perfil?,"ADVERTENCIA: " Al hacerlo eliminara todo lo que este asociado a este perfil, "Etapas, Actividades"',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#5a6268',
        cancelButtonColor: '#5a6268',
        titleColor:'#5a6268',
        iconColor:'#5a6268'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new URLSearchParams();
            formData.append('perfilid', perfilId);
            formData.append('eventoid', eventoId);
            formData.append('accion', 'eliminar');

            fetch('controllers/perfilEvento.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al enviar la petición ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Info!',
                        text: `Perfil eliminado exitosamente`,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#5a6268',
                        titleColor:'#5a6268',
                        iconColor:'#5a6268'
                    });
                    const row = document.querySelector(`tr[data-perfilid="${perfilId}"]`);
                    if (row) {
                        row.remove();
                    }
                    const tableBody = document.getElementById('perfilTableBody');
                    const filasPerfiles = tableBody.querySelectorAll('tr[data-perfilid]');
                    const btnNext = document.getElementById('btn-next');
                    if (filasPerfiles.length === 0) {
                        const emptyRow = `<tr><td colspan="4" style="text-align: center;">Este evento no tiene perfiles asignados.</td></tr>`;
                        tableBody.insertAdjacentHTML('beforeend', emptyRow);
                        btnNext.style.display = 'none';
                    }
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error eliminando el perfil: ' + data.message,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#5a6268',
                        titleColor:'#5a6268',
                        iconColor:'#5a6268'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Error: ' + error.message,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#5a6268',
                    titleColor:'#5a6268',
                    iconColor:'#5a6268'
                });
            });
        } else {
            Swal.fire({
                title: 'Eliminación cancelada',
                text: '',
                icon: 'info',
                confirmButtonColor: '#5a6268',
                titleColor:'#5a6268',
                iconColor:'#5a6268'
            });
        }
    });
}




// Función para activar/desactivar el campo de puntos de bono según el estado del checkbox
function toggleBono(perfilId) {

    const bonoCheckbox = document.getElementById(`checkBono${perfilId}`);
    const valor =bonoCheckbox.checked ? 1 : 0;
    editarPerfil(perfilId,valor);
}
// el evento del botón "configuracion de etapas por perfil" 
const botonSiguiente = document.getElementById('btn-next');
botonSiguiente.addEventListener('click', () => { 
    const Eventoid =  document.getElementById('selectEventoId').value;
    const nuevaURL = 'categoria.php?eventoId=' + Eventoid;
        window.location.href = nuevaURL; 
});


 function filterEvents() {
    var filter = document.getElementById("demo-foo-filter-status").value;
    var rows = document.querySelectorAll(".evento-row");

    rows.forEach(function(row) {
        if (filter === "" || row.getAttribute("data-estadoid") === filter) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

//evento change el select de eventos en los perfiles 
const selectEventoid = document.getElementById('selectEventoId');
    selectEventoid.addEventListener('change', function() {
        const selectedEventId = this.value;
            const formData = new FormData();
            formData.append('selectedEventId', selectedEventId);
            formData.append('accion', 'perfilevento');
        
            fetch('controllers/perfilEvento.php', {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            })
            .then(response => {
                if (!response.ok) throw new Error('Error al enviar la petición ' + response.statusText);
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    listarPerfiles(data);
                }
                listarPerfiles(data);
            })
            .catch(error => console.error('Error al enviar la petición:', error));
        
        })

        
        
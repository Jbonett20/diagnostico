/**
 * 
 * @author : jorge bonett   jbn19860220@gmail.com
 * 
 */
document.addEventListener('DOMContentLoaded', () => {
    initEventListeners();
    cerrarModal();
});

function initEventListeners() {
    document.querySelectorAll('form[name="category-form"]').forEach(form => {
        form.addEventListener('submit', categorySubmit);
    });
    // Asignar el evento click a todos los botones con el id btn-save-actividad
    document.querySelectorAll('[id^="btn-save-actividad"]').forEach(button => {
        button.addEventListener('click', function() {
            const formId = this.getAttribute('data-form-id');
            activitySubmit(formId);
        });
    });
     // Asignar el evento click a todos los boton de carga masiva
     document.querySelectorAll('[id^="btn_cargar_participantes"]').forEach(button => {
        button.addEventListener('click', function() {
            const formId = this.getAttribute('data-form-id');
            cargaMasivaParticipantes(formId);
        });
    });
        // Asignar el evento click a todos los botones con el id btn-save-question
        document.querySelectorAll('[id^="btn-save-question"]').forEach(button => {
            button.addEventListener('click', function() {
                const formId = this.getAttribute('data-form-id');
                crearPreguntas(formId);
            });
        });
      
    document.querySelectorAll('.accordion-button').forEach(button => {
        button.addEventListener('click', accordionClick);
    });
    
}


function categorySubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const perfilid = formData.get('perfilid');

    const nombreCategoria = formData.get('nombreCategoria').trim();
    const porcentaje = formData.get('porcentaje').trim();
    const porcentajeValue = parseInt(porcentaje);
    const porcentajeMessageElement = form.querySelector('.porcentaje-message');

    if (!nombreCategoria || !porcentaje) {
        Swal.fire({
            title: 'Info!',
            text: `Por favor ingrese el nombre de la categoría y el porcentaje`,
            icon: 'info',
            confirmButtonText: 'OK',
            confirmButtonColor: '#5a6268',
            iconColor: '#5a6268',
            titleColor:'#5a6268'
        });
        return;
    }

    if (isNaN(porcentajeValue) || !Number.isInteger(porcentajeValue) || porcentajeValue <= 0 || porcentajeValue > 100) {
        porcentajeMessageElement.textContent = 'El porcentaje debe ser un número entero mayor que 0 y menor que 100.';
        return;
    } else {
        porcentajeMessageElement.textContent = '';
    }
    
    formData.append('accion', 'crear');

    fetch('controllers/categoria.php', {
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
            Swal.fire({
                title: 'Info!',
                text: `Etapa creada exitosamente`,
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                iconColor: '#5a6268',
                titleColor:'#5a6268'
            });

            form.reset();
            listarCategoria(data, perfilid);
        } else if (data.datos === 'existe') {
            Swal.fire({
                title: 'Info!',
                text: `El nombre de la etapa ya existe. Por favor, elija otro nombre.`,
                icon: 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                iconColor: '#5a6268',
                titleColor:'#5a6268'
            });
        } else if (!data.success && typeof data.datos === 'number') {
            Swal.fire({
                title: 'Info!',
                text: `El porcentaje total no puede superar el 100%. su porcentaje restante es ${data.datos}%.`,
                icon: 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                iconColor: '#5a6268',
                titleColor:'#5a6268'
            });
        } else {
            Swal.fire({
                title: 'Info!',
                text: `Error creando la etapa`,
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                iconColor: '#5a6268',
                titleColor:'#5a6268'
            });
        }
    })
    .catch(error => console.error('Error:', error));
}




function accordionClick(event) {
    const button = event.target;
    const key = button.closest('form').id.replace('listarCategoria', '');

    listarCategoriaKey(key);

    document.querySelectorAll('.accordion-button').forEach(otherButton => {
        if (otherButton !== button) {
            otherButton.setAttribute('aria-expanded', 'false');
            otherButton.classList.add('collapsed');
            const collapseId = otherButton.getAttribute('data-bs-target');
            document.querySelector(collapseId).classList.remove('show');
        }
    });
     // Selecciona el tab de "Crear etapas" automáticamente
     const perfilId = button.closest('form').querySelector('input[name="perfilid"]').value;
     document.getElementById(`home-tab${perfilId}`).click();
}

function listarCategoria(data, perfilid) {
    const tableBody = document.getElementById(`categoriaTableBody-${perfilid}`);
    if (!tableBody) {
        console.error(`Table body with id categoriaTableBody-${perfilid} not found`);
        return;
    }

    tableBody.innerHTML = data.datos.length > 0 ? data.datos.map(categoria => `
        <tr data-categoriaid="${categoria.categoriaid}">
            <td>${categoria.nombre}</td>
            <td>${categoria.porcentaje}</td>
            <td>${categoria.calificacion.charAt(0).toUpperCase() + categoria.calificacion.slice(1)}</td>         
            <td>
                <button onclick="verCategoria(${categoria.categoriaid}, ${categoria.perfilid},'${categoria.nombre}',${categoria.porcentaje})" class="btn btn-secondary btn-xs btn-min" title="Editar">
                <i class="fas fa-pencil-alt" style="color:#fff"></i> Editar
                </button>
                <button class="btn btn-secondary btn-xs btn-min" onclick="addActividades(${categoria.categoriaid},'${categoria.nombre}', ${perfilid},'${categoria.calificacion}')" title="Crear categoria">
                <i class="fa fa-cog" aria-hidden="true"></i> Categorias
                </button>
                <button onclick="eliminarCategoria(${categoria.categoriaid}, ${categoria.perfilid})" class="btn btn-danger btn-xs btn-min" title="Eliminar">
                <i class="fa fa-trash" aria-hidden="true"></i> Eliminar
                </button>
            </td>
        </tr>
    `).join('') : '<tr><td colspan="3">No se encontraron categorías.</td></tr>';
}

function listarCategoriaKey(key) {
    const form = document.getElementById(`listarCategoria${key}`);
    if (form) {
        const formData = new FormData(form);
        formData.append('accion', 'listarCategorias');

        fetch('controllers/categoria.php', {
            method: 'POST',
            body: formData,
            headers: { 'Accept': 'application/json' }
        })
        .then(response => {
            if (!response.ok) throw new Error('Error al enviar la petición ' + response.statusText);
            return response.json();
        })
        .then(data =>  {
            if (data) {
                document.querySelector('#categoriaid').value = data.datos.categoriaid;
                listarCategoria(data, 
      
            formData.get('perfilid'))}})
        .catch(error => console.error('Error:', error));
    }
} 

//funcion para ver la categoria en la modal de editar
function verCategoria(categoriaid, perfilid, nombre, porcentaje) {
    $('#mod-cat').modal('show');
    document.getElementById('categoria_id').value = categoriaid;
    document.getElementById('perfil_id').value = perfilid;
    document.getElementById('cat_nombre').value = nombre;
    document.getElementById('cat_porcentaje').value = porcentaje;
}

function editarCategoria() {
    const catForm = document.getElementById('form-cat');
    const formData = new FormData(catForm);
    const catPorcentaje = document.getElementById('cat_porcentaje').value;
    const catPorcentajeError = document.getElementById('cat_porcentaje-error');

    if (!Number.isInteger(Number(catPorcentaje)) || catPorcentaje < 1 || catPorcentaje > 100) {
        catPorcentajeError.style.display = 'block';
        catPorcentajeError.textContent = 'Por favor, ingrese un porcentaje entero entre 1 y 100.';
        return;
    } else {
        catPorcentajeError.style.display = 'none';
    }
    formData.append('accion', 'editar');
   const perfil_id= formData.get('perfil_id')
    fetch('controllers/categoria.php', {
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
            Swal.fire({
                title: 'info!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                iconColor: '#5a6268'
            })
            .then(() => {
              document.getElementById('close-modal-button').click(); 
                listarCategoria(data,perfil_id);
            });
           
        } else {
            Swal.fire({
                title: 'Info!',
                text: data.message,
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                iconColor: '#5a6268'
            });
        }
    })
    .catch(error => {
        console.error('Error al enviar la petición:', error);
        Swal.fire({
            title: 'Error!',
            text: error.message,
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#5a6268',
            iconColor: '#5a6268',
            titleColor: '#5a6268'
        });
    });
}


function activitySubmit(formId) {
    const form = document.getElementById(`activity-form${formId}`);

    if (!form) {
        console.error('No se encontró el formulario asociado al evento submit.');
        return;
    }

    const categoriaid = document.querySelector('#categoriaid').value;
    const calificacion = document.getElementById(`calificacion${formId}`).value;
    const valor = document.getElementById(`valor${formId}`).value;
    const valorError = document.getElementById(`valor-error${formId}`);

     if (!Number.isInteger(Number(valor)) || valor < 1 || valor > 100) {
        valorError.style.display = 'block';
        valorError.textContent = 'Por favor, ingrese un valor entero entre 1 y 100.';
        return;
    } else {
        valorError.style.display = 'none';
    }

    const formData = new FormData(form);
    formData.append('calificacion', calificacion);

    if (!formData.get('nombre').trim() || !formData.get('valor').trim()) {
        Swal.fire({
            title: 'Info!',
            text: `Por favor complete todos los campos.`,
            icon: 'info',
            confirmButtonText: 'OK',
            confirmButtonColor: '#5a6268',
            iconColor: '#5a6268',
            titleColor: '#5a6268'
        });
        return;
    }

    formData.append('categoriaid', categoriaid);
    formData.append('accion', 'crear');

    fetch('controllers/actividad.php', {
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
            form.reset();
            Swal.fire({
                title: 'Info!',
                text: `Categoria creada exitosamente`,
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                iconColor: '#5a6268',
                titleColor: '#5a6268'
            });
            listarActividadesCategorias(categoriaid);
        } else if (data.datos === 'existe') {
            Swal.fire({
                title: 'Info!',
                text: `El nombre de la categoria ya existe. Por favor, elija otro nombre.`,
                icon: 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                iconColor: '#5a6268',
                titleColor: '#5a6268'
            });
        } else if (data.resta !== undefined) {
            Swal.fire({
                title: 'Info!',
                text: `El valor sobrepasa el límite de puntos. Solo quedan ${data.resta} puntos disponibles.`,
                icon: 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                iconColor: '#5a6268',
                titleColor: '#5a6268'
            });
        } else {
            Swal.fire({
                title: 'Info!',
                text: data.message,
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                iconColor: '#5a6268',
                titleColor: '#5a6268'
            });
        }
    })
    .catch(error => console.error('Error al enviar la petición:', error));
}

function listarActividadesCategorias(categoriaId) {
    const perfiles = document.querySelectorAll('[name="perfilid"]'); 

    perfiles.forEach(perfil => {
        const perfilid = perfil.value; 
        fetch('controllers/actividad.php', {
            method: 'POST',
            body: new URLSearchParams({ categoriaid: categoriaId, accion: 'listarActividades' }),
            headers: { 'Accept': 'application/json' }
        })
        .then(response => {
            if (!response.ok) throw new Error('Error al enviar la petición ' + response.statusText);
            return response.json();
        })
        .then(data => {
            const selectActividades = document.getElementById(`actividades${perfilid}`);
            selectActividades.innerHTML = '<option value="" selected disabled>Seleccione la categoria</option>';
            if (data.success) {
                if (data.datos && data.datos.length > 0) { 
                    data.datos.forEach(actividad => {
                        if (actividad.nombre != null) {  
                            const option = document.createElement('option');
                            option.value = actividad.actividadid;
                            option.textContent = `${actividad.nombre} (valor: ${actividad.valor} - tipo: ${actividad.calificacion.charAt(0).toUpperCase() + actividad.calificacion.slice(1)})`;
                            selectActividades.appendChild(option);
                        }
                    });
                } else {
                    const option = document.createElement('option');
                    option.textContent = 'No hay categorias disponibles';
                    option.disabled = true;
                    selectActividades.appendChild(option);
                }
                tabladeActividades(perfilid, data.datos); 
            } 
        })
        .catch(error => console.error('Error al enviar la petición:', error));
    });
}

function tabladeActividades(perfilid, actividades) {
    const tableBody = document.getElementById(`actividadTableBody-${perfilid}`);
    tableBody.innerHTML = '';  

    if (actividades.length > 0) {
        actividades.forEach(actividad => {
            const row = document.createElement('tr');
            row.setAttribute('data-actividadid', actividad.actividadid);

            const actividadCell = document.createElement('td');
            actividadCell.textContent = actividad.nombre;
            row.appendChild(actividadCell);

            const valorCell = document.createElement('td');
            valorCell.textContent = actividad.valor;
            row.appendChild(valorCell);

            const tipoCell = document.createElement('td');
            tipoCell.textContent = actividad.calificacion.charAt(0).toUpperCase() +actividad.calificacion.slice(1);
            row.appendChild(tipoCell);

            const preguntasCell = document.createElement('td');
            preguntasCell.textContent = actividad.totalpreguntas || 0;
            row.appendChild(preguntasCell);

            const respuestasCell = document.createElement('td');
            respuestasCell.textContent = actividad.totalrespuestas || 0;
            row.appendChild(respuestasCell);

            const opcionesCell = document.createElement('td');
            opcionesCell.innerHTML = `<button class="btn btn-secondary btn-xs btn-min" onclick="veractividades(${actividad.actividadid})">Ver</button>
                                      <button class="btn btn-danger btn-xs btn-min" onclick="eliminarActividad(${actividad.actividadid},${actividad.categoriaid})">Eliminar</button>`;
            row.appendChild(opcionesCell);

            tableBody.appendChild(row);
        });
    } else {
        const noDataMessageRow = document.createElement('tr');
        const noDataCell = document.createElement('td');
        noDataCell.colSpan = 6;
        noDataCell.textContent = 'No hay categorias disponibles';
        noDataMessageRow.appendChild(noDataCell);
        tableBody.appendChild(noDataMessageRow);
    }

    tableBody.parentElement.classList.add('table', 'table-bordered', 'table-sm', 'custom-table');
}



function addActividades(categoriaid, categoriaNombre, perfilid, calificacion) {
    const actividadTab = document.querySelector(`#actividad-tab${perfilid}`);
    const categoriaTab = document.querySelector(`#home-tab${perfilid}`);
    const actividadContent = document.querySelector(`#actividad${perfilid}`);
    const categoriaContent = document.querySelector(`#categoria${perfilid}`);
    actividadTab.style.display = 'block';
    actividadTab.classList.add('active');
    actividadTab.setAttribute('aria-selected', 'true');

    categoriaTab.classList.remove('active');
    categoriaTab.setAttribute('aria-selected', 'false');
    actividadTab.parentElement.style.display = 'none';
    actividadContent.classList.add('show', 'active');
    categoriaContent.classList.remove('show', 'active');
    document.getElementById(`categoria-nom${perfilid}`).textContent = categoriaNombre;
    const selectActividades = document.getElementById(`calificacion${perfilid}`);
    selectActividades.value = calificacion;
    document.querySelector('#categoriaid').value = categoriaid;

    const selecPreguntas = document.getElementById(`tipo-pregunta${perfilid}`);
    
    if (calificacion === 'calificación') {
        // Si la calificación es "calificación", establece la opción "libre" y elimina las demás opciones
        selecPreguntas.innerHTML = '<option value="libre">Libre</option>';
        selecPreguntas.value = 'libre';
    } else {
        // Restaura las opciones originales si la calificación no es "calificación"
        selecPreguntas.innerHTML = `
            <option value="" selected disabled>Seleccione el tipo de pregunta</option>
            <option value="única respuesta">Única respuesta</option>
            <option value="opción múltiple">Opción múltiple</option>
        `;
        selecPreguntas.value = '';
        //para hacer aparecer la opcion de añadir respuestas cuando es de tipo responder
        const caja_resp= document.getElementById(`caja-respuesta${perfilid}`);
        caja_resp.style.display = 'block';

    }

    listarActividadesCategorias(categoriaid);
}



function hideActividades(perfilid) {
    const actividadTab = document.querySelector(`#actividad-tab${perfilid}`);
    const cargaMasivaTab = document.querySelector(`#nav-carga-masiva-tab${perfilid}`);
    const actividadContent = document.querySelector(`#actividad${perfilid}`);
    const categoriaContent = document.querySelector(`#categoria${perfilid}`);
    const cargaMasivaContent = document.querySelector(`#carga-masiva${perfilid}`);

    // Oculta el tab de "Actividades"
    actividadTab.style.display = 'none';
    actividadTab.classList.remove('active');
    actividadTab.setAttribute('aria-selected', 'false');

    // Oculta el tab de "Cargar participantes"
    cargaMasivaTab.classList.remove('active');
    cargaMasivaTab.setAttribute('aria-selected', 'false');

    // Muestra el contenido del tab de "Categoría"
    categoriaContent.classList.add('show', 'active');
    actividadContent.classList.remove('show', 'active');
    cargaMasivaContent.classList.remove('show', 'active');
     //para hacer desaparecer la opcion de añadir respuestas cuando es de tipo calificacion
     const caja_resp= document.getElementById(`caja-respuesta${perfilid}`);
     caja_resp.style.display = 'none';
     //limpiar el formulario de preguntas
     const formquestion = document.getElementById(`form-question${perfilid}`);
      formquestion.reset();
      const tableBody= document.getElementById(`actividadTableBody-${perfilid}`);
      tableBody.innerHTML = '';  
}
function hideOtherTabsContent(perfilid) {
    // Ocultar contenido de todos los tabs excepto el de carga masiva
    document.querySelectorAll('.tab-pane').forEach(tabPane => {
        if (!tabPane.classList.contains(`carga-masiva${perfilid}`)) {
            tabPane.classList.remove('show', 'active');
        }
    });

    // Desactivar todos los botones de los tabs excepto el de carga masiva
    document.querySelectorAll('.nav-link').forEach(navLink => {
        if (!navLink.classList.contains(`carga-masiva${perfilid}`)) {
            navLink.classList.remove('active');
            navLink.setAttribute('aria-selected', 'false');
        }
    });

    // Activar el botón de carga masiva
    const cargaMasivaTab = document.querySelector(`#nav-carga-masiva-tab${perfilid}`);
    cargaMasivaTab.classList.add('active');
    cargaMasivaTab.setAttribute('aria-selected', 'true');

    // Mostrar el contenido del tab de carga masiva
    const cargaMasivaContent = document.querySelector(`#carga-masiva${perfilid}`);
    cargaMasivaContent.classList.add('show');
    cargaMasivaContent.classList.add('active');

}




function eliminarCategoria(categoriaid, perfilId) {
    Swal.fire({
        icon: 'info',
        text: '¿Está seguro de que desea eliminar esta etapa? ADVERTENCIA: Al hacerlo, eliminará todo lo que esté asociado a esta etapa, incluyendo actividades y demás.',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#5a6268',
        cancelButtonColor: '#5a6268',
        iconColor: '#5a6268',
        titleColor: '#5a6268'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new URLSearchParams({ categoriaid, perfilid: perfilId, accion: 'eliminar' });

            fetch('controllers/categoria.php', {
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
                    Swal.fire({
                        title: 'Info!',
                        text: 'Etapa eliminada con éxito',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#5a6268',
                        iconColor: '#5a6268',
                        titleColor: '#5a6268'
                    });
                    document.querySelector(`tr[data-categoriaid="${categoriaid}"]`)?.remove();
                }else if (data.activa) {
                    Swal.fire({
                        title: 'warning!',
                        text: ` ${data.message}`,
                        icon: 'info',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#5a6268',
                        iconColor: '#5a6268',
                        titleColor: '#5a6268'
                    }) }else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#5a6268',
                        iconColor: '#5a6268',
                        titleColor: '#5a6268'
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
                    iconColor: '#5a6268',
                    titleColor: '#5a6268'
                });
            });
        } else {
            Swal.fire({
                title: 'Eliminación cancelada',
                text: '',
                icon: 'info',
                confirmButtonColor: '#5a6268',
                iconColor: '#5a6268',
                titleColor: '#5a6268'
            });
        }
    });
}



//funcion para crear las preguntas  de las categorias
function crearPreguntas(formId) {
    const form = document.getElementById(`form-question${formId}`);
    if (!form) {
        console.error('No se encontró el formulario asociado a la creación de preguntas.');
        return;
    }

    const tipoCalificacion = document.getElementById(`calificacion${formId}`).value;
    const categoriaid = document.getElementById('categoriaid').value;
    const selectedActivity = document.getElementById(`actividades${formId}`).value;
    const valorPreguntaInput = document.getElementById(`valor-pregunta${formId}`);
    const valorPregunta = valorPreguntaInput.value.trim();
    const tipoPregunta = document.getElementById(`tipo-pregunta${formId}`).value;
    const valorError = document.getElementById(`valor-error-preg${formId}`);
    const items = document.getElementById(`rowCoun${formId}`).value;

    const formData = new FormData(form);
    formData.append('categoriaid', categoriaid);
    formData.append('actividadid', selectedActivity);
    formData.append('tipoCalificacion', tipoCalificacion);
    formData.append('items', items);
    formData.append('accion', 'crearPregunta');

    // Validar campos requeridos básicos
    if (!selectedActivity || !formData.get('pregunta').trim() || !valorPregunta || !formData.get('tipo-pregunta')) {
        Swal.fire({
            title: 'Info!',
            text: 'Por favor complete todos los campos requeridos',
            icon: 'info',
            confirmButtonText: 'OK',
            confirmButtonColor: '#5a6268',
            iconColor: '#5a6268',
            titleColor: '#5a6268'
        });
        return;
    }

    // Validar campos adicionales dependiendo del tipo de calificación
    if (tipoCalificacion !== 'calificación') {
        let rowCount = parseInt(document.getElementById(`rowCoun${formId}`).value.trim());
        // Validar que rowCount no sea vacío o solo espacios
        if (isNaN(rowCount)) {
            Swal.fire({
                title: 'Error!',
                text: 'El número de filas de respuesta no puede estar vacío.',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                iconColor: '#5a6268',
                titleColor: '#5a6268'
            });
            return;
        }

        rowCount = rowCount || 0;  // Permitir que rowCount sea 0 si se valida como tal

        let respuestaValida = false;
        let totalRespuesta = 0;
        let validUniqueAnswer = false;
        const respuestas = [];

        for (let i = 0; i <= rowCount; i++) {
            const respuestaInput = document.getElementById(`np${formId}${i}`);
            const valorRespuestaInput = document.getElementById(`cant${formId}${i}`);

            if (respuestaInput && valorRespuestaInput) {
                const respuestaValue = respuestaInput.value.trim();
                let valorRespuestaValue =valorRespuestaInput.value.trim();

                // Verificar si la respuesta está vacía o solo tiene espacios
                if (respuestaValue === '') {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Las respuestas no pueden estar vacías o llenas solo de espacios.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#5a6268',
                        iconColor: '#5a6268',
                        titleColor: '#5a6268'
                    });
                    return;
                }

                if (respuestaValue) {
                    respuestaValida = true;

                    if (tipoPregunta === 'única respuesta') {
                        // Si es única respuesta, establecer a cero las respuestas diferentes al valor de la pregunta
                        valorRespuestaValue = (valorRespuestaValue === valorPregunta) ? valorRespuestaValue : 0;
                    }

                    formData.append(`np${formId}${i}`, respuestaValue);
                    formData.append(`cant${formId}${i}`, valorRespuestaValue);
                    respuestas.push(valorRespuestaValue);
                    totalRespuesta +=  parseFloat(valorRespuestaValue);
                }
            }
        }

        if (!respuestaValida) {
            Swal.fire({
                title: 'Info!',
                text: 'Por favor complete todos los campos requeridos',
                icon: 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                iconColor: '#5a6268',
                titleColor: '#5a6268'
            });
            return;
        }

        // Validar que haya al menos una respuesta con el valor igual al valor de la pregunta
        if (tipoPregunta === 'única respuesta') {
            validUniqueAnswer = respuestas.includes(valorPregunta);
            if (!validUniqueAnswer) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Debe haber al menos una respuesta con un valor igual al de la pregunta.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#5a6268',
                    iconColor: '#5a6268',
                    titleColor: '#5a6268'
                });
                return;
            }
        }

        // Validar que la suma de los valores de las respuestas sea igual al valor de la pregunta
        if (tipoPregunta === 'opción múltiple' && totalRespuesta != valorPregunta) {
            Swal.fire({
                title: 'Error!',
                text: 'La suma de los valores de las respuestas debe ser igual al valor de la pregunta.',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                iconColor: '#5a6268',
                titleColor: '#5a6268'
            });
            return;
        }
    }

    fetch('controllers/actividad.php', {
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
            form.reset();
            Swal.fire({
                title: 'Info!',
                text: 'Operacion exitosa',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                iconColor: '#5a6268',
                titleColor: '#5a6268'
            });
            listarActividadesCategorias(categoriaid);
        } else if (data.resta !== undefined) {
            Swal.fire({
                title: 'Info!',
                text: `El valor sobrepasa el límite de puntos. Solo quedan ${data.resta} puntos disponibles.`,
                icon: 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                iconColor: '#5a6268',
                titleColor: '#5a6268'
            });
        } else {
            Swal.fire({
                title: 'Info!',
                text: data.message,
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5a6268',
                iconColor: '#5a6268',
                titleColor: '#5a6268'
            });
        }
    })
    .catch(error => console.error('Error al enviar la petición:', error));
}





//funcion para ver las activiades individuales 
function veractividades(actividadid){
const formData = new FormData();
formData.append('actividadid',actividadid);
formData.append('accion','veractividad');


fetch('controllers/actividad.php', {
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
        const activityDetails = document.getElementById('activityDetails');
        activityDetails.innerHTML = generateActivityDetailsHTML(data.datos);
        $('#activityModal').modal('show');
    } else {
        Swal.fire({
            title: 'Info!',
            text: `Error al solicitar la actividad`,
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#5a6268',
            iconColor: '#5a6268',
            titleColor:'#5a6268'
        });
    }
})
.catch(error => console.error('Error al enviar la petición:', error));

}
function generateActivityDetailsHTML(datos) {
    let html = `<table class="table table-bordered">`;
     html += ` <p>ACTIVIDAD - ${datos[0].actividad_nombre}</p> 
                  <p>VALOR - ${datos[0].valor}</p>`
    datos.forEach(cont => {
       
    });
        html += `<thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Pregunta</th>
                    <th>Tipo Pregunta</th>
                    <th>Valor Pregunta</th>
                    <th>Respuestas</th>
                </tr>
            </thead>
            <tbody>`;
    datos.forEach((row,index) => {
        html += `<tr>
                    <td>${index+1}</td>
                    <td>${row.actividad_nombre}</td>
                    <td>${row.calificacion.charAt(0).toUpperCase() + row.calificacion.slice(1)}</td>
                    <td>${row.pregunta_nombre  !== null ? row.pregunta_nombre : ''}</td>
                    <td>${row.tipopregunta  !== null ? row.tipopregunta.charAt(0).toUpperCase() + row.tipopregunta.slice(1) : ''}</td>
                    <td>${row.pregunta_valor !== null ? row.pregunta_valor : ''}</td>
                    <td> ${row.totalrespuestas !== null ? row.totalrespuestas : ''} </td>
                 </tr>`;
        
    });
    html += '</tbody></table>';
    return html;
}
// Función para cerrar la modal ver actividadesy limpiar su contenido
function cerrarModal() {
    const modal = document.getElementById('activityModal');
    const activityDetails = document.getElementById('activityDetails');
    activityDetails.innerHTML = '';
    $(modal).modal('hide');
    const closeButton = document.querySelector('#activityModal .modal-header .close');
    const closeFooterButton = document.querySelector('#activityModal .modal-footer .btn-secondary');

    if (closeButton && closeFooterButton) {
        closeButton.addEventListener('click', cerrarModal);
        closeFooterButton.addEventListener('click', cerrarModal);
    }
}
// Función para eliminar una categoria
function eliminarActividad(actividadid,categoriaid) {
    Swal.fire({
        icon:'info',
        text: '¿Está seguro de que desea eliminar esta categoria? Al hacerlo eliminará todo lo asociado a ella, incluyendo preguntas y respuestas.',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#5a6268',
        cancelButtonColor: '#5a6268',
        iconColor: '#5a6268',
        titleColor:'#5a6268'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('actividadid', actividadid);
            formData.append('categoriaid', categoriaid);
            formData.append('accion', 'EliminarActividad');

            fetch('controllers/actividad.php', {
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
                        Swal.fire({
                            title: 'Info!',
                            text: `Categoria eliminada exitosamente`,
                            icon: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#5a6268',
                            iconColor: '#5a6268',
                            titleColor:'#5a6268',
                            iconColor: '#5a6268',
                            titleColor:'#5a6268'
                        });
                     
                    document.querySelector(`tr[data-actividadid="${actividadid}"]`)?.remove();
                    listarActividadesCategorias(categoriaid);
                } else {
                    Swal.fire({
                        title: 'Info!',
                        text: `Error al eliminar la categoria: ${data.message}`,
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#5a6268',
                        iconColor: '#5a6268',
                        titleColor:'#5a6268'
                    });
                }
            })
            .catch(error => console.error('Error al enviar la petición:', error));
        }
    });
}
/**
 * @description: cargar Archivo para los participantes
 * @author : Manuel Romero   maalroba22@gmail.com
 * 
 */
function cargaMasivaParticipantes(formId) {
    const frmCargarParticipantes = document.getElementById(`frm_cargar_participantes${formId}`);
    if (!frmCargarParticipantes) {
        console.error('No se encontró el formulario asociado al evento submit.');
        return;
    }
    const evenId = document.querySelector('#evenId').value;
    if (frmCargarParticipantes) {
        const dataForm = new FormData(frmCargarParticipantes);
        const archivo = document.querySelector("#archivo").files[0];
        dataForm.append("opcn", "cargaPartcicipante");
        dataForm.append("eventoid", evenId);
        dataForm.append("perfilid", formId);
        
        $.ajax({
            type: "POST",
            url: "controllers/participantes.php",
            data: dataForm,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function ({ error, message }) {
                try {
                    if (error) {
                        throw new Error(message);
                    }
                    Swal.fire({
                        title: 'Info!',
                        text: `Participantes cargados exitosamente`,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#5a6268',
                        iconColor: '#5a6268',
                        titleColor:'#5a6268',
                    }).then(() => {
                        document.querySelector("#archivo").value = '';
                        document.getElementById('btn_ver_participantes2').style.display = 'block';

                    });
                    console.log("message: " + message);
                } catch (error) {
                    console.log(error.message);
                    Swal.fire({
                        icon: "info",
                        title: "Oops...",
                        text: message,
                    });
                }
            }
        });
    }
}
//agregar campos para la creacion de las opciones de respuestas
function addNewRow(perfilid) {
    let rowCounter = document.getElementById(`rowCoun${perfilid}`);
    rowCounter.value++;
    const newRow = document.createElement('div');
    newRow.className = 'row mb-3';
    newRow.id = `response-row-${rowCounter.value}`; 
    newRow.innerHTML = `
        <div class="col-md-6 mb-3">
        <label for="tipoetapa" class="form-label">Opción</label>
            <input id="np${perfilid}${rowCounter.value}" name="np${perfilid}${rowCounter.value}" type="text" placeholder="Respuesta" class="form-control">
        </div>
        <div class="col-md-5 mb-3">
        <label for="tipoetapa" class="form-label">Valor</label>
            <input id="cant${perfilid}${rowCounter.value}" name="cant${perfilid}${rowCounter.value}" type="number" placeholder="Valor de la respuesta" class="form-control" required>
        </div>
        <div class="col-md-1 mb-3 d-flex align-items-end justify-content-end">
            <button type="button" class="btn btn-danger btn-remove-row" data-row-id="${rowCounter.value}" style="cursor: pointer;">
                <i class="fa fa-minus-circle"></i>
            </button>
        </div>
    `;

    document.getElementById(`caja-respuesta${perfilid}`).appendChild(newRow);
    newRow.querySelector('.btn-remove-row').addEventListener('click', function() {
        removeRow(newRow.id);
    });
}
function removeRow(rowId) {
    const row = document.getElementById(rowId);
    row.parentNode.removeChild(row);
}
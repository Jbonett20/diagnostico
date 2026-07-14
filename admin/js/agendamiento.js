import Crud from "../helpers/js/Crud.js";
document.addEventListener("DOMContentLoaded", function (e) {
    e.preventDefault();
    getPefil();
    document.body.addEventListener('click', function (event) {
        if (event.target && event.target.id === 'btn-cronometro') {
            verCronometro();
        }
    });

});
function getPefil() {
    // Obtener el eventoid desde la URL
    const urlParams = new URLSearchParams(window.location.search);
    const eventoid = urlParams.get('eventoid');

    let crud = new Crud(`controllers/agendamiento.php?opcn=getperfil&evento_id=${eventoid}`);
    crud.listar(({ datos, eventos }) => {
        // Ordenar datos por total (acumulado + puntoextras) de mayor a menor
        datos.forEach(el => {
            el.participante.sort((a, b) => {
                let totalA = 0;
                let totalB = 0;

                // Calcular total A (acumulado de etapas + puntos extra)
                let puntosExtraA = isNaN(parseFloat(a.puntoextras)) ? 0 : parseFloat(a.puntoextras);
                el.heder.forEach(m => {
                    if (a.etapas[m.categoriaid] && a.etapas[m.categoriaid]['valor']) {
                        totalA += parseFloat(a.etapas[m.categoriaid]['valor']);
                    }
                });
                totalA += puntosExtraA;

                // Calcular total B (acumulado de etapas + puntos extra)
                let puntosExtraB = isNaN(parseFloat(b.puntoextras)) ? 0 : parseFloat(b.puntoextras);
                el.heder.forEach(m => {
                    if (b.etapas[m.categoriaid] && b.etapas[m.categoriaid]['valor']) {
                        totalB += parseFloat(b.etapas[m.categoriaid]['valor']);
                    }
                });
                totalB += puntosExtraB;

                // Ordenar de mayor a menor (totalB - totalA)
                return totalB - totalA;
            });
        });
console.log(datos, 'datosoosos')
        document.body.style.backgroundImage = `url('./assets/imagen/${eventos.img}')`;
        let tabla = ""
        datos.forEach(el => {

            tabla += `<div class="card-box">
            <h4 class="header-title m-0">${el.perfil}</h4>
            <div class="table-responsive">
                <table id="demo-foo-addrow" class="table table-centered table-striped table-bordered mb-0 toggle-circle default footable-loaded footable" data-page-size="7">
                    <thead style="background-color: ${eventos.color} !important">
                        <tr>
                            <th data-sort-ignore="true" class="min-width footable-visible footable-first-column">Nombre</th>
                            <th data-sort-ignore="true" class="min-width footable-visible footable-first-column">Concesionario</th>
                            <th data-sort-initial="true" data-toggle="true" class="footable-visible footable-sortable">Punto Extra<span class="footable-sort-indicator"></span></th>
                            <th`
            el.heder.forEach(element => {
                tabla += ` <th data-sort-initial="true" data-toggle="true" class="footable-visible footable-sortable">${element.etapa}<span class="footable-sort-indicator"></span></th>`
            });

            tabla += ` </th>
                            <th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable">Total<span class="footable-sort-indicator"></span></th>
                         <th data-sort-ignore="true" class="min-width footable-visible footable-first-column">Detalles</th>
                            </tr>
                    </thead>
                    <tbody>`
            el.participante.forEach(x => {
                /* console.log(x.usuarioid) */
                tabla += `<tr class="footable-even" style="">
                                            <td class="text-center footable-visible footable-first-column">${x.participante}</td>
                                            <td class="text-center footable-visible footable-first-column">${x.empresa}</td>
                                            <td class="text-center footable-visible footable-first-column">${x.puntoextras}</td>`
                let acomulado = 0;
                // Validar puntoextra: si no es número, usar 0
                let puntosExtra = isNaN(parseFloat(x.puntoextras)) ? 0 : parseFloat(x.puntoextras);

                x.etapas
                el.heder.forEach(m => {
                    acomulado += parseFloat(x.etapas[m.categoriaid]['valor'])
                    tabla += `<td class="footable-visible"><span class="footable-toggle"></span>${x.etapas[m.categoriaid]['valor']}</td>`
                })
                tabla += `<td class="text-center footable-visible footable-first-column" style="color: white">
                                <span class="badge bg-secondary">
                                    ${acomulado + puntosExtra}
                                </span>
                                 </td>
                            <td class="text-center footable-visible footable-first-column">
                            <!-- Ícono de ver -->
                            <i class="fa fa-eye me-2" aria-hidden="true"
                            data-id="${x.usuarioid}"
                            data-participante="${x.participante}"></i>

                            <!-- Ícono de agregar -->
                             <i class="fa fa-plus" aria-hidden="true"
                            data-id="${x.usuarioid}"
                            data-participante="${x.participante}"
                            data-accion="agregar"></i>
                        </td>

                </tr>`
            });
            tabla += `</tbody>
                </table>
            </div>
        </div> `
            const newtabla = document.querySelector("#conatiner_tabla")
            const nombreevento = document.querySelector("#nombre-evento")
            newtabla.innerHTML = tabla
            // nombreevento.innerHTML = (`<h3 style="background-color: ${eventos.color} !important" class="mt-4 text-white">EVENTO: ${eventos['nombre']}</h3>`) 
            nombreevento.innerHTML = (` 
            <h3>
                <span class="p-1 text-white" style="background-color: ${eventos.color} !important; border-radius: 5px;">
                    EVENTO: ${eventos['nombre']}
                </span>
            </h3>
        `)
        });
    });
}

document.body.addEventListener('click', function (e) {
    if (e.target.classList.contains('fa-eye')) {
        let idUsuario = e.target.getAttribute('data-id');
        let participante = e.target.getAttribute('data-participante');
         
        mostrarDetalles(idUsuario, participante);
    }
    if (e.target.classList.contains('fa-plus')) {
        let idUsuario = e.target.getAttribute('data-id');
        let participante = e.target.getAttribute('data-participante');
       
        mostrarAllUser(idUsuario, participante);
    }
});

function mostrarDetalles(idUsuario, participante) {
    let observacionesPorActividad = {};
    let modal = document.getElementById('detail_user');
    let modalBody = document.getElementById('modal-body');
    modalBody.innerHTML = `
        <div class="modal-content">
            <div class="row mb-3">
                <div class="col-md-4">
                    <p>PARTICIPANTE: ${participante}</p>
                </div>
               <div class="col-md-4">
            <select id="stageSelect" name="stageSelect" class="form-control custom-select">
                <option value="">Escoge la etapa</option>
            </select>
        </div>
        <div class="col-md-4">
            <select id="dynamicSelect" name="dynamicSelect" class="form-control custom-select">
                <option value="">Escoge la categoría</option>
            </select>
        </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <p id="punt_total_cat">PUNTAJE TOTAL OBTENIDO DE LA ETAPA: 0</p>
                </div>
                <div class="col-md-4">
                </div>
                <div class="col-md-4">
                    
                    <p id="punt_total">PUNTAJE TOTAL OBTENIDOS EN LA CATEGORIA: 0</p>
                </div>
            </div>
            <div class="table-container">
                <div class="table-scroll">
                    <table id="dataTable" class="data-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Pregunta</th>
                                <th>Valor</th>
                                <th>Puntaje obtenido</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    `;



    modal.style.display = "block";
    const urlParams = new URLSearchParams(window.location.search);
    const eventoid = urlParams.get('eventoid');
    let crud = new Crud(`controllers/agendamiento.php?opcn=getDetails&usuario_id=${idUsuario}&evento_id=${eventoid}`);
    crud.listar(({ datos, extras }) => {
        const stageSelect = document.getElementById('stageSelect');
        const categorySelect = document.getElementById('dynamicSelect');
        const dataTableBody = document.querySelector('#dataTable tbody');
        let totalCat = 0;

        // Inicializar el select de etapas
        stageSelect.innerHTML = '<option value="">ESCOGE LA ETAPA</option>';
        categorySelect.innerHTML = '<option value="">ESCOGE LA CATEGORIA</option>';

        if (datos.length > 0) {
            datos.forEach(categoria => {
                let stageOption = document.createElement('option');
                stageOption.value = categoria.categoria;
                stageOption.text = categoria.categoria;
                stageSelect.appendChild(stageOption);

                // Calcular el puntaje total para cada etapa
                categoria.actividades.forEach(actividad => {
                    totalCat += parseFloat(actividad.valor) || 0;
                });
            });

        } else {
            let row = document.createElement('tr');
            let cell = document.createElement('td');
            cell.colSpan = 4;
            cell.textContent = 'No hay actividades disponibles';
            row.appendChild(cell);
            dataTableBody.appendChild(row);
        }

    });


    // Añadir listener para el cambio en el select de etapas
    document.getElementById('stageSelect').addEventListener('change', function () {
        const selectedStage = this.value;
        const categorySelect = document.getElementById('dynamicSelect');
        const dataTableBody = document.querySelector('#dataTable tbody');
        const dataTableContainer = document.querySelector('.table-container');

        categorySelect.innerHTML = '<option value="">ESCOGE LA CATEGORIA</option>';

        if (selectedStage) {
            if (selectedStage === '') {
                dataTableBody.innerHTML = '';
                dataTableContainer.style.display = 'none';  // Ocultar la tabla
                return;
            }

            dataTableContainer.style.display = 'block';  // Mostrar la tabla

            // Filtrar las categorías según la etapa seleccionada
            let crud = new Crud(`controllers/userDetalles.php?opcn=getCategoriesByStage&usuario_id=${idUsuario}&evento_id=${eventoid}&stage=${selectedStage}`);
            crud.listar(({ datos }) => {
                if (datos.length > 0) {
                    datos.forEach(actividad => {
                        let option = document.createElement('option');
                        option.value = actividad.actividadid;
                        option.text = `${actividad.actividad}`;
                        categorySelect.appendChild(option);
                        document.getElementById('punt_total_cat').textContent = `PUNTAJE TOTAL OBTENIDO DE LA ETAPA: ${actividad.porcentaje}`;
                        // Almacena observación y archivo por actividad
                        observacionesPorActividad[actividad.actividadid] = {
                            observacion: actividad.observacion,
                            archivo: actividad.archivo
                        };
                    });
                } else {
                    let option = document.createElement('option');
                    option.value = '';
                    option.text = 'No hay categorías disponibles';
                    categorySelect.appendChild(option);
                    document.getElementById('punt_total_cat').textContent = `PUNTAJE TOTAL OBTENIDO DE LA ETAPA: 0`;
                }
            });
        } else {
            // Limpiar el select de categorías si no se selecciona una etapa
            categorySelect.innerHTML = '<option value="">ESCOGE LA CATEGORIA</option>';
            dataTableBody.innerHTML = '';
            dataTableContainer.style.display = 'none';  // Ocultar la tabla
            document.getElementById('punt_total_cat').textContent = `PUNTAJE TOTAL OBTENIDO DE LA ETAPA: 0`;
            let puntaje = document.getElementById('punt_total');
            puntaje.textContent = `PUNTAJE TOTAL OBTENIDOS EN LA CATEGORIA: 0`;
        }
    });

    // Añadir listener para el cambio en el select de categorías
    document.getElementById('dynamicSelect').addEventListener('change', function () {
        const actividadId = this.value;
        let puntaje = document.getElementById('punt_total');

        if (actividadId) {
            let postData = {
                actividad_id: actividadId,
                usuario_id: idUsuario,
                evento_id: eventoid
            };
            let crud = new Crud('controllers/userDetalles.php');

            crud.post(postData, ({ datos }) => {
                const dataTableBody = document.querySelector('#dataTable tbody');
                dataTableBody.innerHTML = '';
                let recordNumber = 1;
                let totalpuntos = 0;

                if (datos.length > 0) {
                    datos.forEach(item => {
                        let row = document.createElement('tr');

                        // Número de registro
                        let numberCell = document.createElement('td');
                        numberCell.textContent = recordNumber++;
                        row.appendChild(numberCell);

                        // Pregunta
                        let questionCell = document.createElement('td');
                        questionCell.textContent = item.pregunta || 'N/A';
                        row.appendChild(questionCell);

                        // Valor
                        let obtCell = document.createElement('td');
                        obtCell.textContent = item.puntos || 'N/A';
                        row.appendChild(obtCell);

                        // Puntaje obtenido
                        let valueCell = document.createElement('td');
                        valueCell.textContent = item.valor || 'N/A';
                        row.appendChild(valueCell);

                        dataTableBody.appendChild(row);
                        totalpuntos += parseFloat(item.valor) || 0;
                    });
                } else {
                    let row = document.createElement('tr');
                    let cell = document.createElement('td');
                    cell.colSpan = 4;
                    cell.textContent = 'No hay datos disponibles para esta categoria';
                    row.appendChild(cell);
                    dataTableBody.appendChild(row);
                }

                puntaje.textContent = `PUNTAJE TOTAL OBTENIDOS EN LA CATEGORIA: ${totalpuntos}`;
                // Mostrar observación y archivos adjuntos dinámicos
                const info = observacionesPorActividad?.[actividadId] || {};
                const obs = info.observacion && info.observacion.trim() !== '' ? info.observacion : 'No hay observación registrada.';
                const files = info.archivo && info.archivo.trim() !== '' ? info.archivo : '';


                const detalleContainer = document.getElementById('detalleExtra');
                if (detalleContainer) detalleContainer.remove(); // Eliminar si ya existe

                const newDetails = document.createElement('div');
                newDetails.id = 'detalleExtra';
                newDetails.className = 'row mt-4';
                newDetails.innerHTML = `
                    <div class="col-md-6">
                        <h5>Observación:</h5>
                        <p>${obs}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>Archivos adjuntos:</h5>
                        <div id="file-links">
                            ${renderFiles(files)}
                        </div>
                    </div>
                `;

                modalBody.appendChild(newDetails);

            });
        } else {
            const dataTableBody = document.querySelector('#dataTable tbody');
            dataTableBody.innerHTML = '';
            puntaje.textContent = `PUNTAJE TOTAL OBTENIDOS EN LA CATEGORIA: 0`;
        }
    });
}
function renderFiles(fileString) {
    if (!fileString) return '<p>No hay archivos.</p>';

    const fileArray = fileString.split(',').map(f => f.trim()).filter(f => f !== '');
    if (fileArray.length === 0) return '<p>No hay archivos.</p>';

    return fileArray.map(file => {
        const extension = file.split('.').pop().toLowerCase();
        const url = `./${file}`; // 🔁 AJUSTA la ruta

        if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(extension)) {
            return `<div class="mb-2"><img src="${url}" alt="Imagen" style="max-width: 100%; height: auto;"></div>`;
        } else if (extension === 'pdf') {
            return `<div class="mb-2"><a href="${url}" target="_blank">📄 Ver PDF</a></div>`;
        } else {
            return `<div class="mb-2"><a href="${url}" target="_blank">📎 Descargar archivo</a></div>`;
        }
    }).join('');
}


function verCronometro() {
    const cronometroContainer = document.querySelector('.cronometro');
    if (cronometroContainer) {
        cronometroContainer.style.display = cronometroContainer.style.display === 'none' ? 'block' : 'none';
    } else {
        console.warn(".cronometro no encontrada en el DOM");
    }
}

document.querySelector('.close-btn').addEventListener('click', function () {
    document.getElementById('detail_user').style.display = "none";
});

window.addEventListener('click', function (event) {
    if (event.target == document.getElementById('detail_user')) {
        document.getElementById('detail_user').style.display = "none";
    }
});

function mostrarAllUser(idUsuario, participante) {
  const modal = document.getElementById('add_user');
  const modalBody = document.getElementById('modal-add-body');
  const urlParams = new URLSearchParams(window.location.search);
  const eventoid = urlParams.get('eventoid');

  // Llamada al backend
  let crud = new Crud(`controllers/agendamiento.php?opcn=getParticipantes&usuario_id=${idUsuario}&evento_id=${eventoid}`);

  crud.listar(({ datos, error, message }) => {
    if (error || !datos || datos.length === 0) {
      modalBody.innerHTML = `<div class="p-3">No hay información disponible para este participante.</div>`;
      modal.style.display = "block";
      return;
    }

    let html = `
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content p-3">
          <div class="modal-header">
            <h4 class="modal-title">PARTICIPANTE: ${participante}</h4>
            <button type="button" class="btn-close" aria-label="Close" id="modalAllUser"></button>
          </div>
          <div class="modal-body">`;

    datos.forEach((categoria, index) => {
      html += `
        <div class="mt-4">
          <h5 class="fw-bold text-primary">Etapa ${index + 1}: ${categoria.categoria}</h5>
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>Actividad</th>
                  <th>Pregunta</th>
                  <th>Valor Obtenido</th>
                  <th>Calificador</th>
                </tr>
              </thead>
              <tbody>`;

      let i = 1;
      categoria.actividades.forEach(actividad => {
        if (!actividad.preguntas || actividad.preguntas.length === 0) {
          html += `<tr>
            <td>${i++}</td>
            <td>${actividad.actividad}</td>
            <td colspan="4">Sin preguntas asignadas</td>
          </tr>`;
          return;
        }

        actividad.preguntas.forEach(pregunta => {
          html += `<tr>
            <td>${i++}</td>
            <td>${actividad.actividad}</td>
            <td>${pregunta.pregunta || 'Sin nombre'}</td>
            <td>${pregunta.valor || 0}</td>
            <td>${pregunta.calificador || 'Sin calificador'}</td>
          </tr>`;
        });
      });

      html += `</tbody></table></div></div>`;
    });

    html += `
          </div>
        </div>
      </div>`;

    modalBody.innerHTML = html;
    modal.style.display = "block";
  });
}





document.body.addEventListener('click', function (e) {
  if (e.target && e.target.id === 'modalAllUser') {
    const modal = document.getElementById('add_user');
    const modalBody = document.getElementById('modal-add-body');
    modal.style.display = "none";
    modalBody.innerHTML = ''; // Opcional: limpiar contenido
  }
});

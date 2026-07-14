document.addEventListener('DOMContentLoaded', () => {
    $('#actividadModal').modal({
        backdrop: 'static',
        keyboard: false
    });

    document.getElementById('perfil').addEventListener('change', function () {
        var selectedValue = this.value;
        llenarParticipantes(selectedValue);
        $('#yourContainerId').empty();
    });
    document.getElementById('participante').addEventListener('change', function () {
        $('#yourContainerId').empty();
    });
    llenarPerfil();
    llenarTitulo();

});

function llenarTitulo() {
    const urlParams = new URLSearchParams(window.location.search);
    const perfilid = urlParams.get('eventoid'); // Captura el valor del parámetro 'eventoid'

    $.ajax({
        url: 'controllers/calificar.php',
        type: 'POST',
        data: { opcn: 'titulo', id: perfilid },
        dataType: 'json',

        success: function (data) {
            console.log('Datos recibidos:', data); // Log para ver la respuesta completa

            // Accede al primer objeto del arreglo
            const fotoUrl = data.foto ? `assets/imagen/${data.img}` : 'assets/imagen/evento.jpg';

            $('.page-title-box').empty().append(`
                <div class="profile-info">
                    <img src="${fotoUrl}" alt="${data.nombre}" class="profile-image">
                    <h4 class="page-title">${data.nombre}</h4>
                </div>
                `);

        },

        error: function () {
            alert('Error al cargar el perfil.');
        }
    });
}



function llenarPerfil() {
    const urlParams = new URLSearchParams(window.location.search);
    const perfilid = urlParams.get('eventoid'); // Captura el valor del parámetro 'perfilid'
    $.ajax({
        url: 'controllers/calificar.php',
        type: 'POST',
        data: { opcn: 'cargar', id: perfilid },
        dataType: 'json',

        success: function (data) {
            // Limpia el select de perfiles actual
            $('#perfil').empty();

            $('#perfil').append(
                $('<option>', {
                    value: '',
                    text: 'Seleccionar',
                    selected: true,
                    disabled: true
                })
            );

            // Agrega las opciones de perfiles al select de perfiles
            data.forEach(function (perfil) {
                $('#perfil').append(
                    $('<option>', {
                        value: perfil.perfilid,
                        text: perfil.nombre
                    })
                );
            });

            // Muestra la modal después de cargar los perfiles si es necesario
        },

        error: function () {
            alert('Error al cargar los perfiles.');
        }
    });
}

function llenarParticipantes(selectedValue) {
    $.ajax({
        url: 'controllers/calificar.php',
        type: 'POST',
        data: { opcn: 'participantes', id: selectedValue },
        dataType: 'json',

        success: function (data) {
            // Limpia el select de perfiles actual
            $('#participante').empty();

            $('#participante').append(
                $('<option>', {
                    value: '',
                    text: 'Seleccionar',
                    selected: true,
                    disabled: true
                })
            );

            // Agrega las opciones de perfiles al select de perfiles
            data.forEach(function (perfil) {
                $('#participante').append(
                    $('<option>', {
                        value: perfil.usuarioid,
                        text: perfil.nombres + ' ' + perfil.apellidos
                    })
                );
            });
            // $('#participante').select2({
            //          placeholder: '--Seleccione--',
            //          allowClear: true
            // });

            // Muestra la modal después de cargar los perfiles si es necesario
        },

        error: function () {
            alert('Error al cargar los perfiles.');
        }
    });
}

function llenarEtapas() {
    var perfilSeleccionado = document.getElementById('perfil').value;
    var participanteSeleccionado = document.getElementById('participante').value;
    // Verificar que ambos campos estén seleccionados antes de continuar
    if (!perfilSeleccionado || !participanteSeleccionado) {
        Swal.fire({
            icon: 'warning',
            title: 'Advertencia',
            text: 'Seleccione perfil y participante para empezar a calificar.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#5a6268',
            titleColor: '#5a6268',
            iconColor: '#5a6268'
        });
        return;
    }

    $.ajax({
        url: 'controllers/calificar.php',
        type: 'POST',
        data: { opcn: 'Etapas', idperfil: perfilSeleccionado, iduser: participanteSeleccionado },
        dataType: 'json',

        success: function (data) {
            // Limpia el contenedor de cualquier contenido previo
            $('#yourContainerId').empty();

            // Contenedor donde se añadirán las cards
            let container = $('<div>', { class: 'container mt-4' });
            let tituloContainer = $('<div>', { id: 'tituloContainer' });

            let pantallaContainer = $(`
                <div id="pantallaContainer" class="mb-3">
                    <div id="horas">00</div>
                    <div id="minutos">:00</div>
                    <div id="segundos">:00</div>
                    <div id="centesimas">:00</div>
                </div>
                `);

            let botonContainer = $(`
                <div id="botonContainer">
                    <button class="btn-cron" id="btn-start" disabled>START</button>
                    <button class="btn-cron" id="btn-reset" disabled>RESET</button>
                </div>
            `);
            let cronometroContainer = $('<div>', { id: 'container' });
            cronometroContainer.append(pantallaContainer, botonContainer);
            let accordion = $('<div>', { class: 'accordion', id: 'accordionExample' });
            container.append(tituloContainer, cronometroContainer, accordion);

            // Itera sobre los datos recibidos para crear las cards
            data.forEach(function (evento, index) {
                var card = $('<div>', { class: 'card' });
                var cardHeader = $('<div>', { class: 'card-header', id: 'heading' + (index + 1) });
                var details = $('<div>', { class: 'details' });
                var nameSpan = $('<span>').text(evento.nombre); // Solo el nombre sin el texto adicional
                var percentageSpan = $('<span>').text('Porcentaje: ' + evento.porcentaje + '%');
                var button = $('<button>', {
                    class: 'btn btn-link',
                    type: 'button',
                    'data-bs-toggle': 'collapse',
                    'data-bs-target': '#collapse' + (index + 1),
                    'aria-expanded': 'true',
                    'aria-controls': 'collapse' + (index + 1)
                }).html('&#9660;');

                details.append(nameSpan).append(percentageSpan);
                cardHeader.append(details).append(button);

                var collapseDiv = $('<div>', {
                    id: 'collapse' + (index + 1),
                    class: 'collapse',
                    'aria-labelledby': 'heading' + (index + 1),
                    'data-bs-parent': '#accordionExample'
                });



                var cardBody = $('<div>', { class: 'card-body' });
                evento.actividades.forEach(function (actividad, actividadIndex) {
                    var actividadContainer = $('<div>', { class: 'actividad-container mb-3', style: 'border: 1px solid #ddd; padding: 10px;' });
                    var actividadDiv = $('<div>', {
                        class: 'actividad-text',
                        text: actividad.nombre + ' - Valor: ' + actividad.valor + '%'
                    });

                    // Determinar el texto del botón basado en el estado
                    var buttonText;
                    var buttonClass;
                    var isDisabled = false;
                    switch (actividad.estado) {
                        case '0':
                            buttonText = 'Responder';
                            buttonClass = 'btn btn-secondary ml-2 color';
                            break;
                        case '1':
                            buttonText = 'En proceso de calificación';
                            buttonClass = 'btn btn-secondary ml-2';
                            isDisabled = true;
                            break;
                        case '2':
                            buttonText = 'Actividad Calificada';
                            buttonClass = 'btn btn-secondary ml-2';
                            isDisabled = true;
                            break;
                    }

                    var responderButton = $('<button>', {
                        id: 'btnActividad_' + actividad.id,
                        class: buttonClass, // Ajustar la clase según el estado
                        text: buttonText,
                        disabled: isDisabled, // Deshabilitar el botón si es necesario
                        click: function () {
                            if (actividad.estado === '0') {
                                // Llamar a la función abrirModalActividad con el ID de la actividad
                                abrirModalActividad(evento.categoriaId, actividad.id, actividad.nombre, actividad.valor);
                            }
                        }
                    });

                    actividadContainer.append(actividadDiv).append(responderButton);
                    cardBody.append(actividadContainer);
                });

                collapseDiv.append(cardBody);
                card.append(cardHeader).append(collapseDiv);
                accordion.append(card);
            });

            container.append(accordion);
            $('#yourContainerId').html(container);
            inicializarCronometro();

        },
        error: function () {
            alert('Error al cargar las etapas.');
        }
    });


}

function inicializarCronometro() {
    let idTemporizador;
    let contador = 0;
    let corriendo = false;
    let tiempoMaximo = 720000;

    let btnStart = document.getElementById('btn-start');
    let btnReset = document.getElementById('btn-reset');
    const pantalla = document.querySelector("#pantallaContainer");
    const botones = document.querySelectorAll(".btn-cron");

    IniciarCronStart(); // auto start opcional

    botones.forEach((boton) => {
        boton.addEventListener("click", () => {
            const botonTexto = boton.textContent.trim();

            if (botonTexto === "RESET") {
                btnReset.setAttribute('disabled', 'true');
                btnStart.removeAttribute('disabled');
                detener();
                contador = 0;
                actualizar();
            } else if (botonTexto === "START") {
                btnStart.setAttribute('disabled', 'true');
                btnReset.removeAttribute('disabled');

                if (!corriendo) {
                    corriendo = true;
                    idTemporizador = setInterval(() => {
                        contador++;
                        if (contador >= tiempoMaximo) {
                            detener();
                            Swal.fire({
                                icon: 'info',
                                title: 'Tiempo límite alcanzado',
                                text: 'El cronómetro se ha detenido automáticamente después de 2 horas.',
                                confirmButtonColor: '#5a6268'
                            });
                        }
                        actualizar();
                    }, 10);
                }
            }
        });
    });

    function detener() {
        corriendo = false;
        clearInterval(idTemporizador);
    }

    function actualizar() {
        const horas = Math.floor(contador / 360000);
        const minutos = Math.floor((contador % 360000) / 6000);
        const segundos = Math.floor((contador % 6000) / 100);
        const centesimas = contador % 100;

        pantalla.innerHTML = `
            <div id="horas">${formatear(horas)}</div>
            <div id="minutos">:${formatear(minutos)}</div>
            <div id="segundos">:${formatear(segundos)}</div>
            <div id="centesimas">:${formatear(centesimas)}</div>
        `;
    }

    function formatear(numero) {
        return numero.toString().padStart(2, "0");
    }

    function IniciarCronStart() {
        btnStart.setAttribute('disabled', 'true');
        btnReset.removeAttribute('disabled');
        corriendo = true;
        idTemporizador = setInterval(() => {
            contador++;
            if (contador >= tiempoMaximo) {
                detener();
                Swal.fire({
                    icon: 'info',
                    title: 'Tiempo límite alcanzado',
                    text: 'El cronómetro se ha detenido automáticamente después de 2 horas.',
                    confirmButtonColor: '#5a6268'
                });
            }
            actualizar();
        }, 10);
    }
}


// Función para abrir la modal y cargar contenido basado en la actividad seleccionada
function abrirModalActividad(eventoId, actividadId, actividadNombre, actividadValor) {
    Swal.fire({
        title: '¿Está seguro de empezar la calificacion de esta actividad? no podra cerrar hasta finalizar',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, Empezar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#5a6268',
        titleColor: '#5a6268',
        iconColor: '#5a6268'
    }).then((result) => {
        if (result.isConfirmed) {
            var participanteSeleccionado = $('#participante').val();

            // Establece el título de la modal
            $('#modalTitle').text(actividadNombre);


            $('#modalForm').html('');
            $('#actividadId').val(actividadId);

            // Llena de preguntas
            $.ajax({
                url: 'controllers/calificar.php',
                type: 'POST',
                data: { opcn: 'preguntas', id: actividadId, participante: participanteSeleccionado },
                dataType: 'json',
                success: function (data) {
                    var formContent = '';

                    data.forEach(function (pregunta) {
                        formContent += `
                    <div class="form-group row mt-3">
                        <label class="col-sm-8 col-form-label">${pregunta.nombre} (${pregunta.valor} puntos)</label>
                        <div class="col-sm-4">
                            <select class="form-control form-control-sm" name="respuesta_${pregunta.preguntaid}">
                `;

                        for (var i = 0; i <= pregunta.valor; i += 0.5) {
                            formContent += `<option value="${i}">${i}</option>`;
                        }

                        formContent += `
                            </select>
                        </div>
                        <input type="hidden" name="pregunta_id[]" value="${pregunta.preguntaid}">
                    </div>
                `;
                    });

                    formContent += `
                    <hr>
                     <div class="mb-3">
                            <label for="notaUser" class="form-label fw-semibold">Observación</label>
                            <textarea class="form-control" id="notaUser" rows="3" placeholder="Escribe una observación..." style="resize: vertical; max-height: 300px;"></textarea>
                    </div>

                    <div class="mb-3">
                            <label for="fileUpload" class="form-label fw-semibold">Subir archivo (opcional)</label>
                            <input class="form-control" type="file" id="fileUpload" accept="image/*,application/pdf">
                    </div>

                <div class="text-center">
                    <button type="button" class="btn btn-primary mt-3" onclick="enviarRespuestas(${eventoId})">Calificar</button>
                </div>
                   `;

                    $('#modalForm').html(formContent);
                },
                error: function () {
                    alert('Error al cargar las preguntas.');
                }
            });
            // Abre la modal
            $('#actividadModal').modal('show');
        }
    });


}

function enviarRespuestas(categoriaide) {
    console.log(categoriaide);
    Swal.fire({
        title: '¿Está seguro de enviar estos puntajes? no se podrá editar',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, enviar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#5a6268',
        titleColor: '#5a6268',
        iconColor: '#5a6268'
    }).then((result) => {
        if (result.isConfirmed) {
            const urlParams = new URLSearchParams(window.location.search);
            const perfilid = urlParams.get('eventoid');
            let perfilSeleccionado = $('#perfil').val();
            let participanteSeleccionado = $('#participante').val();
            var actividadId = $('#actividadId').val();
            let observacion = $('#notaUser').val();
            let archivoInput = document.getElementById('fileUpload');
            let archivo = archivoInput.files[0]; // Archivo seleccionado

            var respuestasPreguntas = [];
            $('#modalForm .form-group').each(function () {
                var preguntaId = $(this).find('input[type="hidden"]').val();
                var respuestaValor = $(this).find('select').val();

                respuestasPreguntas.push({
                    id: preguntaId,
                    valor: respuestaValor
                });
            });

            // Usar FormData para enviar archivos y otros datos
            var formData = new FormData();
            formData.append('opcn', 'carga_pregunta');
            formData.append('actividad', actividadId);
            formData.append('perfil', perfilSeleccionado);
            formData.append('participante', participanteSeleccionado);
            formData.append('evento', perfilid);
            formData.append('categoriaide', categoriaide);
            formData.append('observacion', observacion);
            formData.append('archivo', archivo);

            // Incluir el array de respuestas como JSON
            formData.append('respuestasPreguntas', JSON.stringify(respuestasPreguntas));

            $.ajax({
                url: 'controllers/calificar.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (data) {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: 'Respuestas guardadas correctamente.',
                            confirmButtonColor: '#5a6268',
                            titleColor: '#5a6268',
                            iconColor: '#5a6268'
                        });
                        $('#actividadModal').modal('hide');
                        let botonActualizar = $(`button[id='btnActividad_${actividadId}']`);
                        if (botonActualizar.length) {
                            botonActualizar.text('Actividad Calificada');
                            botonActualizar.prop('disabled', true);
                            botonActualizar.removeClass('color'); 
                        }
                        // llenarEtapas()
                    } else {
                        alert(data.message || 'Ocurrió un error al guardar.');
                    }
                },
                error: function () {
                    alert('Algo salió mal al enviar el formulario.');
                }
            });
        }
    });
}


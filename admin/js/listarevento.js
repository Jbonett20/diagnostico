document.addEventListener('DOMContentLoaded', function () {
    CerrarModalEventos()
});

function eliminarEvento(eventoid, estadoid) {
    Swal.fire({
        icon:'info',
        text: '¿Está seguro de que desea eliminar este evento?',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#5a6268',
        cancelButtonColor: '#5a6268',
        iconColor: '#5a6268'
    }).then((result) => {
        if (result.isConfirmed) {
            let datos = new FormData();
            datos.append('eventoid', eventoid);
            datos.append('estadoid', estadoid);
            datos.append('accion', 'eliminar');

            fetch('controllers/evento.php', {
                method: 'POST',
                body: datos
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
                        text: 'Evento eliminado con éxito',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#5a6268',
                        iconColor: '#5a6268',
                        titleColor:'#5a6268'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Info!',
                        text: 'Error eliminando el evento: ' + data.message,
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#5a6268' ,
                        iconColor: '#5a6268',
                        titleColor:'#5a6268'
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
                    titleColor:'#5a6268'
                });
            });
        }
    });
}

function editarEvento(eventoid) {
    let datos = new FormData();
    datos.append('eventoid', eventoid);
    datos.append('accion', 'consultaevento');
    fetch('controllers/evento.php', {
        method: 'POST',
        body: datos
    }).then(response => {
            if (!response.ok) {
                throw new Error('Error al enviar la petición ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (data.datos[0].perfiles > 0) {
                    const nuevaURL = 'categoria.php?eventoId=' + eventoid;
                    window.location.href = nuevaURL; 
                } else {
                    Swal.fire({
                        title: 'Info!',
                        text: `El evento ${data.datos[0].nombre} no tiene perfiles creados. Por favor, para proseguir con la configuración del evento, ingrese al menú principal en la opción "Eventos" después en la opción "Gestionar eventos" y luego a la opción "Perfiles", seleccione el evento y agregue los perfiles`,
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#5a6268',
                        iconColor: '#5a6268',
                        titleColor:'#5a6268'
                    });
                }
            } else {
                Swal.fire({
                    title: 'Info!',
                    text: `Error al realizar la acción`,
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#5a6268',
                    iconColor: '#5a6268',
                    textColor:'#5a6268'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function ActivarEvento(eventoid){
    let datos = new FormData();
    datos.append('eventoid', eventoid);
    datos.append('accion', 'activarEvento');
    fetch('controllers/evento.php', {
        method: 'POST',
        body: datos
    }).
    then(response => {
        if (!response.ok) {
            throw new Error('Error al enviar la petición ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        const { datos , error, message } = data
        if(error) throw new Error(message);
        $('#modal_activar_etapas').modal('show');
        // console.log(datos)
        
        let html = datos.map( (x,index) => {
          /*   console.log(x) */
            let cabecera =  `<div class="accordion custom-accordion mb-2" id="custom-accordion-${index}">
                                <div class="card mb-0">
                                    <div class="card-header" id="heading${index}">
                                        <h5 class="m-0 position-relative">
                                            <a class="custom-accordion-title text-reset d-block" data-toggle="collapse" href="#collapse${index}" aria-expanded="true" aria-controls="collapse${index}">
                                             ${x.nombre} ${x.bono == '1' ? 'CON BONO' : 'SIN BONO'} <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapse${index}" class="collapse p-1 ${index == 0 ? 'show' : ''}" aria-labelledby="headingFour" data-parent="#custom-accordion-${index}" style="">
                                        <table class="table table-borderless table-hover table-nowrap table-centered m-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Etapa</th>
                                                    <th>Tipo</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;
            let arrayEtapas = x.etapas.split(',');
            let tr = '';
            arrayEtapas.forEach(element => {
                let arrayTd = element.split(':');
                let etapas= arrayTd[0].toLowerCase();
                tr += `<tr>
                            <td>
                                <h5 class="m-0 font-weight-normal">${etapas.charAt(0).toUpperCase() + etapas.slice(1)} (${arrayTd[1]}%) </h5>
                            </td>
                            <td>
                                <h5 class="m-0 font-weight-normal">${arrayTd[2].charAt(0).toUpperCase() + arrayTd[2].slice(1)}</h5>
                            </td>
                            <td>
                                <button type="button" class="btn btn-xs btn-min ${arrayTd[4] == '0'  ? 'btn-secondary btn-xs btn-min' : 'btn-danger'} activarEvento" data-evento="${x.eventoid}" data-perfil="${x.perfilid}" data-etapa="${arrayTd[3]}">${arrayTd[4] == '0'  ? 'Activar' : 'Inactivar'}</button>
                            </td>
                        </tr>`
            });
            return cabecera + tr + `</tbody>
                                        </table>
                                        </div>
                                        </div>
                                    </div>`
        })
        $('#cuerpoModal').html(html);
    })
    .catch(error => {
        Swal.fire({
            title: '¡Oops!',
            text: error.message,
            confirmButtonText: 'OK',
            confirmButtonColor: '#5a6268',
            iconColor: '#5a6268',
            textColor:'#5a6268'
        });
    });
}

$('body').on('click','.activarEvento', function() {
    let idEvento = $(this).data('evento');
    let idPerfil = $(this).data('perfil');
    let idEtapa = $(this).data('etapa');
    let datos = new FormData();
    datos.append('eventoid', idEvento);
    datos.append('perfilid', idPerfil);
    datos.append('etapaid', idEtapa);
    datos.append('accion', 'activarEtapaEventos');
    fetch('controllers/evento.php', {
        method: 'POST',
        body: datos
    }).
    then(response => {
        if (!response.ok) {
            throw new Error('Error al enviar la petición ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        const { datos , error, message } = data
        if(error) throw new Error(message);
        $('#modal_activar_etapas').modal('hide');
        ActivarEvento(idEvento);
        Swal.fire({
            title: 'Bien',
            text: error.message,
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#5a6268',
            iconColor: '#5a6268'
        });
    })
    .catch(error => {
        $('#modal_activar_etapas').modal('hide');
        Swal.fire({
            title: '¡Oops!',
            text: error.message,
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#5a6268',
            iconColor: '#5a6268',
            textColor:'#5a6268'
            
        });
    });
})
// cerrar modal de activar eventos 
function CerrarModalEventos(){
    const closedModal= document.getElementById('btn-closed-evento');
    closedModal.addEventListener('click',()=>{
        $('#modal_activar_etapas').modal('hide');
        
    })
}
function redirigirCalificar(eventoid) {
    window.location.href = 'calificar.php?eventoid=' + eventoid;
}

function verEvento(eventoid) {
    window.location.href = 'agendamiento.php?eventoid=' + eventoid;
}
function copiarLink(eventoid) {
    /* link Produccion */
  var link = 'https://autotrain.com/diagnostico/admin/login_particapante.php?eventoid=' + eventoid; // Asegúrate de cambiar 'https://tu-sitio.com' por tu dominio real.
  
 
    /* link sandbox */
//   var link = 'https://autotrain.com/sandbox_sales_content/admin/login_particapante.php?eventoid=' + eventoid; // Asegúrate de cambiar 'https://tu-sitio.com' por tu dominio real.
 
 
  /* Link_local_jorge */
  // var link = 'http://misproyectos.test/diagnosticosalescontest/admin/login_particapante.php?eventoid=' + eventoid; // Asegúrate de cambiar 'https://tu-sitio.com' por tu dominio real.
    // Crear un elemento de texto temporal
    var tempInput = document.createElement('input');
    tempInput.value = link;
    document.body.appendChild(tempInput);
    
    // Seleccionar el texto del enlace
    tempInput.select();
    tempInput.setSelectionRange(0, 99999); // Para dispositivos móviles
    
    try {
        // Copiar el texto al portapapeles
        document.execCommand('copy');
        
        // Mostrar alerta con SweetAlert
        Swal.fire({
            title: 'Bien',
            text: 'El link de la evaluacion fue copiado con exito',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    } catch (err) {
        console.error('Error al copiar el enlace:', err);
    } finally {
        // Eliminar el elemento de texto temporal
        document.body.removeChild(tempInput);
    }
}
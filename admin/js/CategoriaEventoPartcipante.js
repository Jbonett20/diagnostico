import Crud from "../helpers/js/Crud.js";

class CategoriaEventoPartcipante {
    getAllCategoriesEventoPartcipante(eventoid) {
        console.log(eventoid, "getAllCategoriesEventoPartcipante");
        const crud = new Crud(`controllers/CategoriaEventoParticipante.php?opcn=getcategoria&eventoid=${eventoid}`);
        crud.listar(response => {
            console.log(response,'responseeee')
            if (response.error) {
                console.log(response.message);
                return;
            }
            const { data, evento } = response;
            const nombreEvento = evento.evento;
            let rows = '';
            data.forEach(el => {
                let actividades = '';
                el.actividades.forEach(element => {
                    let linkText = '';
                    let linkClass = '';
                    let linkOnClick = '';

                    if (element.estado === 'realizada') {
                        linkText = 'REALIZADA';
                        linkClass = 'badge badge-secondary';
                        linkOnClick = `confirmStartActivity('${eventoid}', '${element.actividadid}', '${el.categoriaid}','${el.estado}', '${element.estado}')`;
                    } else {
                        linkText = 'INICIAR ACTIVIDAD';
                        linkClass = 'badge badge-secondary';
                        linkOnClick = `confirmStartActivity('${eventoid}', '${element.actividadid}', '${el.categoriaid}','${el.estado}', '${element.estado}')`;
                    }
                    actividades += `
                    <div class="media" style="margin-top: 20px;">
                        <div class="media-body">
                           <div class="row">
                           <div class="col-6">
                           <h5 class="mt-0 mb-1">${element.nombre}</h5>
                           </div>
                              <div class="col-6">
                                 <a href="#" onclick="${linkOnClick}" class="${linkClass}" style="${element.estado === 'realizada' ? 'background-color: #AA2B3E; color: white;' : ''}">${linkText}</a>
                           </div>
                           </div>        
                        </div>
                    </div>`;
                });
                rows += `
                <tr>
                    <td>
                     <div class="row">
                     <div class="col-6">
                     <h5>
                     CATEGORIA: ${el.nombrecategoria}
                     </h5>
                     </div>
                      <div class="col-6">
                        <h5>
                        PORCENTAJE: ${el.porcentaje}%
                        </h5>
                     </div>
                    </div>   
                        <br>
                        ${actividades}
                    </td>
                </tr>`;
            });

            const tablaCartilla = document.querySelector('#tabla_cartila tbody');
            if (tablaCartilla) {
                tablaCartilla.innerHTML = rows;
            }

            const tituloEvento = document.querySelector('#titulo-evento');
            if (tituloEvento) {
                tituloEvento.innerHTML = nombreEvento;
                tituloEvento.style.color = '#AA2B3E';
            }
        });
    }
}

export { CategoriaEventoPartcipante }
window.confirmStartActivity = function(eventoid, actividadid, catgoriaid,est_cat,estado) {
    if (estado == 'realizada') {
        Swal.fire({
            icon: 'warning',
            text: 'La actividad ya fue realizada.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#5a6268',
            iconColor: '#5a6268',
            titleColor: '#5a6268'
        });
    }else if(est_cat==1){
        Swal.fire({
            icon: 'info',
            text: '¿Está seguro de que desea iniciar esta actividad? Una vez iniciada, debe completarla para evitar errores en su calificación.',
            showCancelButton: true,
            confirmButtonText: 'Iniciar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#5a6268',
            cancelButtonColor: '#d33',
            iconColor: '#5a6268',
            titleColor: '#5a6268'
        }).then((result) => {
            if (result.isConfirmed) {
                window.open(`evaluacion?eventoid=${eventoid}&eid=${actividadid}&cid=${catgoriaid}`, '_blank');
            }
        });
    }else{
        Swal.fire({
            icon: 'warning',
            text: 'La etapa aun no se encuentra activa',
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#5a6268',
            iconColor: '#5a6268',
            titleColor: '#5a6268'
        })
    }
    
}

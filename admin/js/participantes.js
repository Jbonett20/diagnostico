import Crud from "../helpers/js/Crud.js";
import opciones from "../helpers/js/lenguajeDataTable.js";
import { SearchParamUrl } from "../helpers/js/SearchParamUrl.js";
import { SweeAlert, SweeAlertConfirm } from "../helpers/js/SweeAlert.js";
document.addEventListener("DOMContentLoaded", function (e) {
    e.preventDefault();
    getAll();
});

const getAll = () => {
    const getSearchUrl = new SearchParamUrl()
    const eventoid = getSearchUrl.buscar('eventoid')
    const perfilid = getSearchUrl.buscar('perfilid')
    const crud = new Crud(`controllers/participantes.php?opcn=getAll&perfilid=${perfilid}&eventoid=${eventoid}`);
    crud.listar(({ data, evento }) => {
        console.log(data);
        console.log(evento.perfil);
        let tabla = ''
        if (data.length > 0) {
            data.forEach((el, index) => {
                let estdoOn = el.estado == "ACTIVO"
                    ? `<span  id="btb-eliminar" data-id="${el.usuarioid}" data-estado="${el.estado}"  class="action-icon"><i class="fas fa-trash-alt" style="color:grey"  data-id="${el.usuarioid}" ></i></span>`
                    : `<span  id="btb-eliminar" data-id="${el.usuarioid}"  data-estado="${el.estado}"  class="action-icon"><i class="fas fa-check-circle" style="color:grey"  data-id="${el.usuarioid}" ></i></span>`
                tabla += `
                    <tr class="evento-row">
                            <td class="text-center">${index + 1}</td>
                            <td class="text-center">${el.identificacion}</td>
                            <td class="text-center">${el.nombres}</td>
                            <td class="text-center">${el.apellidos}</td>
                            <td class="text-center" style="display:none">${el.perfil}</td>
                            <td class="text-center">${el.bono}</td>
                            <td class="text-center">${el.empresa}</td>
                            <td class="text-center"><span class="badge label-table ${el.color}">${el.estado}</span></td>
                            <td class="text-center">
                                    <span  id="btn-listar-editar" data-id="${el.usuarioid}" class="action-icon" data-toggle="modal" data-target="#madal-editar-participantes"><i class="fas fa-pencil-alt" style="color:gray" data-id="${el.usuarioid}"></i></span>
                                        ${estdoOn}
                         </td>
                    </tr>                       
        `
            })
        } else {
            const ocultarparticipante = document.querySelector('#tabla-container')
            if (ocultarparticipante) {
                ocultarparticipante.style.display = 'none'
            }
            const contaiercard = document.querySelector('#container_data')
            if (contaiercard) {
                contaiercard.style.display = "block"
            }
        }
        const tabla_participante = document.querySelector('.tabla-participantes tbody')
        if (tabla_participante) {
            tabla_participante.innerHTML = tabla
        }
        $('.tabla-participantes ').DataTable(opciones);
        const titulo = document.querySelector('#titulo_evento_perfil');
        if (titulo) {
            titulo.innerHTML = `<h5 class="text-uppercase"><i class="mdi mdi-cards-variant mr-1"></i>
            EVENTO - ${evento.evento}</h5>
            <h5 class="mb-3  text-uppercase"><i class="mdi mdi-account-check mr-1"></i>
            PERFIL - ${evento.perfil} </h5>`
        }
    })
}

$("body").on('click', '#btb-eliminar', function (e) {
    e.preventDefault();
    let data = {}
    let id = $(this).data('id');
    let estado = $(this).data('estado');
    data["id"] = id;
    data["opcn"] = "activar";
    let message = estado === "ACTIVO" ? "Inactivar" : "Activar";
    /*  console.log(id);
     console.log(estado); */
    activarParticipante(data, message);
})

const activarParticipante = async (data, message) => {
    try {
        let respuesta = await SweeAlertConfirm(
            `Deseas ${message}`,
            "SI",
            "Cancelar"
        );
        if (respuesta.isConfirmed) {
            let crud = new Crud("./controllers/participantes.php");
            crud.editar(data, function (res) {
                const { error, message } = res;
                SweeAlert("success", "OK", message);
                getAll()
            });
        }
    } catch (e) {
        SweeAlert("error", "Error", e.message);
    }

}

$("body").on('click', '#btn-listar-editar', function (e) {
    try {
        let id = $(this).data('id');
        const crud = new Crud(`controllers/participantes.php?opcn=findById&id=${id}`);
        crud.listar(({ data }) => {
            console.log(data);
            const id = document.querySelector("#txt_id")
            const identificacion = document.querySelector("#txt_identificacion")
            const nombres = document.querySelector("#txt_nombres")
            const apellidos = document.querySelector("#txt_apellidos")
            const bono = document.querySelector("#txt_bono")
            const perfil = document.querySelector("#txt_perfil")
            const empresa = document.querySelector("#txt_empresa")
            id.value = data.usuarioid
            identificacion.value = data.identificacion
            nombres.value = data.nombres
            apellidos.value = data.apellidos
            bono.value = data.bono
            perfil.value = data.perfil
            empresa.value = data.empresa
        })

    } catch (error) {
        SweeAlert("error", "Error", error.message);
    }

})


const frmEditarPparticipantes = document.querySelector("#frm_editar_participantes")
if (frmEditarPparticipantes) {
    frmEditarPparticipantes.addEventListener("submit", (e) => {
        e.preventDefault();
        try {
            const formDta = new FormData(e.target);
            const data = {};
            formDta.forEach((value, name) => {
                data[name] = value;
            });
            data["opcn"] = "editarP"
            const crud = new Crud("controllers/participantes.php")
            crud.editar(data, ({ error, message }) => {
                if (error) {
                    throw new Error(message);
                }
                $("#madal-editar-participantes").modal("hide");
                $("#cerrar_modal").click()
                SweeAlert("success", "OK", message);
                getAll();
            });
        } catch (error) {
        }
    })
}
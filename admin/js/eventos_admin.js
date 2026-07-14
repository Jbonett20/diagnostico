import Crud from "../helpers/js/Crud.js";
 import opciones from "../helpers/js/lenguajeDataTable.js"; 
document.addEventListener("DOMContentLoaded", (e) => {
    e.preventDefault();
    allEventos();
});
const allEventos = () => {
    const crud = new Crud("controllers/evento_admin.php?opcn=get_user");
    crud.listar(({ data }) => {
        console.log(data);
        let colums = data
            .map((el, index) => {
                 
                return `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${el.evento}</td>
                                <td>${el.fechainicio}</td>
                                <td>${el.fechafin}</td>
                                <td>${el.creador}</td>
                                <td>
                                <span class="badge bg-soft-success text-success">ACTIVO</span></td>
                                <td>
                                 <a href="detalle_evento.php?evento=${el.eventoid}" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Detalle"><i class="mdi mdi-eye" data-id="${el.eventoid}" ></i></a> 
                                </td>
                            </tr>
                `
            }).join('');

        const tabla = document.querySelector('#Tabla_all_eventos tbody')
        tabla.innerHTML = colums
        $('#Tabla_all_eventos').DataTable(opciones);
    })
};

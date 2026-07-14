import Crud from "../helpers/js/Crud.js";
import opciones from "../helpers/js/lenguajeDataTable.js";
import { SearchParamUrl } from "../helpers/js/SearchParamUrl.js";

document.addEventListener("DOMContentLoaded", (e) => {
    e.preventDefault();
  //  allEventosDetalle();
});

const allEventosDetalle = () => {
    console.log("desde los eventos detalles");
    const searchParam = new SearchParamUrl()
    const eventoid = searchParam.buscar('evento')
    console.log(eventoid);
    const crud = new Crud(`controllers/evento_admin.php?opcn=evento&evento_id=${eventoid}`);
    crud.listar(({ data }) => {
        console.log(data);

    })
};

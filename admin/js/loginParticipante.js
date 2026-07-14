import Crud from "../helpers/js/Crud.js";
import { SearchParamUrl } from "../helpers/js/SearchParamUrl.js";
import { CategoriaEventoPartcipante } from "./CategoriaEventoPartcipante.js";

document.addEventListener("DOMContentLoaded", function () {
    categrias();
});

const categrias = (e) => {
    const getSearchUrl = new SearchParamUrl()
    const params =  getSearchUrl.buscar('eventoid')
   const categoria = new CategoriaEventoPartcipante(); 
   categoria.getAllCategoriesEventoPartcipante(params);
}


/* login Participantes */

const btnLoginParticipantes = document.querySelector('#btn-login-participantes');
if (btnLoginParticipantes) {
    let frmLoginParticipantes = document.querySelector('#frm-login-participante');
    let notificionloginParticipantes = document.querySelector('#notification-login-participante')
    btnLoginParticipantes.addEventListener('click', async (e) => {
        e.preventDefault();
        notificionloginParticipantes.innerHTML = '';
        /* alert('hola desde loginParticipantes') */


        try {
            const getSearchUrl = new SearchParamUrl()
            const eventoid =  getSearchUrl.buscar('eventoid')

           
            let formData = new FormData(frmLoginParticipantes);
            formData.append("operation", "loginParticipantes");
            formData.append("eventoid", eventoid)

            let datos = {};
            for (const [clave, valor] of formData.entries()) {
                datos[clave] = valor;
            }
            let login = new Crud("controllers/login.php");
            const { error, message, otp } = await new Promise((resolve) => {
                login.ingresar(datos, (res) => {
                    resolve(res);
                });
            });

            if (error) {
                throw new Error(message);
            }
            setTimeout(() => {
                notificionloginParticipantes.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                Ingresando...
            </div>`;
                window.location.href = `cartilla_participante?eventoid=${eventoid}`;
            }, 1000);
        } catch (error) {
            notificionloginParticipantes.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            ${error}
        </div>`;
        }
    });
}
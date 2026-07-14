import Crud from "../helpers/js/Crud.js";

let frmLoginButton = document.getElementById("frmLoginButton");
if (frmLoginButton) {
  let frmLogin = document.getElementById("frmLogin");
  let notificationLogin = document.getElementById("notification-login");
  frmLoginButton.addEventListener("click", async function (e) {
    e.preventDefault();
    notificationLogin.innerHTML = "";
    try {
      let formData = new FormData(frmLogin);
      formData.append("operation", "ingresar");
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
      /* if (otp) {
        let formGroupOtp = document.getElementById("form-group-otp");
        formGroupOtp.classList.add("d-block");
        formGroupOtp.classList.remove("d-none");

        notificationLogin.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            Se ha enviado un correo electrónico a ${datos.email} con el código OTP.
        </div>`;
      } */
        setTimeout(() => {
          notificationLogin.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                Ingresando...
            </div>`;
          window.location.href = "index";
        }, 1000);
    } catch (error) {
      notificationLogin.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            ${error}
        </div>`;
    }
  });
}


/* login Participantes */

const btnLoginParticipantes = document.querySelector('#btn-login-participantes');
if(btnLoginParticipantes) {
  let frmLoginParticipantes = document.querySelector('#frm-login-participante');
  let notificionloginParticipantes = document.querySelector('#notification-login-participante')
  btnLoginParticipantes.addEventListener('click', async (e) =>{
    e.preventDefault();
    notificionloginParticipantes.innerHTML = '';
    /* alert('hola desde loginParticipantes') */

    try {
      let formData = new FormData(frmLoginParticipantes);
      formData.append("operation", "loginParticipantes");
      formData.append("eventoid", 1)
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
      /* if (otp) {
        let formGroupOtp = document.getElementById("form-group-otp");
        formGroupOtp.classList.add("d-block");
        formGroupOtp.classList.remove("d-none");

        notificationLogin.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            Se ha enviado un correo electrónico a ${datos.email} con el código OTP.
        </div>`;
      } */
        setTimeout(() => {
          notificionloginParticipantes.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                Ingresando...
            </div>`;
          window.location.href = "evaluacion";
        }, 3000);
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
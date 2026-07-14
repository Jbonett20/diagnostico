// import alertaSwal from "../helpers/js/alertaSwal.js";
import Crud from "../helpers/js/Crud.js";
let controladorUrl = "controllers/usuarios.php";
window.addEventListener("DOMContentLoaded", () => {
  ListarEmpresa();
  ListarRoles();
  Listar();
});

const Listar = async () => {
  try {
    let Campana = new Crud(`${controladorUrl}?operation=listar`);

    const { res } = await new Promise((resolve) => {
      Campana.consultar((res) => {
        resolve(res);
      });
    });
    tabla(res);
  } catch (error) {
    console.log("Error", error);
  }
};

const ListarEmpresa = async () => {
  try {
    let Campana = new Crud("controllers/empresa.php?operation=listarSelect");

    const { res, error, message } = await new Promise((resolve) => {
      Campana.consultar((res) => {
        resolve(res);
      });
    });
    if (error) {
      throw new Error(message);
    }
    SelectEmpresa({ res });
  } catch (error) {
    Swal.fire({
        title: 'Error',
        text: error.message,
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#5a6268',
        iconColor: '#5a6268',
        titleColor:'#5a6268'
    });
  }
};
const ListarRoles = async () => {
  try {
    let Campana = new Crud("controllers/rol.php?operation=listarSelect");

    const { res, error, message } = await new Promise((resolve) => {
      Campana.consultar((res) => {
        resolve(res);
      });
    });
    if (error) {
      throw new Error(message);
    }
    SelectRol({ res });
  } catch (error) {
    Swal.fire({
        title: 'Error',
        text: error.message,
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#5a6268',
        iconColor: '#5a6268',
        titleColor:'#5a6268'
    });
  }
};

const tabla = (data) => {
  let tabla = document.querySelector("#tablaDatos tbody");
  let tbody = "";
  if (data.length > 0) {
    tbody = data
      .map((e) => {
        return `<tr>
                <td>${e.nombres}</td>
                <td>${e.apellidos}</td>
                <td>${e.email}</td>
                <td>${e.identificacion}</td>
                <td>${e.telefono}</td>
                <td>${e.rol}</td>
                <td>${e.empresa}</td>
                <td>${`<span class="badge badge badge-pill badge-${e.estadoid == "1" ? "secondary" : "danger"}">${e.estadoid == "1" ? "ACTIVO" : "INACTIVO"}</span>`}</td>
                <td>${`<button data-toggle="modal" data-target="#modalEditar" data-id="${e.usuarioid}" id="editarcliente" type="button" class="btn btn-secondary waves-effect waves-light btn-xs btn-min"><i data-idc="${e.usuarioid}" class="mdi mdi-pencil"></i></button>
                      <button  id="restablecerContrasena" data-id = "${e.usuarioid}" data-cc = "${e.identificacion}"  type="button" class="btn btn-secondary waves-effect waves-light btn-xs btn-min"><i  class="mdi mdi-key"></i></button>
                      <button  id="eliminar" data-id = "${e.usuarioid}" data-estado = "${e.estadoid}" type="button" class="btn btn-danger waves-effect waves-light btn-xs btn-min"><i  class="mdi mdi-close"></i></button>
                `}</td>
            </tr>
            `;
      })
      .join("");
  }
  tabla.innerHTML = tbody;
  let btnEditarCampana = document.querySelectorAll("#btnBuscar");
  if (btnEditarCampana) {
    btnEditarCampana.forEach((element) => {
      tippy(element, {
        content: "Editar",
      });
    });
  }
};

$("body").on("click", "#btnCrear", async function (e) {
  e.preventDefault();

  try {
    let frmCrear = document.getElementById("frmCrear");
    if (!frmCrear.checkValidity()) {
      frmCrear.reportValidity();
      return;
    }
    let formData = new FormData(frmCrear);
    formData.append("operation", "crear");
    let datos = {};
    for (const [clave, valor] of formData.entries()) {
      datos[clave] = valor;
    }
    let Operacion = new Crud(controladorUrl);

    const { error, message } = await new Promise((resolve) => {
        Operacion.crear(datos, (res) => {
        resolve(res);
      });
    });

    if (error) {
      throw new Error(message);
    }

    Listar();
    frmCrear.reset();
    const closeButtons = document.querySelectorAll('.close');
    for (const button of closeButtons) {
        button.click();
    }
    // $("#modalAgregar").modal("hide");
    Swal.fire({
        title: 'Info!',
        text: `Operación exitosa`,
        icon: 'warning',
        confirmButtonText: 'OK',
        confirmButtonColor: '#5a6268',
        iconColor: '#5a6268',
        titleColor:'#5a6268'
    });
  } catch (error) {
    Swal.fire({
        title: 'Error!',
        text: error.message,
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#5a6268',
        iconColor: '#5a6268',
        titleColor:'#5a6268'
    });
    
  }
});

$("body").on("click", "#btnBuscar", async function (e) {
  e.preventDefault();
  try {
    let id = $(this).data("id");

    let Vinculo = new Crud(
      `${controladorUrl}?operation=findById&registroid=${id}`
    );

    const { error, message, res } = await new Promise((resolve) => {
      Vinculo.consultar((res) => {
        resolve(res);
      });
    });

    if (error) {
      throw new Error(message);
    }
    $("#modalEditar").modal("show");
    document.getElementById("nombre_editar").value = res.nombre;
    document.getElementById("empresaid").value = res.empresaid;
  } catch (error) {
    Swal.fire({
        title: 'Error',
        text: error.message,
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#5a6268',
        iconColor: '#5a6268',
        titleColor:'#5a6268'
    });
  }
});

$("body").on("click", "#btnEditar", async function (e) {
  e.preventDefault();

  try {
    let frmEditar = document.getElementById("frmEditar");
    if (!frmEditar.checkValidity()) {
      frmEditar.reportValidity();
      return;
    }
    let formData = new FormData(frmEditar);
    let datos = {};
    for (const [clave, valor] of formData.entries()) {
      datos[clave] = valor;
    }
    let Editar = new Crud('controllers/usuarios.php?operation=editar');

    const { error, message } = await new Promise((resolve) => {
      Editar.editar(datos, (res) => {
        resolve(res);
      });
    });

    if (error) {
      throw new Error(message);
    }

    Listar();
    frmEditar.reset();
    $("#modalEditar").modal("hide");
    Swal.fire({
        title: 'Info!',
        text: `Operación exitosa`,
        icon: 'warning',
        confirmButtonText: 'OK',
        confirmButtonColor: '#5a6268',
        iconColor: '#5a6268',
        titleColor:'#5a6268'
    });
  } catch (error) {
    Swal.fire({
        title: 'Info!',
        text: error.message,
        icon: 'warning',
        confirmButtonText: 'OK',
        confirmButtonColor: '#5a6268',
        iconColor: '#5a6268',
        titleColor:'#5a6268'
    });
  }
});

const SelectEmpresa = ({ res }) => {
  let option = '<option value=""></option>';
  let dataOptions = res

  if (dataOptions.length > 0) {
    option += dataOptions
      .map((e) => {
        return `<option value="${e.empresaid}">${e.nombres.charAt(0).toUpperCase() + e.nombres.slice(1)}</option>`;
      })
      .join("");
  }

  document.getElementById("empresaid").innerHTML = option;
  document.getElementById("empresaidEdit").innerHTML = option;
//   $("#empresaid, #empresaidEdit").select2({
//     placeholder: "Seleccione una opción",
//   });
};

const SelectRol = ({ res }) => {
  let option = '<option value=""></option>';
  option = res.map((e) => {
        return `<option value="${e.rolid}">${e.nombre.charAt(0).toUpperCase() + e.nombre.slice(1)}</option>`;
      })
      .join("");

  document.getElementById("rol").innerHTML = option;
  document.getElementById("rolEdit").innerHTML = option;
//   $("#rol, #rolEdit").select2({
//     placeholder: "Seleccione una opción",
//   });
};
$("body").on("click", "#editarcliente", function (e) {
  try {
    // $('#modalEditar').modal('show');
    let idUsuario = $(this).data("id");
    const crud = new Crud(`controllers/usuarios.php?operation=getByid&id=${idUsuario}`);
    crud.listar(function (data) {
      const { res } = data;
      document.getElementById("apellidosEdit").value = res.apellidos;
      document.getElementById("correoEdit").value = res.email;
      document.getElementById("identificacionEdit").value = res.identificacion;
      document.getElementById("nombresEdit").value = res.nombres;
      document.getElementById("telefonoEdit").value = res.telefono;
      document.getElementById("usuarioidEdit").value = res.usuarioid;

      document.getElementById("rolEdit").value = res.rolid;
      document.getElementById("empresaidEdit").value = res.empresaid;
      /* pasar datos la selec2 tipo identificacion */
      // const rolEdit = $("#rolEdit");
      $("#rolEdit").val(res.rolid).trigger("change");
      $("#empresaidEdit").val(res.empresaid).trigger("change");

      /* pasar datos la selec2 ciudad */
      const ciudad = $("#ciudadEdit");
      ciudad.val(data.ciudadid).trigger("change");

    });
  } catch (error) {
    Swal.fire({
        title: 'Info!',
        text: error.message,
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#5a6268',
        iconColor: '#5a6268',
        titleColor:'#5a6268'
    });
  }
});

$("body").on("click", "#restablecerContrasena", async function() {
Swal.fire({
    title: "Restablecer contraseña",
    text: "Esta seguro de restablecer la contraseña?",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      let crud = new Crud("./controllers/usuarios.php?operation=restablecer");
      let id = $(this).data('id')
      let identificacion = $(this).data('cc')
      let data = { cc: identificacion, id: id}
      crud.editar(data, function (res) {
        const { error, message } = res;
        Swal.fire({
            title: 'Ok!',
            text: message,
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#5a6268',
            iconColor: '#5a6268',
            titleColor:'#5a6268'
        });
      });
    }
  });
})

$("body").on("click", "#eliminar", async function() {
  let id = $(this).data('id')
  let estadoid = $(this).data('estado')
  Swal.fire({
    title: estadoid == 1 ? "INACTIVAR" : "ACTIVAR",
    text: "Esta seguro?",
    icon: "warning",
    buttons: true,
    dangerMode: true,
    confirmButtonColor: '#5a6268',
    iconColor: '#5a6268',
    titleColor:'#5a6268'
  })
  .then((willDelete) => {
    if (willDelete) {
      let crud = new Crud("./controllers/usuarios.php?operation=inactivar");
      let data = { estadoid: estadoid, id: id}
      crud.editar(data, function (res) {
        const { error, message } = res;
        Swal.fire({
            title: 'Ok!',
            text: message,
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#5a6268',
            iconColor: '#5a6268',
            titleColor:'#5a6268'
        });
        Listar();
      });
    }
  });
})
const alertaSwal = (message, titulo = null, icono = null) => {
    swal({
      title: titulo || "Error",
      text: message || "Registro no encontrado",
      icon:  icono || "error",
      button: "Cerrar",
    });
  };

  export default alertaSwal
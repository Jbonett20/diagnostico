class Crud {
  constructor(baseUrl) {
    this.endpoint = baseUrl;
  }

  crear(data, callback) {
    this._ajaxRequest("POST", data, callback);
  }

  listar(callback) {
    this._ajaxRequest("GET", null, callback);
  }

  consultar(callback) {
    this._ajaxRequest("GET", null, callback);
  }

  editar(data, callback) {
    this._ajaxRequest("PUT", data, callback);
  }

  eliminar(id, callback) {
    this._ajaxRequest("DELETE", { id }, callback);
  }

  ingresar(data, callback) {
    this._ajaxRequest("POST", data, callback);
  }

  post(data, callback, file = false) {
    this._ajaxRequest("POST", data, callback, file);
  }

  _hasValidSession() {
    let isValid = false;

    const xhr = new XMLHttpRequest();
    xhr.open("GET", "validar_sesion.php", false); // Usamos el modo síncrono (false) para bloquear la ejecución hasta obtener la respuesta
    xhr.send();

    if (xhr.readyState === 4 && xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.status === "ok") {
        isValid = true;
      } else {
        isValid = false;
      }
    }

    return isValid;
  }

  _ajaxRequest(method, data, callback, file = false) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, this.endpoint);
    if (!file) {
      xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    }
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        callback(JSON.parse(xhr.responseText));
      }
    };
    (file) ? xhr.send(data) : xhr.send(JSON.stringify(data))
  }

}

export default Crud;

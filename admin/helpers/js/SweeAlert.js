const SweeAlert = (icon = "error", title, text) => {
  Swal.fire({
    icon,
    title,
    text,
    confirmButtonColor: '#5a6268',
    iconColor: '#5a6268',
    titleColor: '#5a6268',
    iconColor: '#5a6268',
    titleColor: '#5a6268'
  });
};

const SweeAlertConfirm = (title, confirmButtonText, denyButtonText) => {
  return new Promise((resolve) => {
    Swal.fire({
      title,
      icon: "info",
      showDenyButton: true,
      showCancelButton: false,
      confirmButtonText,
      denyButtonText,
      confirmButtonColor: '#5a6268',
      iconColor: '#5a6268',
      titleColor: '#5a6268',
      iconColor: '#5a6268',
      titleColor: '#5a6268'
    }).then((result) => {
      resolve(result);
    });
  });
};

export { SweeAlert, SweeAlertConfirm };

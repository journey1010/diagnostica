import { Toast } from './Toast.js';

$(document).ready(select2);
function select2() {
  $(".select2").select2({
    closeOnSelect: true,
  });
}

$(document).on('submit', '#registrarArchivo', function (event) {
  event.preventDefault();
  if (
    $("#archivoEvadiag").prop("files").length !== 0 ||
    $("#fechaSubida").val() !== ''
  ) {
    let formData = new FormData();
    formData.append('archivo', $("#archivoEvadiag").prop("files")[0]);
    formData.append('fecha', $("#fechaSubida").val());


    let progressBar = $('.progress-bar');
    let progressText = $('.progress-bar').text();
    $.ajax({
      url: '/administrador/registrarevadiag',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      xhr: function () {
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener('progress', function (evt) {
          if (evt.lengthComputable) {
            let percentComplete = evt.loaded / evt.total * 100;
            progressBar.css('width', percentComplete + '%');
            progressBar.text(percentComplete.toFixed(2) + '%');
          }
        }, false);
        return xhr;
      },
      beforeSend: function () {
        progressBar.css('width', '0%');
        progressBar.text('0%');
      },
      success: function (response) {
        let resp = JSON.parse(response);
        let indice = Object.keys(resp)[0];
        switch (indice) {
          case "error":
            Toast.fire({
              background: "#E75E15",
              iconColor: "#000000",
              icon: "error",
              title: resp[indice],
            });
            break;
          case "success":
            Toast.fire({
              icon: "success",
              title: resp[indice],
            });
            break;
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        Toast.fire({
          icon: "error",
          title: `Ha ocurrido un error en la solicitud! Código: ${jqXHR.status}, Estado: ${textStatus}, Error: ${errorThrown}`,
          background: "#ff0000",
        });
      },
      complete: function () {
        progressBar.css('width', '100%');
        progressBar.text('100%');
      }
    });
  } else {
    Toast.fire({
      icon: "warning",
      title: 'Por favor, ingrese todos los datos del formulario.',
    });
  }
});
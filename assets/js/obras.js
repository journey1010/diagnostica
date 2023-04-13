import { Toast } from './Toast.js';


$(document).ready(select2);
function select2() {
  $(".select2").select2({
    closeOnSelect: true,
  });
}

$(document).on('submit', '#registrarObras', function (event) {
  event.preventDefault();
  if (
    $("#tituloObra").val() !== '' ||
    $(".select2").val() !== '' ||
    $("#archivoObra").prop("files").length !== 0 ||
    $("#fechaObra").val() !== '' ||
    $("#descripcionObra").val() !== ''
  ) {
    let formData = new FormData();
    formData.append('titulo', $("#tituloObra").val());
    formData.append('tipo', $(".select2").val());
    formData.append('archivo', $("#archivoObra").prop("files")[0]);
    formData.append('fecha', $("#fechaObra").val());
    formData.append('descripcion', $("#descripcionObra").val());

    let progressBar = $('.progress-bar');
    let progressText = $('.progress-bar').text();
    $.ajax({
      url: '/administrador/registrarobra',
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

$(document).on('click', '#limpiarFiltro', limpiarFiltro);
function limpiarFiltro() {
  document.getElementById("orderBy").selectedIndex = 0;
  document.getElementById("fechaObraActualizar").value = "";
  document.getElementById("tipoObraActualizar").selectedIndex = 0;
  $('#orderBy').select2('destroy');
  $('#tipoObraActualizar').select2('destroy');
  $(".select2").select2({
    closeOnSelect: true,
  });
}

$(document).on('click', '#aplicarFiltro', searchObra);
$(document).on('click', '#buscarPalabra', searchObra);
$(document).on('keydown', '#palabraClave', function(e){
  if (e.keyCode === 13) {
    searchObra();
    return false;
  }
});
function searchObra() {
  if (
    $('#tipoObraActualizar option:selected').val() == '' &&
    $('#fechaObraActualizar').val() == '' &&
    $('#palabraClave').val() == ''
  ) {
    Toast.fire({
      icon: "warning",
      title: 'Por favor, ingrese una palabra clave, una fecha o un tipo para iniciar una búsqueda.',
    });
  } else {
    let formData = {
      tipo: $('#tipoObraActualizar option:selected').val(),
      fecha: $('#fechaObraActualizar').val(),
      ordenar: $('#orderBy option:selected').val(),
      palabra: $('#palabraClave').val()
    }

    $.ajax({
      url: '/administrador/buscarobra',
      method: 'POST',
      data: formData,
      beforeSend: function () {
        $('#spinner').show();
      },
      success: function (data) {
        try {
          let jsonData = JSON.parse(data);
          Toast.fire({
            icon: "error",
            title: jsonData.error,
          });
        } catch (e) {
          $('#respuestaBusqueda').html(data);
        }
        $('#spinner').hide();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        $('#spinner').hide();
        Toast.fire({
          icon: "error",
          title: `Ha ocurrido un error en la solicitud! Código: ${jqXHR.status}, Estado: ${textStatus}, Error: ${errorThrown}`,
          background: "#ff0000",
        });
      }
    });
  }
}

$(document).on('click', '.edit-icon', function () {
  let row = $(this).closest("tr");
  let id = row.find("td:eq(0)").text();
  let titulo = row.find("td:eq(1)").text();
  let descripcion = row.find("td:eq(2)").text();
  let tipo = row.find("td:eq(3)").text();
  let fecha = row.find("td:eq(4)").text();

  row.find(".edit-icon").hide();
  row.find(".cancel-icon").show();

  Swal.fire({
    width: 'auto',
    heightAuto: false,
    html: formToUpdate(id, titulo, descripcion, tipo, fecha),
    showCancelButton: true,
    confirmButtonText: 'Guardar',
    cancelButtonText: 'Cancelar',
    preConfirm: () => {
      row.find(".cancel-icon").show();
      actualizarObra();
    }, 
    willClose: function() {
      row.find(".cancel-icon").hide();
      row.find(".edit-icon").show();
    }
  });
});

//Formulario de actualizacion
function formToUpdate(id,titulo,descripcion, tipo, fecha) {
  let htmlContent = `
    <style>
      .custom-select {
        width: 100%;
      }
      .custom-select option {
        font-size: 16px; 
      }
      .custom-select.hovered:hover option:hover {
        background-color: blue;
      }
    </style>
    <div class="card card-success mt-0 mx-auto w-100">
    <div class="card-header">
        <h3 class="card-title">Editar proyecto de inversión pública</h3>
    </div>
    <form id="actualizarObra" enctype="multipart/form-data">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label for="titulo">Título*</label>
                    <input type="text" class="form-control" id="idObraUpdate" value="${id}" style="display: none;">
                    <input type="text" class="form-control" id="tituloObra" value="${titulo}">
                </div>
                <div class="col-md-6">
                    <label for="tipoObraUpdate">Tipo *</label>
                    <select id="tipoObraUpdate" class="custom-select" data-placeholder="Selecciona un tipo de proyecto de inversión" style="width: 100%; height: calc(2.25rem + 2px);" tabindex="-1" aria-hidden="true">
                      <option value="Adicionales de obra" ${tipo === "" ? "selected" : ""}>Selecciona un tipo de proyecto</option>
                      <option value="Adicionales de obra" ${tipo === "Adicionales de obra" ? "selected" : ""}>Adicionales de obra</option>
                      <option value="Liquidacíon de obras" ${tipo === "Liquidacíon de obras" ? "selected" : ""}>Liquidacíon de obras</option>
                      <option value="Supervisión de contrataciones" ${tipo === "Supervisión de contrataciones" ? "selected" : ""}>Supervisión de contrataciones</option>
                      <option value="Historico" ${tipo === "Historico" ? "selected" : ""}>Historico</option>
                      <option value="Información Adicional" ${tipo === "Información Adicional" ? "selected" : ""}>Información Adicional</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="archivoObraUpdate">Seleccione un archivo *</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="archivoObraUpdate" onchange="
                            if (this.files.length > 0) {
                                document.querySelector('.custom-file-label').innerHTML = this.files[0].name
                            } else {
                                    document.querySelector('.custom-file-label').innerHTML = 'Seleccione un archivo'
                            }
                        ">
                        <label class="custom-file-label" for="archivoObraUpdate" data-browse="${titulo}">${titulo}</label>
                  </div>
                </div>
                <div class="col-md-6">
                    <label for="HoraIngreso">Fecha *</label>
                    <input type="date" class="form-control" id="fechaObraUpdate" value="${fecha}">
                </div>
                <div class="col-md-12">
                    <label for="descripcion">Descripción *</label>
                    <textarea type="text" class="form-control text-content" id="descripcionObraUpdate" placeholder="Por favor ingrese una descripción..." style="min-height: 100px; max-width: 100%">${descripcion}</textarea>
                    <div id="contadorPalabras" style="color: red;"></div>
                </div>
            </div>
        </div>
        <div class="card-footer mt-3">
            <div class="progress">
                <div class="progress-bar active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; border-radius: 10px;">
                    0%
                </div>
            </div>
        </div>
  </form>
  <script>
    document.querySelector('#tipoObraUpdate').addEventListener('mouseenter', function() {
      this.classList.add('hovered');
    });

    document.querySelector('#tipoObraUpdate').addEventListener('mouseleave', function() {
      this.classList.remove('hovered');
    });
  </script>
  `;
  return htmlContent;
}

function actualizarObra () {
  let formData  = new FormData();
  formData.append('id', $('#idObraUpdate').val());
  formData.append('titulo', $('#tituloObra').val());
  formData.append('tipo', $("#tipoObraUpdate").val());
  formData.append('archivo', $("#archivoObraUpdate").prop("files")[0]);
  formData.append('fecha', $("#fechaObraUpdate").val());
  formData.append('descripcion', $("#descripcionObraUpdate").val());

  let progressBar = $('.progress-bar');
  let progressText = $('.progress-bar').text();
  $.ajax({
    url: '/administrador/actualizarobra',
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
      });
    },
    complete: function () {
      progressBar.css('width', '100%');
      progressBar.text('100%');
    }
  });
}
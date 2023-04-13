import { Toast } from './Toast.js';

$(document).ready(select2);
function select2() {
  $(".select2").select2({
    closeOnSelect: true,
  });
}

//registrar oficina
$(document).on("submit", "#registrarOficina", function (e) {
  e.preventDefault();
  if ($('input[name="nombreOfi"]').val() !== '' && $('input[name="sigla"]').val() !== '') {
    let formData = {
      jerarquia: $('#tipoOrgano').val(),
      nombre: $('#nombreOfi').val(),
      sigla: $('#sigla').val()
    };
    $.ajax({
      url: '/administrador/registraroficina',
      method: 'POST',
      data: formData,
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
      }
    });

  } else {
    Toast.fire({
      background: "#E8EC14",
      icon: "warning",
      iconColor: "#000000",
      title: "Debe llenar todos los campos obligatorios",
    });
  }
});

//funcionalidad de iconos de la tabla
$(document).on("click", ".edit-icon", edit);
function edit() {
  let row = $(this).closest("tr");
  row.find("td[contenteditable=false]").prop("contenteditable", true);
  row.find(".edit-icon").hide();
  row.find(".cancel-icon").show();
  row.find(".save-icon").show();
}

$(document).on("click", ".cancel-icon", cancel);
function cancel() {
  let row = $(this).closest("tr");
  row.find("td[contenteditable=true]").prop("contenteditable", false);
  row.find(".edit-icon").show();
  row.find(".cancel-icon").hide();
  row.find(".save-icon").hide();
}

$(document).on("click", ".save-icon", save);
function save() {
  let row = $(this).closest("tr");
  row.find("td[contenteditable=true]").prop("contenteditable", false);
  row.find(".edit-icon").show();
  row.find(".cancel-icon").hide();
  row.find(".save-icon").hide();
  
  let formData = {
    id: row.find("td:eq(0)").text(),
    nombre: row.find("td:eq(1)").text(),
    jerarquia : row.find("td:eq(2)").text(),
    sigla: row.find("td:eq(3)").text()
  };

  $.ajax({
    url: "/administrador/actualizaroficina",
    method: "POST",
    data: formData,
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
  });
}
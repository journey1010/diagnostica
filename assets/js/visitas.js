import { Toast } from "./Toast.js";

$(document).ready(select2);
function select2() {
  $(".select2").select2({
    closeOnSelect: true,
  });
}
//buscar dni visita
$(document).on("click", "#BuscarDNIVisita", buscarDNIVisita);
function buscarDNIVisita(e) {
  e.preventDefault();
  let dni = $("#dniVisita").val();
  if (dni == "") {
    Toast.fire({
      background: "#86FFD3",
      icon: "info",
      title: "El campo DNI no puede estar vacío",
    });
  } else {
    $.ajax({
      url: "https://dniruc.apisperu.com/api/v1/dni/" + dni + "?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imdpbm9fcGFyZWRlc0BvdXRsb29rLmNvbS5wZSJ9.1rXghi0JQb2I-COt_4J7juPDkIgCBZZbHcixnwGF0mI",
      method: "GET",
      beforeSend: function () {
        $("#BuscarDNIVisita").html("Buscando ...");
      },
      success: function (data) {
        $("#BuscarDNIVisita").html("Buscar");
        if (data.success == false) {
          Toast.fire({
            icon: "error",
            title:
              "Ha ocurrido un error en la solicitud! En este momento no se puede Consultar a la API.",
          });
        } else {
          $("#apellidos_nombres").val(
            data.nombres +
            " " +
            data.apellidoPaterno +
            " " +
            data.apellidoMaterno
          );
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        $("#BuscarDNIVisita").html("Buscar");
        Toast.fire({
          icon: "error",
          title: `Ha ocurrido un error en la solicitud! Código: ${jqXHR.status}, Estado: ${textStatus}, Error: ${errorThrown}`,
        });
      },
    });
  }
}

$(document).on("submit", "#registrarVisitas", function (event) {
  event.preventDefault();
  if (
    $("#dniVisita").val() === "" ||
    $("#apellidos_nombres").val() === "" ||
    $(".select2 option:selected").text() === "" ||
    $("#hora_de_ingreso").val() === ""
  ) {
    Toast.fire({
      background: "#E8EC14",
      icon: "warning",
      iconColor: "#000000",
      title: "Debe llenar todos los campos obligatorios",
    });
  } else {
    let formData = {
      dniVisita: $("#dniVisita").val(),
      apellidosNombres: $("#apellidos_nombres").val(),
      oficina: $(".select2 option:selected").val(),
      personaAVisitar: $("#persona_a_visitar").val(),
      horaDeIngreso: $("#hora_de_ingreso").val(),
      quienAutoriza: $("#quien_autoriza").val(),
      motivo: $("#motivo").val(),
    };

    $.ajax({
      url: "/administrador/resgistravisitas",
      method: "POST",
      data: formData,
      beforeSend: function () {
        $("#btn btn-primary").html("Guardando...");
      },
      success: function (response) {
        $("#btn btn-primary").html("Guardar");
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
            $("#dniVisita").val("");
            $("#apellidos_nombres").val("");
            $(".select2").val(null).trigger("change"); // restablecer el select
            $("#persona_a_visitar").val("");
            $("#quien_autoriza").val("");
            $("#motivo").val("");
            Toast.fire({
              icon: "success",
              title: resp[indice],
            });
            break;
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        $("#dniVisita").val("");
        $("#apellidos_nombres").val("");
        $(".select2").val(null).trigger("change"); // restablecer el select
        $("#persona_a_visitar").val("");
        $("#quien_autoriza").val("");
        $("#motivo").val("");
        Toast.fire({
          icon: "error",
          title: `Ha ocurrido un error en la solicitud! Código: ${jqXHR.status}, Estado: ${textStatus}, Error: ${errorThrown}`,
          background: "#ff0000",
        });
      }
    });
  }
});

//Actualizar visitas
//funcionalidad de iconos de la tabla
$(document).on("click", ".edit-icon", edit);
function edit() {
  let row = $(this).closest("tr");
  row.find("td[contenteditable=false]").prop("contenteditable", true);
  row.find(".edit-icon").hide();
  row.find(".cancel-icon").show();
  row.find(".save-icon").show();

  let hora = moment().zone('America/Phoenix').format('YYYY-MM-DD HH:mm:ss');
  let horaSalida = row.find("td:eq(3)");
  horaSalida.text(hora);

}

$(document).on("click", ".cancel-icon", cancel);
function cancel() {
  let row = $(this).closest("tr");
  row.find("td[contenteditable=true]").prop("contenteditable", false);
  row.find(".edit-icon").show();
  row.find(".cancel-icon").hide();
  row.find(".save-icon").hide();
  let horaSalida = row.find("td:eq(3)");
  horaSalida.text("");
}

$(document).on("click", ".save-icon", save);
function save() {
  let row = $(this).closest("tr");
  let formData = {
    id: row.find("td:eq(0)").text(),
    horaSalida: row.find("td:eq(3)").text(),
    motivo: row.find("td:eq(4)").text(),
  };

  $.ajax({
    url: "/administrador/actualizarvisitas",
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

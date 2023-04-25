import { Toast } from './Toast.js';

$(document).ready(select2);
function select2() {
  $(".select2").select2({
    closeOnSelect: true,
  });
}

$(document).on('submit', '#matematicaform', function(e) {
  e.preventDefault();

  let detener = false;
  $('tr').each(function(){
    var suma = 0;
    $(this).find('input[type="text"]').each(function(){
      var valor = parseInt($(this).val());
      if (!isNaN(valor)) {
        suma += valor;
      }
    });

    if (suma !== 100) {
      Toast.fire({
        icon: "warning",
        title: "Los valores de la fila "+$(this).index() + " son : " + suma+". Esta suma no debe ser inferior ni mayor a 100"
      });
      detener = true;
      return false;
    }
  });

  if (detener) {
    return false;
  }

  let datos = [];
  let inputs = $(this).find("input");
  inputs.each(function(index) {
    datos.push($(this).val());
  });

  $.ajax({
    method: 'POST',
    url: '/administrador/director/registrar-matematica',
    data: {datos: datos},
    success: function(response) {
      let resp = JSON.parse(response)
      if(resp.status == 'success') {
        Toast.fire({
          icon: "success",
          title: resp.message,
        });
      } else {
        Toast.fire({
          icon: "warning",
          title: resp.message,
        });
      }
    }
  });
});

$(document).on('submit', '#lenguajeform', function(e) {
  e.preventDefault();

  let detener = false;
  $('tr').each(function(){
    var suma = 0;
    $(this).find('input[type="text"]').each(function(){
      var valor = parseInt($(this).val());
      if (!isNaN(valor)) {
        suma += valor;
      }
    });

    if (suma !== 100) {
      Toast.fire({
        icon: "warning",
        title: "Los valores de la fila "+$(this).index() + " son : " + suma+". Esta suma no debe ser inferior ni mayor a 100"
      });
      detener = true;
      return false;
    }
  });

  if (detener) {
    return false;
  }

  let datos = [];
  let inputs = $(this).find("input");
  inputs.each(function(index) {
    datos.push($(this).val());
  });

  $.ajax({
    method: 'POST',
    url: '/administrador/director/registrar-lenguaje',
    data: {datos: datos},
    success: function(response) {
      let resp = JSON.parse(response)
      if(resp.status == 'success') {
        Toast.fire({
          icon: "success",
          title: resp.message,
        });
      } else {
        Toast.fire({
          icon: "warning",
          title: resp.message,
        });
      }
    }
  });
});
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
  let datos = [];
  let inputs = $(this).find("input");
  inputs.each(function(index) {
    datos.push($(this).val());
  });

  let suma = 0;
  let fila = 1;
  for (let i = 0; i < datos.length; i += 4) {
    suma +=parseFloat(datos[i]) +parseFloat(datos[i+1]) +parseFloat(datos[i+2]) + parseFloat(datos[i+3]);
    if (suma !== 100) {
      Toast.fire({
        icon: "warning",
        title: "La suma del valor de los logros en la fila "+(fila)+" es incorrecto. ",
      });
      detener = true;
      return false;
    }
    fila++;
    suma = 0;
  }

  if (detener) {
    return false;
  }


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
  let datos = [];
  let inputs = $(this).find("input");
  inputs.each(function(index) {
    datos.push($(this).val());
  });

  let suma = 0;
  let fila = 1;
  for (let i = 0; i < datos.length; i += 4) {
    suma +=parseFloat(datos[i]) +parseFloat(datos[i+1]) +parseFloat(datos[i+2]) + parseFloat(datos[i+3]);
    if (suma !== 100) {
      Toast.fire({
        icon: "warning",
        title: "La suma del valor de los logros en la fila "+(fila)+" es incorrecto. ",
      });
      detener = true;
      return false;
    }
    fila++;
    suma = 0;
  }

  if (detener) {
    return false;
  }

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
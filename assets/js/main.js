$(document).ready(function () {
  //cambiar clase active del sidebar menu
  let linkActive = document.querySelectorAll('.nav-link');
  linkActive.forEach(function(item) {
    item.addEventListener('click', function() {
      // Elimina la clase "active" de todos los elementos de la lista
      linkActive.forEach(function(item) {
        item.classList.remove('active');
      });
      // Agrega la clase "active" solo al elemento seleccionado
      this.classList.add('active');
    });
  });
  //Cambiar logo user
  let change_logo_user = document.getElementById('change_logo_user');
  change_logo_user.addEventListener('click', changeLogo, false);

  function changeLogo()
  {
    Swal.fire({
      title: 'Ingrese su nuevo avatar',
      html: `
            <form id="upload-form" enctype="multipart/form-data">
              <input type="hidden" name="username" value="${username}" id="username">
              <div class="custom-file">
                  <input type="file" class="custom-file-input"  id="file" name="file">
                  <label class="custom-file-label" for="file" data-browser="Elegir">Seleccione un archivo</label>
              </div>
            </form>
          `,
      showCancelButton: true,
      confirmButtonText: 'Cambiar logo',
      showLoaderOnConfirm: true,
      preConfirm: () => {
        let form = document.getElementById('upload-form');
        let formData = new FormData(form);
        let fileInput = document.getElementById('file');
        if (!fileInput.value) {
          Swal.showValidationMessage('Debe seleccionar un archivo');
          return false;
        }
        return $.ajax({
          method: "POST",
          url: '/changelogo',
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            return JSON.parse(response);
          },
          error: function (response) {
            Swal.showValidationMessage(`Request failed: ${response.statusText}`);
          }
        });
      },
      allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
      if (result.isConfirmed) {
        if (result.value.extension === false) {
          Swal.fire({
            title: 'Error',
            text: `La extensión del archivo no es válida`,
            icon: 'error'
          })
        } else if (result.value.exito === true) {
          Swal.fire({
            title: 'Avatar actualizado',
            text: `El avatar ha sido actualizado correctamente para ${username}`,
            icon: 'success'
          })
        } else if (result.value.fallo) {
          Swal.fire({
            title: 'Error',
            text: `${result.value.fallo}`,
            icon: 'error'
          })
        }else if (result.value.error) {
          Swal.fire({
            title: 'Error',
            text: `${result.value.error}`,
            icon: 'error'
          })
        }
      }
    });
  }

  //cambiar contraseñas
  let change_password = document.getElementById('change_password_user');
  change_password.addEventListener('click', changePassword, false);

  function changePassword()
  {
    Swal.fire({
      title: 'Ingresa tu nueva contraseña',
      input: 'password',
      inputLabel: 'Contraseña ',
      inputPlaceholder: 'Nueva contraseña',
      inputAttributes: {
        maxlength: 10,
        autocapitalize: 'off',
        autocorrect: 'off'
      },
      preConfirm: function (password) {
        if (!password) {
          Swal.showValidationMessage('Debe ingresar una contraseña');
          return false;
        }

        let formData = new FormData();
        formData.append('username',username);
        formData.append('password', password);
        
        return $.ajax({
          url: "/changepassword",
          method: "POST",
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            let jsonResponse = JSON.parse(response);
            if (jsonResponse.respuesta === true) {
              Swal.fire({
                title: 'Contraseña actualizada',
                text: `La contraseña ha sido actualizada exitosamente para el usuario ${$('#username').val()}`,
                icon: 'success'
              });
            } else if (jsonResponse.respuesta === false) {
              Swal.fire({
                title: 'Error',
                text: 'Ha ocurrido un fallo al cambiar la contraseña',
                icon: 'error'
              });
            } else {
              Swal.fire({
                title: 'Error',
                text: 'Ha fallado el envío de datos. Vuelva a intentar más tarde.',
                icon: 'error'
              });
            }
          },
          error: function (response) {
            Swal.fire({
              title: 'Error',
              text: `Request failed: ${response.statusText}`,
              icon: 'error'
            });
          }
        });
      },
      allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
      if (result.isConfirmed) {
        if (result.value.respuesta == false ) {
          Swal.fire({
            title: 'Error',
            text: `Ha ocurrido un fallo al cambiar la contraseña`,
            icon: 'error'
          })
        } else if (result.value.respuesta == true ) {
          Swal.fire({
            title: 'Contraseña actualizada',
            text: 'La contraseña ha sido actualizada exitosamente para el usuario : '+username,
            icon: 'success'
          })
        }else if (result.value.respuesta == 'Error de datos'){
          Swal.fire({
            title: 'Error',
            text: 'Ha fallado el envío de datos. Vuelva a intentar más tarde.',
            icon: 'error'
          })
        }
      }
    });
  }
    
  //cerrar sesión 
  let cerrar_sesion = document.getElementById('cerrar_sesion');
  cerrar_sesion.addEventListener('click', cerrarSesion, false);
  function cerrarSesion()
  {
    Swal.fire({
      title: '¿Estás seguro?',
      text: '¿Quieres cerrar sesión?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, cerrar sesión',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      // Si el usuario confirma que quiere cerrar sesión
      if (result.isConfirmed) {
        // Llama a la función signOut() usando AJAX
        $.ajax({
          method: "POST",
          url: "/signout",
          success: function() {
            // Si la función se ejecutó correctamente, redirige al usuario a la página de inicio de sesión
            window.location.href = '/administrador';
          }
        });
      }
    });
  }

  //carga dinamica de las opciones del sidebar

  $('a[name="sidebarEnlace').click(function(e) {
    e.preventDefault();
    var page = $(this).data('page');
    var progressBar = $('.progress-bar');
    var progressBarContainer = $('.progress-bar-container');

    $.ajax({
      url: '/administrador/' + page,
      beforeSend: function(){
        progressBar.width('0%');
        progressBarContainer.show();
      },
      success: function(html) {
        $('#contentPage').html(html);
        window.history.pushState(null, null, '/administrador/' + page);
        $('#contentPage').append("<script>$('.select2').select2({closeOnSelect: true });</script>");
      },
      complete: function() {
        progressBarContainer.hide();
      }, 
      xhr: function() {
        var xhr = new XMLHttpRequest();
        xhr.addEventListener('progress', function(event) {
          if (event.lengthComputable) {
            var percentComplete = (event.loaded / event.total) * 100;
            progressBar.width(percentComplete + '%');
          }
        }, false);
        return xhr;
      }
    });
  });
});
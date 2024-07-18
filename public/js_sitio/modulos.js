document.addEventListener('DOMContentLoaded', (event) => {
    const textElement = document.getElementById('typing-text');
    const text = textElement.textContent;
    textElement.textContent = '';
    let i = 0;
    const speed = 170; // Velocidad de escritura (en milisegundos)

    function typeWriter() {
        if (i < text.length) {
            textElement.textContent += text.charAt(i);
            i++;
            setTimeout(typeWriter, speed);
        } else {
            textElement.style.borderRight = 'none'; // Quita el cursor al finalizar
        }
    }

    typeWriter();
});

$(document).ready(function () {
    
    $('.disabled').on('click', function (e) {
        e.preventDefault();


        swal({
            title: "¡Módulo en desarrollo!",
            text: "Lo sentimos el módulo aún no está disponible",
            type: "info", // warning, error, success, info
            buttons: {
                visible: false, // true , false
            },
            timer: 3000,
            showConfirmButton: false
        });


    })
    

      $('.unauthorized').on('click', function (e) {
        e.preventDefault();


        swal({
            title: "¡Módulo no Autorizado!",
            text: "Lo sentimos, no tiene permiso para acceder a este módulo, si cree que es un error comuníquelo con su superior.",
            type: "error", // warning, error, success, info
            buttons: {
                visible: false, // true , false
            },
            timer: 3300,
            showConfirmButton: false
        });


      })
    
    $(document).ready(function() {
    $('#user-menu-toggle').on('click', function(e) {
        e.preventDefault();
        $('#user-menu').toggleClass('show');
    });
    });
    
    $("#boton_cerrarsesion").click(function()
{
    swal({   
        title: "¿Cerrar sesión?",
        text: "Confirme que desea cerrar sesión",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function(isConfirm){
        if (isConfirm)
        {
            // cerrar msj confirmacion
            swal.close();
            location.href = "/logout";
        }
        else 
        {
            // mensaje
            swal({
                title: "Cancelado",
                text: "Acción cancelada",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 500,
                showConfirmButton: false
            });
        }
    });
    return false;
});


    
});



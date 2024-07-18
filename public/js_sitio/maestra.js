
// Load PAGINA
$(document).ready(function() 
{
    setTimeout(function()
    {
        consultaotificaciones();
    }, 2000);
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




// Consulta notificaciones del sistema
function consultaotificaciones()
{
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/consultanotificaciones",
        data:{},
        cache: false,
        success:function(dato){
            $('#div_notificaciones').html(dato.notificaciones);
            $('#notificaciones_titulo').html('Notificaciones de vigencias ('+dato.notificaciones_total+')');

            if (parseInt(dato.notificaciones_total) > 0)
            {
                $('#notificaciones_activas').html('<span class="heartbit"></span><span class="point"></span>');
            }
            else
            {
                $('#notificaciones_activas').html('');
            }
        },
        error: function(dato){
            // alert('Error: '+dato.msj);
            consultaotificaciones();
            return false;
        }
    });//Fin ajax
}
$(document).ready(function(){
    
    consultarInformacion()

    // Inicializar tooltip
    $('[data-toggle="tooltip"]').tooltip();
    

    $('#boton_nueva_doc').on('click', function (e) {
        e.preventDefault();

        $('#form_subir_informacion').each(function () {
            this.reset();
        });
        
        $('#documento').css('display', 'block');
        $('#enlace').css('display', 'none');
        $('#RUTA_DOCUMENTO').prop('required', true);
        $('#RUTA_LINK').prop('required', false);

        // Abrir modal
        $('#modal_informacion').modal({ backdrop: false });

    })

    $("#boton_guardar_informacion").click(function (e) {
    
        e.preventDefault();

        var valida = this.form.checkValidity();
        if (valida)
        {
            swal({
                title: "¡Confirme que desea guardar!",
                text: "Centro de información",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Guardar!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function (isConfirm) {    
                if (isConfirm) {

                    // cerrar msj confirmacion
                    swal.close();

                    // enviar datos
                    $('#form_subir_informacion').ajaxForm({
                        dataType: 'json',
                        type: 'POST',
                        url: "/biblioteca",
                        data: {},
                        resetForm: false,
                        success: function(data) {
                            
                            consultarInformacion()

                            // mensaje
                            swal({
                                title: "Correcto",
                                text: "Información guardada correctamente",
                                type: "success", // warning, error, success, info
                                buttons: {
                                    visible: false, // true , false
                                },
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // actualiza boton
                            $('#boton_guardar_informacion').html('Guardar <i class="fa fa-save"></i>');

                            // cerrar modal
                            $('#modal_informacion').modal('hide');
                        },
                        beforeSend: function() {
                            $('#boton_guardar_informacion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                        },
                        error: function(data) {
                            
                            // actualiza boton
                            $('#boton_guardar_informacion').html('Guardar <i class="fa fa-save"></i>');

                            // mensaje
                            swal({
                                title: "Error",
                                text: "Error al guardar la información",
                                type: "error", // warning, error, success, info
                                buttons: {
                                    visible: false, // true , false
                                },
                                timer: 1500,
                                showConfirmButton: false
                            });

                            return false;
                        }
                    }).submit();

                    return false;
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
        }
    });



});


function seleccionarInformacion(checkbox) {

    if (checkbox.checked) {
        $('#RUTA_LINK').prop('required', true);
        $('#RUTA_DOCUMENTO').prop('required', false);
        $('#enlace').css('display', 'block');
        $('#documento').css('display', 'none');
        
    } else {
      
        $('#RUTA_DOCUMENTO').prop('required', true);
        $('#RUTA_LINK').prop('required', false);
        $('#documento').css('display', 'block');
        $('#enlace').css('display', 'none');
    }
}

function consultarInformacion() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/obtenerInfoBliblioteca",
        data:{},
        cache: false,
        success:function(dato){
            
            mostraBilioteca(dato);
            $('#divLoader').css('display', 'none');  
            
        },beforeSend: function () {
            $('#divLoader').css({ 'display': 'flex', 'justify-content': 'center' });  

        },
        error: function(dato){
            return false;
        }
    });
    
}

function mostraBilioteca(data) {
    html = '';
   const colors = [
        'rgb(152, 193, 29)',   // Verde
        'rgb(21, 75, 117)',    // Azul Oscuro
        'rgb(0, 152, 199)'     // Azul Claro
    ];


    data.forEach((element, index) => {
        const des = element.DESCRIPCION || '';
        
        const color = colors[index % colors.length];
        
        const titleColor = color === 'rgb(152, 193, 29)' ? 'black' : 'white'; 
        const buttonColor = color === 'rgb(152, 193, 29)' ? 'rgb(0, 152, 199)' : 'rgb(152, 193, 29)'; 

        if (element.RUTA_LINK) {
            button = `<button href="#" class="view-button" style="background-color: ${buttonColor}; color: white;" onclick="verLibroLink('${element.RUTA_LINK}', '${element.TITULO}')"> Ver </button>`;
        } else {
            button = `<button class="view-button" style="background-color: ${buttonColor}; color: white;" onclick="verLibroPdf('${element.ID_CENTRO_INFORMACION}', '${element.TITULO}')" > Ver </button>`;
        }

        
        html += `<div class="col-3 mb-5">
                    <div class="book-container">
                        <div class="book">
                            <div class="cover" style="background-color: ${color};">
                                <h1 class="book-title" style="color: ${titleColor};">${element.TITULO}</h1>
                                <p class="book-description">
                                    ${des}
                                </p>
                                ${button}
                            </div>
                            <div class="pages">
                                <div class="page"></div>
                                <div class="page"></div>
                                <div class="page"></div>
                                <div class="page"></div>
                                <div class="page"></div>
                            </div>
                            <div class="side"></div>
                        </div>
                    </div>
                </div>`;
    });


    $('#listaInformacion').html(html)
}

function verLibroLink(ruta, titulo) {
    const url = ruta; 
    const nombreVentana = titulo;       
    const opciones = "width=800,height=600"; 
    window.open(url, nombreVentana, opciones);
}


function verLibroPdf(id, titulo) {

    console.log(id,' - ', titulo);

    $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/bibliotecapdf/'+id);
    $('#modal_visor').modal({backdrop:false});
    $('#nombre_documento_visor').html(titulo);
            
}
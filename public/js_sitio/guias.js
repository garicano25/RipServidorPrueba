

//CARG INICIAL

document.addEventListener("DOMContentLoaded", function () {
    // Mostrar el modal de aviso de privacidad al cargar la página
    $('#avisoPrivacidadModal').modal('show');

    document.getElementById("aceptarPermisos").addEventListener("click", function () {
        $('#avisoPrivacidadModal').modal('hide');

        // Mostrar el modal de la cámara cuando el de privacidad se haya cerrado
        $('#avisoPrivacidadModal').on('hidden.bs.modal', function () {
            ejecucionCamara();
        });
    });
});

//BOTONES
$("#guardar_guia2").click(function () {

	$('#recsensorial_ordenservicio').attr('required', false);
	$('#inputfotomapa').attr('required', false);
	$('#inputfotomapa').prop('required', false);

	if ($('#proyecto_folio').val() != "") {

		// valida campos vacios
		var valida = this.form.checkValidity();
		if (valida) {


				swal({
					title: "¡Confirme que desea guardar!",
					text: "Reconocimiento sensorial",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Aceptar!",
					cancelButtonText: "Cancelar!",
					closeOnConfirm: false,
					closeOnCancel: false
				}, function (isConfirm) {
					if (isConfirm) {
						swal.close();

                        let datosFormulario2 = $('#guia_2').serializeArray();
                        let datosFormulario3 = $('#guia_3').serializeArray();
                        
                        let csrfToken = $('input[name="_token"]').val();

                        let jsonData = {};
                        $.each(datosFormulario2, function(i, field) {
                            // Obtener el valor seleccionado para cada pregunta
                            let preguntaId = field.name; // Por ejemplo: GUIA2_1
                            let valorSeleccionado = field.value; // Por ejemplo: 4

                            // Solo agregar la respuesta si es una opción seleccionada
                            if (!jsonData[preguntaId]) {
                                jsonData[preguntaId] = {
                                    id: field.id, // ID del botón de opción
                                    valor: valorSeleccionado // Valor del botón de opción
                                };
                            }
                        });

                        let formData = {
                            guia2: convertirAObjeto(datosFormulario2),
                            guia3: convertirAObjeto(datosFormulario3),
                            _token: $('input[name="_token"]').val()
                        };

						$('#guia_2').ajaxForm({
							dataType: 'json',
							type: 'POST',
							url: '/reconocimientoPsicosocial',
							data: {
								opcion: 1,
							},
							resetForm: false,
							success: function (dato) {
								
								swal({
									title: "¡El reconocimiento fue guardado exitosamente!.",
									text: "" + dato.msj,
									type: "success", // warning, error, success, info
									buttons: {
										visible: false, // true , false
									},
									timer: 4500,
									showConfirmButton: false
								});

								// actualiza boton
								$('#boton_guardar_recsensorial').html('Guardar <i class="fa fa-save"></i>');
								return false;
							},
							beforeSend: function () {
								$('#boton_guardar_recsensorial').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
							},
							error: function (dato) {
								// actualiza boton
								$('#boton_guardar_recsensorial').html('Guardar <i class="fa fa-save"></i>');
								// mensaje
								swal({
									title: "Error",
									text: "Error en la acción: " + dato,
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
					else {
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


	} else {
		// mensaje

		$('html, body').animate({
			scrollTop: $("#primeraParte").offset().top
		}, 1000);

		swal({
			title: "Falta seleccionar un FOLIO de proyecto",
			text: "Es necesario seleccionar este reconocimiento con un FOLIO de proyecto para poder continuar!.",
			type: "warning", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 2000,
			showConfirmButton: false
		});
		return false;
	}
});

//FUNCIONES
function instruccionesEntendidas(){
    $('#instruccionesModal').modal('hide');
}

function submitGuia1y2() {
    // Asignar valores del trabajador y respuestas
    $("#GUIAI_TRABAJADOR_ID").val($("#TRABAJADOR_ID").val());
    $("#GUIAI_ID_RECOPSICORESPUESTAS").val($("#ID_RECOPSICORESPUESTAS").val());
    $("#GUIAII_TRABAJADOR_ID").val($("#TRABAJADOR_ID").val());
    $("#GUIAII_ID_RECOPSICORESPUESTAS").val($("#ID_RECOPSICORESPUESTAS").val());

    // Obtener los datos de la guia 1
    var form1Data = new FormData(document.getElementById('guia_1'));
    form1Data.append('option', 1); // Enviar opción 1 para la guía 1

    $.ajax({
        url: '/guardarGuiasPsico',
        type: 'POST',
        data: form1Data,
        processData: false,
        contentType: false,
        success: function(response) {
            
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error en Guia 1:', textStatus, errorThrown);
        }
    });

    // Obtener los datos de la guia 2
    var form2Data = new FormData(document.getElementById('form2'));
    form2Data.append('option', 2); // Enviar opción 2 para la guía 2

    $.ajax({
        url: '/guardarGuiasPsico',
        type: 'POST',
        data: form2Data,
        processData: false,
        contentType: false,
        success: function(response) {
           
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error en Guia 2:', textStatus, errorThrown);
        }
    });
}

function validarGuia5(){
    $('#guia5Modal').modal('show');
}

function validarPregunta(){
    const divPreguntas = $('.divPreguntas');
    for(let pregunta of divPreguntas){
        
        const idPregunta = pregunta.id;
        const divActual = $('#'+idPregunta);
        const opciones = pregunta.querySelectorAll('input[type="radio"]');

        var contestado = false;
        for(let opcion of opciones){
            if(opcion.checked){
                contestado = true;
                break;
            }
            
        }
        if(!contestado){
            divActual.css('border', '1px solid red');
           opciones.forEach(opcion => {
            opcion.addEventListener('change', ()=>{
                divActual.css('border', 'none');
            })
           });
           
            return [false,pregunta]
        }else{
            divActual.css('border', 'none');
        }
    }
    return true
}


$('#guardar_guia3').on('click',function(e){  
    e.preventDefault();
    document.getElementById("guardar_guia3").blur();
  
    $("#GUIAI_TRABAJADOR_ID").val($("#TRABAJADOR_ID").val());
    $("#GUIAI_ID_RECOPSICORESPUESTAS").val($("#ID_RECOPSICORESPUESTAS").val());
    $("#GUIAIII_TRABAJADOR_ID").val($("#TRABAJADOR_ID").val());
    $("#GUIAIII_ID_RECOPSICORESPUESTAS").val($("#ID_RECOPSICORESPUESTAS").val());

    
    var form1Data = new FormData(document.getElementById('guia_1'));
    form1Data.append('option', 1); 

    Swal.fire({
      title: "¿Desea guardar sus respuestas?",
      icon: "question",
      width: "700px",
      showDenyButton: true,
      showCancelButton: true,
      denyButtonColor: "#5F9EA0",
      confirmButtonColor: "green",
      cancelButtonColor: "red",
      confirmButtonText: "Guardar y finalizar",
      denyButtonText: `Guardar y continuar más tarde`,
      cancelButtonText: "Cancelar",
    }).then((result) => {
      
      if (result.isConfirmed) {
        swal.close();


        
        // Validar y enviar guia_1
        var form1 = document.getElementById('guia_1');
        var form1Data = new FormData(form1);
        form1Data.append('tipoGuardado', 1); 
        var valida1 = form1.checkValidity();
        
        const [validado,div] = validarPregunta();
        if (validado) {

            $('#guia5Modal').modal('show');
            $.ajax({
                url: '/guardarGuiasPsico',
                type: 'POST',
                data: form1Data,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#guardar_guia3').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                },
                success: function(response) {
                   
                    
                    // Después de enviar guia_1, validar y enviar guia_3
                    var form2 = document.getElementById('guia_3');
                    var form2Data = new FormData(form2);
                    var valida2 = form2.checkValidity();
                    
                    if (valida2) {
                        $.ajax({
                            url: '/guardarGuiasPsico',
                            type: 'POST',
                            data: form2Data,
                            processData: false,
                            contentType: false,
                            beforeSend: function () {
                                $('#guardar_guia3').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                            },
                            success: function(response) {
                                
                                Swal.fire("Guardado y enviado correctamente!", "Usted ha finalizado exitosamente. Ya puede cerrar esta ventana, gracias!", "success");
                                $('#guardar_guia3').html('Guardar <i class="fa fa-save"></i>');
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error('Error en Guia 3:', textStatus, errorThrown);
                                $('#guardar_guia3').html('Guardar <i class="fa fa-save"></i>');
                            }
                        });
                    } else {
                        swal("Advertencia", "Por favor, completa todas las preguntas de la Guia 3 antes de enviar.", "warning");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error en Guia 1:', textStatus, errorThrown);
                    $('#guardar_guia3').html('Guardar <i class="fa fa-save"></i>');
                }
            });
        }else{
            Swal.fire({
                title: "Advertencia",
                text: "Por favor, completa todas las preguntas de la Guia 1 antes de enviar.",
                icon: "warning",
                confirmButtonText: "Aceptar"
            });
            
            div.scrollIntoView({behavior: "smooth", block: "center" });
            
        } 
    }else if (result.isDenied) {
        swal.close();
        
        $.ajax({
            url: '/guardarGuiasPsico',
            type: 'POST',
            data: form1Data,
            processData: false,
            contentType: false,
            success: function(response) {
               
            },
            beforeSend: function () {
                $('#guardar_guia3').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error en Guia 1:', textStatus, errorThrown);
                $('#guardar_guia3').html('Guardar <i class="fa fa-save"></i>');
            }
        });

        var form2Data = new FormData(document.getElementById('guia_3'));
        form2Data.append('option', 3);

        $.ajax({
            url: '/guardarGuiasPsico',
            type: 'POST',
            data: form2Data,
            processData: false,
            contentType: false,
            success: function(response) {
                
                Swal.fire("Guardado para más tarde", "Puede continuar cuando lo desee durante el plazo indicado en su correo electrónico. Recuerde finalizar antes de su fecha límite", "success");
                $('#guardar_guia3').html('Guardar <i class="fa fa-save"></i>');
            },
            beforeSend: function () {
                $('#guardar_guia3').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error en Guia 3:', textStatus, errorThrown);
                $('#guardar_guia3').html('Guardar <i class="fa fa-save"></i>');
            }
        });
      }
    });
    
}) 

function ejecucionCamara(){
    $('#avisoPermisosModal').modal('show');
    navigator.mediaDevices.getUserMedia({ video: true })
            .then(function (stream) {
              
                $('#avisoPermisosModal').modal('hide');
                $('#fotoModal').modal('show');
                
                const tomarFotoBtn = document.getElementById("tomar-foto");
                const imagenInput = document.getElementById("imagen");

                // Crear un elemento de video y configurarlo
                const video = document.createElement("video");
                video.setAttribute("autoplay", true);
                video.style.width = "100%";
                video.style.transform = "scaleX(-1)";
                video.srcObject = stream;

                // Agregar el video dentro del modal en un contenedor específico
                const videoContainer = document.getElementById("video-container");
                videoContainer.innerHTML = ''; // Limpia cualquier contenido previo
                videoContainer.appendChild(video);

                tomarFotoBtn.addEventListener("click", function() {
                    const canvas = document.createElement("canvas");
                    const context = canvas.getContext("2d");
                
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                    canvas.toBlob(function(blob) {
                        const imagenFile = new File([blob], "foto.png", { type: "image/png" });
                        const trabajadorID = $("#TRABAJADOR_ID").val();
                        let formData = new FormData();
                        formData.append('foto', imagenFile);
                        formData.append('trabajadorId', trabajadorID);  // Enviar ID del trabajador
                
                        let csrfToken = $('input[name="_token"]').val();
                
                        // Mostrar el spinner de carga dentro del modal
                        document.getElementById("loadingSpinner").style.display = "block";
                        $('#divFoto').hide();
                        $('#tomar-foto').hide();
                        $.ajax({
                            url: '/guardarFotoRecpsico',
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            success: function(response) {
                                // Ocultar el spinner de carga
                                // $('#loadingSpinner').hide();
                
                                if (response.mensaje) {
                                    $('#fotoModal').modal('hide');
                                    $('#instruccionesModal').modal('show');
                                  
                                } else {
                                    Swal.fire({
                                        title: "Ocurrió un error",
                                        text: "Ha ocurrido un error al intentar capturar la foto, recargue e intente de nuevo",
                                        icon: "error",
                                        showConfirmButton: true,
                                        showCancelButton: false
                                    });
                                    console.error("Error al guardar la foto:", response);
                                }
                            },
                            error: function(xhr, status, error) {
                                // Ocultar el spinner de carga
                               // $('#loadingSpinner').hide();
                
                                Swal.fire({
                                    title: "Ocurrió un error",
                                    text: "Ha ocurrido un error al intentar capturar la foto, intente de nuevo más tarde",
                                    icon: "error",
                                    showConfirmButton: true,
                                    showCancelButton: false
                                });
                                
                                console.error("Error al enviar la solicitud:", error);
                            }
                        });
                
                        // Limpiar video y canvas
                        stream.getVideoTracks()[0].stop();
                        video.remove();
                        canvas.remove();
                    }, "image/png");
                });
                
                
                

                })
            .catch(function (err) {
                $('#avisoPermisosModal').modal('show');
               
            });
} 

function mostrarGuias(requiereGuia1, requiereGuia2, requiereGuia3) {

    const guia1 = document.getElementById('guia1');
    if (requiereGuia1 === 1) {
        guia1.style.display = 'block';
    } else {
        guia1.style.display = 'none';
    }


    const guia2 = document.getElementById('guia2');
    if (requiereGuia2 === 1) {
        guia2.style.display = 'block';
        $('.guia2').addClass('divPreguntas');
    } else {
        guia2.style.display = 'none';
        $('.guia2').removeClass('divPreguntas');
    }

    const guia3 = document.getElementById('guia3');
    if (requiereGuia3 === 1) {
        guia3.style.display = 'block';
        $('.guia3').addClass('divPreguntas');
    } else {
        guia3.style.display = 'none';
        $('.guia3').removeClass('divPreguntas');
    }
}

function botonradio(radioClass) {
    const radios = document.querySelectorAll(`.${radioClass}`);

    radios.forEach((radio) => {
        radio.addEventListener('change', function() {
            const radiosWithSameName = document.querySelectorAll(`input[name="${this.name}"]`);
            
            radiosWithSameName.forEach(radio => {
                const label = document.querySelector(`label[for="${radio.id}"]`);
                label.classList.remove('selected');
            });
            
            const selectedLabel = document.querySelector(`label[for="${this.id}"]`);
            selectedLabel.classList.add('selected');
        });
    });
}

function guia1() {
    const pregunta1Si = document.getElementById("pregunta1_si");
    const pregunta1No = document.getElementById("pregunta1_no");
    const pregunta2Si = document.getElementById("pregunta2_si").checked;
    const pregunta3Si = document.getElementById("pregunta3_si").checked;
    const pregunta4Si = document.getElementById("pregunta4_si").checked;
    const pregunta5Si = document.getElementById("pregunta5_si").checked;
    const pregunta6Si = document.getElementById("pregunta6_si").checked;
    const pregunta7Si = document.getElementById("pregunta7_si").checked;

    const algunaSi = pregunta2Si || pregunta3Si || pregunta4Si || pregunta5Si || pregunta6Si || pregunta7Si;

    if (algunaSi) {
        pregunta1Si.checked = true;
        pregunta1No.checked = false;
    } 
    else {
        pregunta1No.checked = true;
        pregunta1Si.checked = false;
    }

    if (algunaSi) {
        document.getElementById("seccion2").style.display = "block";
        document.getElementById("seccion3").style.display = "block";
        document.getElementById("seccion4").style.display = "block";
        $('.ocultas').addClass('divPreguntas');

    } else {
        document.getElementById("seccion2").style.display = "none";
        document.getElementById("seccion3").style.display = "none";
        document.getElementById("seccion4").style.display = "none";
        $('.ocultas').removeClass('divPreguntas');
        const radiosSecciones = document.querySelectorAll('#seccion2 input[type="radio"], #seccion3 input[type="radio"], #seccion4 input[type="radio"]');
        radiosSecciones.forEach(function(radio) {
            radio.checked = false;
        });
    }
}

function clientesyusuarios() {
    const siSeleccionado = document.getElementById("preguntaadi1_si").checked;

    if (siSeleccionado) {
        document.getElementById("seccion2_2").style.display = "block";
        $('.ocultas2_1').addClass('divPreguntas');
    } else {
        document.getElementById("seccion2_2").style.display = "none";
        $('.ocultas2_1').removeClass('divPreguntas');
        const inputsSeccion = document.querySelectorAll('#seccion2_2 input[type="radio"]');
        inputsSeccion.forEach(function(input) {
            if (input.type === "radio" || input.type === "checkbox") {
                input.checked = false;
            } else {
                input.value = "";
            }
        });
    }
}

function jefetrabajadores() {
    const sijefe = document.getElementById("preguntaadi2_si").checked;

    if (sijefe) {
        document.getElementById("seccion4_2").style.display = "block";
        $('.ocultas2_2').addClass('divPreguntas');
    } else {
        document.getElementById("seccion4_2").style.display = "none";
        $('.ocultas2_2').removeClass('divPreguntas');
        const seccion4guia2 = document.querySelectorAll('#seccion4_2 input[type="radio"]');
        seccion4guia2.forEach(function(input) {
            if (input.type === "radio" || input.type === "checkbox") {
                input.checked = false;
            } else {
                input.value = "";
            }
        });
    }
}

function clientesyusuariosguia3() {
    const siSeleccionadoguia3 = document.getElementById("preguntaadi1_3si").checked;

    if (siSeleccionadoguia3) {
        document.getElementById("seccion2_3").style.display = "block";
        $('.ocultas3_1').addClass('divPreguntas');
    } else {
        document.getElementById("seccion2_3").style.display = "none";
        $('.ocultas3_1').removeClass('divPreguntas');
        const inputsSeccionguia3 = document.querySelectorAll('#seccion2_3 input[type="radio"]');
        inputsSeccionguia3.forEach(function(input) {
            if (input.type === "radio" || input.type === "checkbox") {
                input.checked = false;
            } else {
                input.value = "";
            }
        });
    }
}

function jefetrabajadoresguia3() {
    const sijefeguia3 = document.getElementById("preguntaadi2_3si").checked;

    if (sijefeguia3) {
        document.getElementById("seccion4_3").style.display = "block";
        $('.ocultas3_2').addClass('divPreguntas');

    } else {
        document.getElementById("seccion4_3").style.display = "none";
        $('.ocultas3_2').removeClass('divPreguntas');

        const seccion4guia3 = document.querySelectorAll('#seccion4_3 input[type="radio"]');
        seccion4guia3.forEach(function(input) {
            if (input.type === "radio" || input.type === "checkbox") {
                input.checked = false;
            } else {
                input.value = "";
            }
        });
    }
}

function cargarExplicaciones() {

    const iconElements = document.querySelectorAll('i[id]');
    const ids = Array.from(iconElements).map(iconElement => iconElement.getAttribute('id')); 

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/obtenerExplicaciones",
        data: { 
            ids: ids,
            _token: csrfToken  
        },
        cache: false,
        success: function(data) {
            if (data.explicaciones) {
                iconElements.forEach(function(iconElement) {
                    const iconId = iconElement.getAttribute('id'); 

                    const explicacion = data.explicaciones[iconId];
                    if (explicacion) {
                        iconElement.setAttribute('title', explicacion);
                        $(iconElement).tooltip();
                    }
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener las explicaciones:', error);
        }
    });
}

function scrolldatos() {
    const datos = document.getElementById('col-datos');
    const offsetTop = datos.offsetTop; 

    window.addEventListener('scroll', function() {
        const scrollTop = window.scrollY; 
        if (scrollTop > offsetTop - 100) {
            datos.classList.remove('mt-3');
        } else {
            datos.classList.add('mt-3');
        }
    });
}

function consultarDatos() {
    if (typeof id === 'undefined') {
        console.error("ID del trabajador no está definido");
        return;
    }

    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $.ajax({
        type: "POST",
        url: "/consultarDatosTrabajador", 
        dataType: "json",
        data: {
            id_trabajador: id, 
            _token: csrfToken  
        },
        success: function (data) {
            if (data) {
                $('#nombre-trabajador').text(data.RECPSICOTRABAJADOR_NOMBRE || 'No disponible');
                $('#genero-trabajador').text(data.RECPSICOTRABAJADOR_GENERO || 'No disponible');
                $('#correo-trabajador').text(data.RECPSICOTRABAJADOR_CORREO || 'No disponible');

            } else {
                alert('No se encontraron datos para este trabajador.');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al obtener los datos:', error);
        }
    });
}

function consultarRespuestasGuardadas() {
    if (typeof id === 'undefined') {
        console.error("ID del trabajador no está definido");
        return;
    }

    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $.ajax({
        type: "POST",
        url: "/consultarRespuestasGuardadas", 
        dataType: "json",
        data: {
            id_trabajador: id, 
            _token: csrfToken  
        },
        success: function (data) {
            if (data) {
                    const respuestasGuiaI = data.RECPSICO_GUIAI_RESPUESTAS
                    const respuestasGuiaIArray = JSON.parse(respuestasGuiaI);
                    if (Array.isArray(respuestasGuiaIArray)) {
                        respuestasGuiaIArray.forEach((respuesta, index) => {
                            if (respuesta !== null) {
                                const preguntaDiv = document.getElementById(`pregunta${index + 1}_1`);
                                if (preguntaDiv) {
                
                                    const radioInput = preguntaDiv.querySelector(`input[type="radio"][value="${respuesta}"]`);
                                    if (radioInput) {
                                        radioInput.checked = true; // Marcar el input
                                    }
                                }
                            }
                        });
                        guia1();
                    } else {
                        console.error('La variable respuestasGuiaI no es un arreglo.');
                    }
                    //RESPUESTAS GUIA II
                    const respuestasGuiaII = data.RECPSICO_GUIAII_RESPUESTAS
                    const respuestasGuiaIIArray = JSON.parse(respuestasGuiaII);
                    if (Array.isArray(respuestasGuiaIIArray)) {
                        respuestasGuiaIIArray.forEach((respuesta, index) => {
                            if (respuesta !== null) {
                                const preguntaDiv2 = document.getElementById(`pregunta${index + 1}_2`);
                                if (preguntaDiv2) {
                                    const radioInput2 = preguntaDiv2.querySelector(`input[type="radio"][value="${respuesta}"]`);
                                    if (radioInput2) {
                                        radioInput2.checked = true; // Marcar el input
                                    }
                                }
                            }
                        });
                        clientesyusuarios();
                        jefetrabajadores();

                    } else {
                        console.error('La variable respuestasGuiaII no es un arreglo.');
                    }
                    //RESPUESTAS GUIA III
                    const respuestasGuiaIII = data.RECPSICO_GUIAIII_RESPUESTAS
                    const respuestasGuiaIIIArray = JSON.parse(respuestasGuiaIII);
                    if (Array.isArray(respuestasGuiaIIIArray)) {
                        respuestasGuiaIIIArray.forEach((respuesta, index) => {
                            if (respuesta !== null) {
                                const preguntaDiv3 = document.getElementById(`pregunta${index + 1}_3`);
                                if (preguntaDiv3) {
                                    const radioInput3 = preguntaDiv3.querySelector(`input[type="radio"][value="${respuesta}"]`);
                                    if (radioInput3) {
                                        radioInput3.checked = true;
                                        const label3 = preguntaDiv3.querySelector(`label[for="${radioInput3.id}"]`);
                                        if (label3) {
                                            label3.classList.add('selected'); 
                                        }
                                    }
                                }
                            }
                        });
                        clientesyusuariosguia3();
                        jefetrabajadoresguia3();

                    } else {
                        console.error('La variable respuestasGuiaIII no es un arreglo.');
                    }
            } else {
                alert('No se encontraron respuestas para este trabajador.');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al obtener los datos:', error);
        }
    });
}

// function validarFormulario(form) {
//     var formulario = form;
  
//     // Agrega la clase "validar" a todos los elementos input, textarea y select con el atributo required, incluyendo radios
//     formulario.find('input[required]:not([disabled]), textarea[required]:not([disabled]), select[required]:not([disabled])').addClass('validar').removeClass('error');
  
//     // Busca todos los campos dentro del formulario con la clase "validar"
//     var campos = formulario.find('.validar');
//     var formularioValido = true;
  
//     campos.each(function () {
//         var tipoCampo = $(this).attr('type');
//         var valorCampo = $(this).val();
  
//         // Verifica si el campo es un radio o checkbox y si hay uno seleccionado en el grupo
//         if (tipoCampo === 'radio') {
//             var nombreGrupo = $(this).attr('name');
//             if ($('input[name="' + nombreGrupo + '"]:checked').length === 0) {
//                 $('input[name="' + nombreGrupo + '"]').addClass('error'); // Añade clase error a los radios
//                 formularioValido = false;
//             } else {
//                 $('input[name="' + nombreGrupo + '"]').removeClass('error');
//             }
//         } 
//         // Valida otros tipos de campos (text, email, etc.)
//         else if (valorCampo === '' || valorCampo === null) {
//             $(this).addClass('error');
//             formularioValido = false;
//         } else {
//             $(this).removeClass('error');
//         }
//     });
  
//     return formularioValido;
//   }
  
//   // Evento para eliminar la clase "error" cuando el campo cambia o recibe entrada
//   $(document).on('input change', 'input[required], textarea[required], select[required]', function() {
//     var tipoCampo = $(this).attr('type');
  
//     // Si es radio o checkbox, solo remueve el error cuando uno del grupo es seleccionado
//     if (tipoCampo === 'radio' || tipoCampo === 'checkbox') {
//         var nombreGrupo = $(this).attr('name');
//         if ($('input[name="' + nombreGrupo + '"]:checked').length > 0) {
//             $('input[name="' + nombreGrupo + '"]').removeClass('error');
//         }
//     } else {
//         if ($(this).val() !== '' && $(this).val() !== null) {
//             $(this).removeClass('error');
//         }
//     }
//   });
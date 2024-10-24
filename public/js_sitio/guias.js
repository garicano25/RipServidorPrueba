console.log('Guía 1:', requiereGuia1);
console.log('Guía 2:', requiereGuia2);
console.log('Guía 3:', requiereGuia3);
console.log('Id:', id);

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
            console.log('Guia 1 enviada:', response);
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
            console.log('Guia 2 enviada:', response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error en Guia 2:', textStatus, errorThrown);
        }
    });
}

function submitGuia1y3() {
    // Asignar valores del trabajador y respuestas
    $("#GUIAI_TRABAJADOR_ID").val($("#TRABAJADOR_ID").val());
    $("#GUIAI_ID_RECOPSICORESPUESTAS").val($("#ID_RECOPSICORESPUESTAS").val());
    $("#GUIAIII_TRABAJADOR_ID").val($("#TRABAJADOR_ID").val());
    $("#GUIAIII_ID_RECOPSICORESPUESTAS").val($("#ID_RECOPSICORESPUESTAS").val());

    // Obtener los datos de la guia 1
    var form1Data = new FormData(document.getElementById('guia_1'));
    form1Data.append('option', 1); // Enviar opción 1 para la guía 1
    
    // swal({
    //     title: "¡Confirme que desea guardar!",
    //     text: "Guardar para mas tarde",
    //     type: "warning",
    //     showCancelButton: true,
    //     confirmButtonColor: "#DD6B55",
    //     confirmButtonText: "Aceptar!",
    //     cancelButtonText: "Cancelar!",
    //     closeOnConfirm: false,
    //     closeOnCancel: false
    // }, function (isConfirm) {
    //     if (isConfirm) {
            
    //         swal.close();
    //         $.ajax({
    //             url: '/guardarGuiasPsico',
    //             type: 'POST',
    //             data: form1Data,
    //             processData: false,
    //             contentType: false,
    //             success: function(response) {
    //                 console.log('Guia 1 enviada:', response);
    //                 $('#guardar_guia3').html('Guardar <i class="fa fa-save"></i>');
    //             },
    //             beforeSend: function () {
    //                 $('#guardar_guia3').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
    //             },
    //             error: function(jqXHR, textStatus, errorThrown) {
    //                 console.error('Error en Guia 1:', textStatus, errorThrown);
    //                 $('#guardar_guia3').html('Guardar <i class="fa fa-save"></i>');
    //             }
    //         });

    //         // Obtener los datos de la guia 2
    //         var form2Data = new FormData(document.getElementById('guia_3'));
    //         form2Data.append('option', 3);

    //         $.ajax({
    //             url: '/guardarGuiasPsico',
    //             type: 'POST',
    //             data: form2Data,
    //             processData: false,
    //             contentType: false,
    //             success: function(response) {
    //                 console.log('Guia 3 enviada:', response);
    //                 $('#guardar_guia3').html('Guardar <i class="fa fa-save"></i>');
    //             },
    //             beforeSend: function () {
    //                 $('#guardar_guia3').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
    //             },
    //             error: function(jqXHR, textStatus, errorThrown) {
    //                 console.error('Error en Guia 3:', textStatus, errorThrown);
    //                 $('#guardar_guia3').html('Guardar <i class="fa fa-save"></i>');
    //             }
    //         });
    //     }
    //     else {
    //         // mensaje
    //         swal({
    //             title: "Cancelado",
    //             text: "Acción cancelada",
    //             type: "error", // warning, error, success, info
    //             buttons: {
    //                 visible: false, // true , false
    //             },
    //             timer: 500,
    //             showConfirmButton: false
    //         });
    //     }
    // });

    Swal.fire({
      title: "Do you want to save the changes?",
      showDenyButton: true,
      showCancelButton: true,
      confirmButtonText: "Save",
      denyButtonText: `Don't save`
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        Swal.fire("Saved!", "", "success");
      } else if (result.isDenied) {
        Swal.fire("Changes are not saved", "", "info");
      }
    });
}


function ejecucionCamara(){
    navigator.mediaDevices.getUserMedia({ video: true })
            .then(function (stream) {
                console.log("Permiso de cámara concedido");
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

                // Escucha el evento click del botón para capturar la imagen
                // tomarFotoBtn.addEventListener("click", function() {
                //     const canvas = document.createElement("canvas");
                //     const context = canvas.getContext("2d");

                //     // Configura el canvas con el mismo tamaño que el video
                //     canvas.width = video.videoWidth;
                //     canvas.height = video.videoHeight;

                //     // Captura la imagen del video y la dibuja en el canvas
                //     context.drawImage(video, 0, 0, canvas.width, canvas.height);

                //     // Convierte el canvas a un archivo de imagen
                //     canvas.toBlob(function(blob) {
                //         const imagenFile = new File([blob], "foto.png", {
                //             type: "image/png"
                //         });
                //         imagenInput.files = [imagenFile];
                //         // Limpia el video y el canvas
                //         stream.getVideoTracks()[0].stop();
                //         video.remove();
                //         canvas.remove();
                //     }, "image/png");
                // });

                tomarFotoBtn.addEventListener("click", function() {
                    const canvas = document.createElement("canvas");
                    const context = canvas.getContext("2d");
                
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                    canvas.toBlob(function(blob) {
                        const imagenFile = new File([blob], "foto.png", { type: "image/png" });
                        const trabajadorID = 7188;
                        let formData = new FormData();
                        formData.append('foto', imagenFile);
                        formData.append('trabajadorId', trabajadorID);  // Enviar ID del trabajador
                
                        let csrfToken = $('input[name="_token"]').val();
                
                        // Mostrar el spinner de carga dentro del modal
                        $('#loadingSpinner').show();
                
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
                                $('#loadingSpinner').hide();
                
                                if (response.mensaje) {
                                    $('#fotoModal').modal('hide');
                                    console.log("Foto guardada correctamente", response);
                                } else {
                                    swal({
                                        title: "Ocurrió un error",
                                        text: "Ha ocurrido un error al intentar capturar la foto, recargue e intente de nuevo",
                                        type: "error", 
                                        buttons: {visible: true},
                                        showConfirmButton: true,
                                        showCancelButton: false
                                    });
                                    console.error("Error al guardar la foto:", response);
                                }
                            },
                            error: function(xhr, status, error) {
                                // Ocultar el spinner de carga
                                $('#loadingSpinner').hide();
                
                                swal({
                                    title: "Ocurrió un error",
                                    text: "Ha ocurrido un error al intentar capturar la foto, intente de nuevo más tarde",
                                    type: "error", 
                                    buttons: {visible: true},
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
                console.log("Error al intentar acceder a la cámara: ", err);
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
    } else {
        guia2.style.display = 'none';
    }

    const guia3 = document.getElementById('guia3');
    if (requiereGuia3 === 1) {
        guia3.style.display = 'block';
    } else {
        guia3.style.display = 'none';
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
    } else {
        document.getElementById("seccion2").style.display = "none";
        document.getElementById("seccion3").style.display = "none";
        document.getElementById("seccion4").style.display = "none";

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
    } else {
        document.getElementById("seccion2_2").style.display = "none";

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
    } else {
        document.getElementById("seccion4_2").style.display = "none";

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
    } else {
        document.getElementById("seccion2_3").style.display = "none";

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
    } else {
        document.getElementById("seccion4_3").style.display = "none";

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
            datos.classList.remove('mt-5');
        } else {
            datos.classList.add('mt-5');
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




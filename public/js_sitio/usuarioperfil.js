// Variables globales
var passwordperfil_error = 0;


// Load PAGINA
$(document).ready(function() 
{
    // inicializar campo FOTO
	$('#fotoperfil').dropify({
		messages: {
			'default': 'Arrastre la imagen aquí o haga click',
			'replace': 'Arrastre la imagen o haga clic para reemplazar',
			'remove':  'Quitar',
			'error':   'Ooops, ha ocurrido un error.'
		},
		error: {
			'fileSize': 'Demasiado grande ({{ value }} max).',
			'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
			'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
			'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
			'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
			'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
		}
	});
});


function verificapasswordperfil(pass)
{
	var dato = parseInt(pass.length);

	if (dato > 5) //Minimo 6 caracteres
	{
		if ($('#usuarioperfil_password').val() === $('#usuarioperfil_password_2').val())
		{
			passwordperfil_error = 0;
			$('#password_mensajeusuarioperfil').html('<h5 class="text-success">* Contraseñas iguales *</h5>');
		}
		else
		{
			passwordperfil_error = 1;
			$('#password_mensajeusuarioperfil').html('<h5 class="text-danger">* Las contraseñas no son iguales *</h5>');
		}
	}
	else
	{
		passwordperfil_error = 1;
		$('#password_mensajeusuarioperfil').html('<h5 class="text-danger">* La contraseña debe tener mínimo 6 caracteres *</h5>');
	}
}


$("#boton_guardarusuarioperfil").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		if (passwordperfil_error == 0)
		{
			// confirmar guardar
			swal({   
	            title: "¡Confirme que desea guardar los cambios!",
	            text: "Usuario",
	            type: "warning",
	            showCancelButton: true,
	            confirmButtonColor: "#DD6B55",
	            confirmButtonText: "Guardar!",
	            cancelButtonText: "Cancelar!",
	            closeOnConfirm: false,
	            closeOnCancel: false
	        }, function(isConfirm){
	            if (isConfirm)
	            {
	            	// cerrar msj confirmacion
                	swal.close();

	            	// enviar datos
					$('#form_usuarioperfil').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '/usuario',
						data:{
				        	usuario_admin: 0
				        },
						resetForm: false,
						success: function(dato)
						{
							// mensaje
							swal({
								title: "Correcto",
								text: ""+dato.msj,
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 4000,
								showConfirmButton: false
							});

							// actualiza boton
							$('#boton_guardarusuarioperfil').html('Guardar cambios <i class="fa fa-save"></i>');

							// cerrar modal
							// $('#modal_maquina').modal('hide');
						},
						beforeSend: function(){
							$('#boton_guardarusuarioperfil').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						},
						error: function(dato) {
							// actualiza boton
							$('#boton_guardarusuarioperfil').html('Guardar cambios <i class="fa fa-save"></i>');
							
							// mensaje
							swal({
								title: "Error",
								text: "Error en la acción: "+dato,
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
	                    timer: 1500,
	                    showConfirmButton: false
	                });   
	            }
			});
			return false;
		}
		else
		{
			// mensaje
			swal({
				title: "¡Passwords incorrectos!",
				text: "Los password no contienen como mínimo 6 caracteres o no son iguales",
				type: "warning", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 2000,
				showConfirmButton: false
			});
			return false;
		}
	}
});
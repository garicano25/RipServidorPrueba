// Variables globales
var datatable_usuarios = null;
var password_error = 0;


// Load PAGINA
$(document).ready(function() 
{
    // mostrar usuarios del sistema
	funcion_tabla_usuarios();

	// Inicializar tooltip
	$('[data-toggle="tooltip"]').tooltip();
});


function funcion_tabla_usuarios()
{
	try
	{
		if (datatable_usuarios != null)
		{
			datatable_usuarios.clear().draw();
			datatable_usuarios.ajax.url("/usuariotabla").load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_usuarios = $('#tabla_usuarios').DataTable({
		        "ajax": {
		            "url": "/usuariotabla",
		            "type": "get",
		            "cache": false,
		            error: function (xhr, error, code)
		            {
		                // console.log(xhr); console.log(code);
						console.log('error en tabla Usuarios');
						if (numeroejecucion <= 1)
						{
							funcion_tabla_usuarios();
							numeroejecucion += 1;
						}
		            },
		            "data": {}
		        },
		        "columns": [
		            // {
		            //     "data": "id"
		            // },
		            {
		                "data": "numero_registro",
		                "defaultContent": "-"
		            },
		            {
		                "data": "foto_usuario",
		                "orderable": false,
		                "defaultContent": "-"
		            },
		            {
		                "data": "nombre_completo",
		                "defaultContent": "-"
		            },
		            {
		                "data": "correo_telefono",
		                "defaultContent": "-",
		                "orderable": false
		            },
		            {
		                "data": "tipo",
		                "defaultContent": "-"
		            },
		            {
		                "data": "roles_acceso",
		                "defaultContent": "-"
		            },
		            {
		                "data": "checkbox_estado",
		                "defaultContent": "-",
		                "orderable": false
		            },
					{
					    // "className": 'editar',
					    "orderable": false,
					    "data": 'boton_editar',
					    // "defaultContent": '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>'
					},
					{
					    // "className": 'eliminar',
					    "orderable": false,
					    "data": 'boton_eliminar',
					    "defaultContent": '-'
					    // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>'
					}
		        ],
		        "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		        // "rowsGroup": [0], //agrupar filas
		        "order": [[ 0, "desc" ]],
		        "ordering": true,
		        "processing": true,
		        "language": {
		            "lengthMenu": "Mostrar _MENU_ Registros",
		            "zeroRecords": "No se encontraron registros",
		            "info": "Página _PAGE_ de _PAGES_ (Total _MAX_ registros)",
		            "infoEmpty": "No se encontraron registros",
		            "infoFiltered": "(Filtrado de _MAX_ registros)",
		            "emptyTable": "No hay datos disponibles en la tabla",
		            "loadingRecords": "Cargando datos....",
		            "processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
		            "search": "Buscar",
		            "paginate": {
		                "first": "Primera",
		                "last": "Ultima",
		                "next": "Siguiente",
		                "previous": "Anterior"
		            }
		        }
		    });
		}

		// Tooltip en DataTable
        datatable_usuarios.on( 'draw', function ()
        {
            $('[data-toggle="tooltip"]').tooltip();
        });
	}
	catch (exception)
	{
		// alert("error en el ajax");
		funcion_tabla_usuarios();
	}
}


function tipousuario(opcion)
{
	// Borrar formulario
	$('#form_usuario').each(function(){
		this.reset();
	});

	// Inicializar campo FOTO
	$('#fotousuario').dropify({
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
    }).data('dropify').clearElement();

    // Campos Hidden
    $("#usuario_id").val(0);

    // Selecciona opcion select
	$('#usuario_tipo').val(opcion);

	var usuario_tipo = parseInt(opcion);

	switch (usuario_tipo)
	{
		case 2: //Proveedor
			// Ocultar campos empleado
			$('.campo_dato_empleado').css('display', 'none');
			$('#empleado_nombre').attr('required', false);
			$('#empleado_apellidopaterno').attr('required', false);
			$('#empleado_apellidomaterno').attr('required', false);
			$('#empleado_direccion').attr('required', false);
			$('#empleado_cargo').attr('required', false);
			$('#empleado_telefono').attr('required', false);
			$('#empleado_fechanacimiento').attr('required', false);
			$('#empleado_correo').attr('required', false);

			// Mostrar campos proveedor
			$('.campo_dato_proveedor').css('display', 'block');
			$('#proveedor_id').attr('required', true);
			$('#proveedor_nombre').attr('required', true);
			$('#proveedor_correo').attr('required', true);

			// ACTIVAR [Roles]
			$('.checkbox_rol').each(function()
			{
				if (this.id == "rol_30") // Rol Externo [Proveedor]
				{
					$("#"+this.id).attr('disabled', false);
					$("#"+this.id).prop('checked', true);
				}
				else
				{
					$("#"+this.id).attr('disabled', true);
					$("#"+this.id).prop('checked', false);
				}
			});

			// titulo cammpo foto
			$('#foto_titulo').html('Logo proveedor *');
        	break;
        default: //Empleados
			// Ocultar campos proveedor
			$('.campo_dato_proveedor').css('display', 'none');
			$('#proveedor_id').attr('required', false);
			$('#proveedor_nombre').attr('required', false);
			$('#proveedor_correo').attr('required', false);

			// Mostrar campos empleado
			$('.campo_dato_empleado').css('display', 'block');
			$('#empleado_nombre').attr('required', true);
			$('#empleado_apellidopaterno').attr('required', true);
			$('#empleado_apellidomaterno').attr('required', true);
			$('#empleado_direccion').attr('required', true);
			$('#empleado_cargo').attr('required', true);
			$('#empleado_telefono').attr('required', true);
			$('#empleado_fechanacimiento').attr('required', true);
			$('#empleado_correo').attr('required', true);

			// ACTIVAR [Roles]
			$('.checkbox_rol').each(function()
			{
				if (this.id == "rol_30") // Rol Externo [Proveedor]
				{
					$("#"+this.id).attr('disabled', true);
					$("#"+this.id).prop('checked', false);
				}
				else
				{
					$("#"+this.id).attr('disabled', false);
					$("#"+this.id).prop('checked', false);
				}
			});

			// titulo cammpo foto
			$('#foto_titulo').html('Foto usuario *');
        	break;
	}
}



function verificapassword(pass)
{
	var dato = parseInt(pass.length);

	if (dato > 5) //Minimo 6 caracteres
	{
		if ($('#password').val() === $('#password_2').val())
		{
			password_error = 0;
			$('#password_mensaje').html('<h5 class="text-success">* Contraseñas iguales *</h5>');
		}
		else
		{
			password_error = 1;
			$('#password_mensaje').html('<h5 class="text-danger">* Las contraseñas no son iguales *</h5>');
		}
	}
	else
	{
		password_error = 1;
		$('#password_mensaje').html('<h5 class="text-danger">* La contraseña debe tener mínimo 6 caracteres *</h5>');
	}
}


function select_proveedor(proveedor_id, proveedor_nombre)
{
	$("#proveedor_nombre").val($("#proveedor_id option:selected").text());
}


// Activar Rol superusuario
$('#rol_1').on('change', function() 
{
	if ($('#rol_1').is(':checked') == 1) 
    {
        $('.checkbox_rol').each( function() {
	        if (this.id != "rol_1")
	        {
	        	$('#'+this.id).prop('checked', false);
	        	$('#'+this.id).attr('disabled', true);
	        }
	    });
    }
    else
    {
    	$('.checkbox_rol').each( function() {
	        if (this.id != "rol_30") //externo deshabilitado
	        {
	        	$('#'+this.id).attr('disabled', false);
	        	$('#'+this.id).prop('checked', false);
	        }
	    });
    }
});


// Activar Rol administrador
$('#rol_2').on('change', function() 
{
	if (parseInt(permiso) == 1)
	{
		if ($('#rol_2').is(':checked') == 1) 
	    {
	        $('.checkbox_rol').each( function()
	        {
		        if ((this.id == "rol_2") || (this.id == "rol_19")) // Administrador y financiero
		        {
		        	// Disponibles
		        }
		        else
		        {
		        	$('#'+this.id).prop('checked', false);
		        	$('#'+this.id).attr('disabled', true);
		        }
		    });
	    }
	    else
	    {
	    	$('.checkbox_rol').each( function() {
		        if (this.id != "rol_30") //externo deshabilitado
		        {
		        	$('#'+this.id).attr('disabled', false);
		        	$('#'+this.id).prop('checked', false);
		        }
		    });
	    }
	}
});


$('#boton_nuevousuario').on('click', function() 
{
    // activar campos segun tipo usuario
    tipousuario(1);

    // Borrar formulario
    $('#form_usuario').each(function(){
        this.reset();
    });

	// Campos Hidden
    $("#usuario_id").val(0);
    $("#usuario_tipo").attr('disabled', false);

	// requerir campo FOTO
	$('#fotousuario').attr('required', true);

	// mostrar modal
	$('#modal_usuario').modal({backdrop:false});
});


// Selecciona USUARIO
$('#tabla_usuarios tbody').on('click', 'td>button.editar', function() 
{
    var tr = $(this).closest('tr');
    var row = datatable_usuarios.row(tr);

    // activar campos segun tipo usuario
    tipousuario(row.data().usuario_tipo);

    // Campos Hidden
    $("#usuario_id").val(row.data().id);

    // llenar campos
    $("#usuario_tipo").val(row.data().usuario_tipo);
    $("#usuario_tipo").attr('disabled', true);

  	// Tipo de usuario
    switch (parseInt(row.data().usuario_tipo))
	{
		case 2: //Proveedor
			// llenar campos
			$("#proveedor_id").val(row.data().empleado_id);
			$("#proveedor_nombre").val(row.data().name);
			$("#proveedor_correo").val(row.data().email);
			$("#password").val(row.data().clave);
			$("#password_2").val(row.data().clave);
        	break;
        default: //Empleados
			// Llenar campos
			$("#empleado_nombre").val(row.data().empleado.empleado_nombre);
			$("#empleado_apellidopaterno").val(row.data().empleado.empleado_apellidopaterno);
			$("#empleado_apellidomaterno").val(row.data().empleado.empleado_apellidomaterno);
			$("#empleado_cargo").val(row.data().empleado.empleado_cargo);
			$("#empleado_direccion").val(row.data().empleado.empleado_direccion);
			$("#empleado_telefono").val(row.data().empleado.empleado_telefono);
			$("#empleado_fechanacimiento").val(row.data().empleado.empleado_fechanacimiento);
			$("#empleado_correo").val(row.data().empleado.empleado_correo);
			$("#password").val(row.data().clave);
			$("#password_2").val(row.data().clave);
        	break;
	}

	// ACTIVAR ROLES
	$.each( row.data().roles, function( key, value )
	{
		$("#rol_"+value.id).attr('disabled', false);
		$("#rol_"+value.id).prop('checked', true);
	});

	// VERIFICA TIPO DE USUARIO
	if ($('#rol_1').is(':checked') == 1)  //Superusuario
    {
    	$('.checkbox_rol').each( function()
    	{
	        if (this.id != "rol_1")
	        {
	        	$('#'+this.id).prop('checked', false);
	        	$('#'+this.id).attr('disabled', true);
	        }
	    });
    }
    else
    {
    	if ($('#rol_2').is(':checked') == 1) //Administrador
	    {
	        $('.checkbox_rol').each( function()
	        {
		        if ((this.id === "rol_2") || (this.id === "rol_19"))
		        {
		        	// Disponibles
		        }
		        else
		        {
		        	$('#'+this.id).prop('checked', false);
		        	$('#'+this.id).attr('disabled', true);
		        }
		    });
	    }
    }
    
	// Mostrar FOTO
	if (row.data().usuario_foto)
	{
		var archivo = row.data().usuario_foto;
	    var extension = archivo.substring(archivo.lastIndexOf("."));
		var imagenUrl = '/usuariofoto/'+row.data().id+extension;
		// var imagenUrl = '/usuariofoto/'+row.data().usuario_foto;
		$('#fotousuario').dropify().data('dropify').destroy();
		$('#fotousuario').dropify().data('dropify').settings.defaultFile = imagenUrl;
		$('#fotousuario').dropify().data('dropify').init();

		// requerir campo
		$('#fotousuario').attr('required', false);
	}
	else
	{
		$('#fotousuario').val('');
	    $('#fotousuario').dropify().data('dropify').resetPreview();
		$('#fotousuario').dropify().data('dropify').clearElement();

		// requerir campo
		$('#fotousuario').attr('required', true);
	}

	// mostrar modal
	$('#modal_usuario').modal({backdrop:false});
});


$("#boton_guardarusuario").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		if (password_error == 0)
		{
			var usuario_tipo = parseInt($('#usuario_tipo').val());
			if (usuario_tipo == 2)
			{
				$('#rol_30').prop('checked', true); //Activar rol [EXTERNO]
			}

			// confirmar guardar
			swal({   
	            title: "¡Confirme que desea guardar!",
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
					$('#form_usuario').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '/usuario',
						data:{
				        	usuario_admin: 1
				        },
						resetForm: false,
						success: function(dato)
						{
							// Campos Hidden
							$("#usuario_id").val(dato.usuario.id);

							// actualiza tabla
							funcion_tabla_usuarios();

							// mensaje
							swal({
								title: "Correcto",
								text: ""+dato.msj,
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});

							// actualiza boton
							$('#boton_guardarusuario').html('Guardar <i class="fa fa-save"></i>');

							// cerrar modal
							$('#modal_usuario').modal('hide');
						},
						beforeSend: function()
						{
							$('#boton_guardarusuario').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						},
						error: function(dato) {
							// actualiza boton
							$('#boton_guardarusuario').html('Guardar <i class="fa fa-save"></i>');
							
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
	                    timer: 500,
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


function usuario_estado(checkbox, usuario_nombre)
{
	// alert(checkbox.checked+' - '+checkbox.value);

	var mensaje = '';
	var estado = 0;

	if (checkbox.checked)
	{
		mensaje = '¿Activar el perfil de usuario?';
		estado = 1;

		// Mantener el checkbox en su estado antes de la acción
		$(checkbox).prop('checked', false);
	}
	else
	{
		mensaje = '¿Desactivar el perfil de usuario?';
		estado = 0;

		// Mantener el checkbox en su estado antes de la acción
		$(checkbox).prop('checked', true);
	}

	// CONFIRMAR
	swal({   
		title: ""+mensaje,
		text: ""+usuario_nombre,
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function(isConfirm)
	{
		if (isConfirm)
		{
			// cerrar msj confirmacion
			swal.close();

			// ENVIAR DATOS
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "/usuarioestado/"+checkbox.value+"/"+estado,
				data:{},
				cache: false,
				success:function(dato)
				{
					// CONFIRMAR ESTADO CHECKBOX
					if (estado == 1)
					{
						$(checkbox).prop('checked', true);
					}
					else
					{
						$(checkbox).prop('checked', false);
					}

					// mensaje
					swal({
						title: "Correcto",
						text: ""+dato.msj,
						type: "success", // warning, error, success, info
						buttons: {
						visible: false, // true , false
						},
						timer: 1500,
						showConfirmButton: false
					});
				},
				error: function(dato)
				{
					// mensaje
					swal({
						title: "Error",
						text: "Intentelo de nuevo",
						type: "error", // warning, error, success, info
						buttons: {
						visible: false, // true , false
						},
						timer: 1500,
						showConfirmButton: false
					});
					return false;
				}
			});//Fin ajax
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
}


// elimina USUARIO
$('#tabla_usuarios tbody').on('click', 'td>button.eliminar', function() 
{
    var tr = $(this).closest('tr');
    var row = datatable_usuarios.row(tr);

    if (row.data().superusuario == 0)
    {
    	swal({   
	        title: "¿Eliminar usuario?",   
	        text: ""+row.data().name,
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
	        	swal({   
		            title: "¡Confirme nuevamente eliminar el usuario!",
		            text: ""+row.data().name,
		            type: "warning",   
		            showCancelButton: true,   
		            confirmButtonColor: "#DD6B55",   
		            confirmButtonText: "Eliminar!",   
		            cancelButtonText: "Cancelar!",   
		            closeOnConfirm: false,   
		            closeOnCancel: false 
		        }, function(isConfirm){   
		            if (isConfirm)
		            {
		            	// cerrar msj confirmacion
                		swal.close();

		            	// eliminar
				        $.ajax({
					        type: "GET",
					        dataType: "json",
					        url: "/usuarioeliminar/"+row.data().id+"/"+row.data().usuario_tipo+"/"+row.data().empleado_id,
					        data:{},
					        cache: false,
					        success:function(dato)
					        {
					        	// actualiza tabla
								funcion_tabla_usuarios();

					        	// mensaje
					            swal({
					                title: "Correcto",
					                 text: ""+dato.msj,
					                type: "success", // warning, error, success, info
					                buttons: {
					                    visible: false, // true , false
					                },
					                timer: 1500,
					                showConfirmButton: false
					            });
					        },
					        error: function(dato)
					        {
					            // mensaje
								swal({
									title: "Error",
									text: "Intentelo de nuevo",
									type: "error", // warning, error, success, info
									buttons: {
									visible: false, // true , false
									},
									timer: 1500,
									showConfirmButton: false
								});
								return false;
					        }
					    });//Fin ajax
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
    }
    else
    {
    	// mensaje
        swal({
            title: "El usuario no se puede eliminar",
            text: "Este tipo de usuario solo se le puede editar la información",
            type: "warning", // warning, error, success, info
            buttons: {
                visible: false, // true , false
            },
            timer: 1500,
            showConfirmButton: false
        });
    }
});
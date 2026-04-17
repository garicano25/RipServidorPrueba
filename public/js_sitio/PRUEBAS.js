$("#boton_nueva_area").click(function (e) {
    e.preventDefault();


    ID_AREA_ERGO = 0;
       


    $('#form_area').each(function(){
        this.reset();
    });


    $("#modal_area").modal("show");

    $('#modal_area .modal-title').html('Nueva área');


});



$("#boton_guardar_area").click(function (e) {
    e.preventDefault();


    formularioValido = validarFormulario3($('#form_area'))

    if (formularioValido) {

    if (ID_AREA_ERGO == 0) {
        
        alertMensajeConfirm({
            title: "¿Desea guardar la información?",
            text: "Al guardarla, se podra usar",
            icon: "question",
        },async function () { 

            await loaderbtn('boton_guardar_area')
            await ajaxAwaitFormData({ api: 1,RECO_ID: recsensorial, ID_AREA_ERGO: ID_AREA_ERGO }, 'recoergoareas', 'form_area', 'boton_guardar_area', { callbackAfter: true, callbackBefore: true }, () => {
        
                Swal.fire({
                    icon: 'info',
                    title: 'Espere un momento',
                    text: 'Estamos guardando la información',
                    showConfirmButton: false
                })

                $('.swal2-popup').addClass('ld ld-breath')
        
                
            }, function (data) {
                    
                    ID_AREA_ERGO = data.areas.ID_AREA_ERGO
                    alertMensaje('success','Información guardada correctamente', 'Esta información esta lista para usarse',null,null, 1500)
                     $('#modal_area').modal('hide')
                    document.getElementById('form_area').reset();
                    Tablarecoareasergo.ajax.reload()
                
            })
            
            
            
        }, 1)
        
    } else {
            alertMensajeConfirm({
            title: "¿Desea editar la información de este formulario?",
            text: "Al guardarla, se podra usar",
            icon: "question",
        },async function () { 

            await loaderbtn('boton_guardar_area')
            await ajaxAwaitFormData({ api: 1, RECO_ID: recsensorial, ID_AREA_ERGO: ID_AREA_ERGO }, 'recoergoareas', 'form_area', 'boton_guardar_area', { callbackAfter: true, callbackBefore: true }, () => {
        
                Swal.fire({
                    icon: 'info',
                    title: 'Espere un momento',
                    text: 'Estamos guardando la información',
                    showConfirmButton: false
                })

                $('.swal2-popup').addClass('ld ld-breath')
        
                
            }, function (data) {
                    
                setTimeout(() => {

                    ID_AREA_ERGO = data.areas.ID_AREA_ERGO
                    alertMensaje('success', 'Información editada correctamente', 'Información guardada')
                     $('#modal_area').modal('hide')
                    document.getElementById('form_area').reset();
                    Tablarecoareasergo.ajax.reload()

                }, 300);  
            })
        }, 1)
    }

} else {
    alertToast('Por favor, complete todos los campos del formulario.', 'error', 2000)

}
    
});






function mostrartablarecoareasergo() {
	try {
		var ruta = "/Tablarecoareasergo";

		if (Tablarecoareasergo != null) {
			Tablarecoareasergo.destroy();
		}

		Tablarecoareasergo = $('#Tablarecoareasergo').DataTable({
			"ajax": {
				"url": ruta,
				"type": "get",
				"cache": false,
				"data": {
					ergoid: recsensorial 
				},
				"error": function (xhr, error, code) {
					console.log('error en Tablarecoareasergo');
				}
			},
			"columns": [
				{
					data: null,
					render: function (data, type, row, meta) {
						return meta.row + 1;
					}
				},
				{
					"data": "NOMBRE_AREA_ERGO",
					"defaultContent": "-"
				},
				{
					"data": "DESCRIPCION_AREA_ERGO",
					"defaultContent": "-"
				},
				{ 
					data: 'BTN_EDITAR',
					orderable: false,
					searchable: false
				},
			],
			"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
			"order": [[0, "DESC"]],
			"ordering": true,
			"processing": true,
			"responsive": true,
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
					"last": "Última",
					"next": "Siguiente",
					"previous": "Anterior"
				}
			}
		});

		Tablarecoareasergo.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});

	} catch (exception) {
		console.error("Error en Tablarecoareasergo:", exception);
	}
}



$('#Tablarecoareasergo tbody').on('click', 'td>button.editar', function () {
    var tr = $(this).closest('tr');
    var row = Tablarecoareasergo.row(tr);


    ID_AREA_ERGO = row.data().ID_AREA_ERGO;

    editarDatoTabla(row.data(), 'form_area', 'modal_area');

    $('#modal_area .modal-title').html(row.data().NOMBRE_AREA_ERGO);

});
@extends('template/maestra')
@section('contenido')



<style>
    #informes_reco {
      background-color: #FFF !important;
    }
  </style>
  
  <div class="row">
      <div class="col-12">
          <div class="card mt-4">
              <div class="card-body bg-info">
                  <div class="form-group ">
                      <label style="color: #ffffff; font-weight: 600;">Seleccione folio de proyecto o reconociminento</label>
                      <select class="custom-select form-control" id="informes_reco" name="informes_reco" onchange="seleccionar_proyectos(this.value);">
                        <option value="">&nbsp;</option>
                    </select>
                    
                  </div>
                  <h4 class="text-white card-title" style="float: left; margin: 0px; padding: 0px;">Reporte de&nbsp;&nbsp;</h4>
                  <select class="custom-select" style="float: left; width: 340px; height: 24px; margin: 0px; padding: 2px 4px; font-weight: 550; background: #FFF;" id="select_tiporeportes" onchange="mostrar_reporte(this.value);">
                    <option value="">Seleccione</option>
                </select>
              </div>
              <div class="card-body" style="border: 0px #f00 solid; min-height: 856px!important;" id="estructura_reporte">
                  <p style="text-align: center; font-size: 24px;">Seleccione un tipo de reporte</p>
              </div>
          </div>
      </div>
  </div>
  

  

@endsection
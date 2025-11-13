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
          <label style="color: #ffffff; font-weight: 600;">Seleccione folio de proyecto y reconociminento</label>
          <select class="custom-select form-control" id="informes_reco" name="informes_reco" onchange="seleccionar_proyectos(this.value);">
            <option value="">&nbsp;</option>
          </select>

        </div>


        <!-- @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
        <h4 class="text-white card-title" style="float: left; margin: 0px; padding: 0px;">Informes y entregables &nbsp;&nbsp;</h4>
        <select class="custom-select" style="float: left; width: 340px; height: 24px; margin: 0px; padding: 2px 4px; font-weight: 550; background: #FFF;" id="select_tiporeportes" onchange="mostrar_reporte(this.value);" disabled>
          <option value="">Seleccione</option>
        </select>
        @endif




        <button class="btn btn-light" style="margin-left: 50%;" type="button" id="btnPoeProyecto" data-toggle="tooltip" title="Población Ocupacionalmente Expuesta" disabled> <i class="fa fa-users" aria-hidden="true"></i> POE Proyecto</button>
        <button class="btn btn-light" style="margin-left: 1%;" type="button" id="btnMatriz" data-toggle="tooltip" title="Matriz de Exposición Laboral" disabled> <i class="fa fa-table" aria-hidden="true"></i> MEL Proyecto</button>
        <button class="btn btn-light" style="margin-left: 1%;" type="button" id="btnmatrizreco" data-toggle="tooltip" title="Matriz de recomendaciones" disabled> <i class="fa fa-table" aria-hidden="true"></i> MR Proyecto</button> -->

        <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">

          <!-- IZQUIERDA: Título + Select -->
          <div style="display: flex; align-items: center; gap: 15px;">
            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
            <h4 class="text-white card-title" style="margin: 0;">
              Informes y entregables
            </h4>

            <select class="custom-select"
              style="width: 340px; height: 24px; padding: 2px 4px; font-weight: 550; background: #FFF;"
              id="select_tiporeportes"
              onchange="mostrar_reporte(this.value);" disabled>
              <option value="">Seleccione</option>
            </select>
            @endif
          </div>

          <!-- DERECHA: Botones -->
          <div style="display: flex; align-items: center; gap: 10px;">
            <button class="btn btn-light" type="button" id="btnPoeProyecto"
              data-toggle="tooltip" title="Población Ocupacionalmente Expuesta" disabled>
              <i class="fa fa-users"></i> POE Proyecto
            </button>

            <button class="btn btn-light" type="button" id="btnMatriz"
              data-toggle="tooltip" title="Matriz de Exposición Laboral" disabled>
              <i class="fa fa-table"></i> MEL Proyecto
            </button>

            <button class="btn btn-light" type="button" id="btnmatrizreco"
              data-toggle="tooltip" title="Matriz de recomendaciones" disabled>
              <i class="fa fa-table"></i> MR Proyecto
            </button>
          </div>
        </div>



      </div>
      <div class="card-body" style="border: 0px #f00 solid; min-height: 856px!important;" id="estructura_reporte">
        <p style="text-align: center; font-size: 24px;" id="estatusInformes"></p>
      </div>

    </div>
  </div>
</div>




@endsection
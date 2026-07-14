<!-- sample modal content -->

<div id="madal-editar-participantes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-2">

                <form id="frm_editar_participantes" name="frm_cargar_participantes">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="txt-identificacion" class="col-form-label">Identificación</label>
                            <input type="txt" class="form-control" id="txt_identificacion" name="txt_identificacion" readonly style="background-color: #eaeaea;">
                            <input type="hidden" class="form-control" id="txt_id" name="txt_id">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="txt-nombres" class="col-form-label">Nombres</label>
                            <input type="text" class="form-control" id="txt_nombres" name="txt_nombres">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="txt-apellidos" class="col-form-label">Apellidos</label>
                            <input type="text" class="form-control" id="txt_apellidos" name="txt_apellidos">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="txt-bono" class="col-form-label">Bono</label>
                            <input type="text" class="form-control" id="txt_bono" name="txt_bono">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="txt-perfil" class="col-form-label">Perfil</label>
                            <input type="text" class="form-control" id="txt_perfil" name="txt_perfil" readonly style="background-color: #eaeaea;">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="txt-empresa" class="col-form-label">Empresa</label>
                            <input type="text" class="form-control" id="txt_empresa" name="txt_empresa" readonly style="background-color: #eaeaea;">
                        </div>
                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" id="cerrar_modal" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                <button type="submit" id="btn-editar-participantes" class="btn btn-secondary waves-effect waves-light">Editar</button>
            </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->
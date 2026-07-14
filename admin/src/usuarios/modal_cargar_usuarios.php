<!-- sample modal content -->

<div id="madal-cargar-usuaios" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cargar Usuarios</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">

                <form id="frm_cargar_usuarios" name="frm_cargar_usuarios">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-3" class="control-label">Cargar Archivo</label>
                                <input class="form-control" type="file" id="fileDatos" name="cargarDatos">
                            </div>
                        </div>
                    </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                <button type="submit" id="btn_cargar_usuarios" class="btn btn-info waves-effect waves-light">Cargar Usuarios</button>
            </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->
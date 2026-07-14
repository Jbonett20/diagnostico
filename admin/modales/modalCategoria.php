<!-- Modal -->
<div class="modal fade" id="mod-cat" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content w-75">
           
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Editar categoría</h5>
                <button type="button" class="btn-close" id="close-modal-button" data-bs-dismiss="modal" aria-label="Close" style="background-color: transparent; border: none; position: absolute; top: 10px; right: 10px; font-size: 1.5rem; color: #333;">&times;</button>
            </div>
            <div class="modal-body p-4">
                <form id="form-cat">
                    <div class="form-outline mb-4">
                        <input type="hidden" id="categoria_id" name="categoria_id" class="form-control" />
                    </div>
                    <div class="form-outline mb-4">
                        <input type="hidden" id="perfil_id" name="perfil_id" class="form-control" />
                    </div>
                    <div class="form-outline mb-4">
                        <input type="hidden" id="evento_id" name="evento_id" class="form-control" value="<?php if (isset($_GET['eventoId']) && !empty($_GET['eventoId'])) {
                                                                                                                                            echo $_GET['eventoId'];
                                                                                                                                        }?>" >
                    </div>
                    <div class="form-outline mb-4">
                        <label class="form-label" for="cat_nombre">Nombre categoría</label>
                        <input type="text" id="cat_nombre" name="cat_nombre" class="form-control" />
                    </div>
                    <div class="form-outline mb-4">
                        <label class="form-label" for="cat_porcentaje">Porcentaje</label>
                        <input type="number" id="cat_porcentaje" name="cat_porcentaje" class="form-control" />
                        <div id="cat_porcentaje-error" style="display: none; color: red;">Por favor, ingrese un porcentaje entero entre 1 y 100.</div>
                    </div>
                    <div class="col-12 text-right mt-3">
                        <button type="button" class="btn btn-secondary btn-xs btn-min" onclick="editarCategoria()">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->

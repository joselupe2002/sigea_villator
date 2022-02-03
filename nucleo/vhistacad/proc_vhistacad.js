
function verDetalleCal(modulo,usuario,institucion, campus,essuper){
    table = $("#G_"+modulo).DataTable();
    dameVentanaDetUnidad("grid_vhistacad",table.rows('.selected').data()[0]["ID"],
    table.rows('.selected').data()[0]["MATRICULA"],
    table.rows('.selected').data()[0]["NOMBRE"],
    table.rows('.selected').data()[0]["MATERIA"], 
    table.rows('.selected').data()[0]["MATERIAD"]);
}
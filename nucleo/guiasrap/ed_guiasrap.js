


function mostrarico(){
	script="<div class=\"modal fade\" id=\"modalDocument\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\"> "+
    "   <div class=\"modal-dialog modal-lg \" role=\"document\">"+
	   "      <div class=\"modal-content\">"+
	   "          <div class=\"modal-header\">"+
	   "             <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Cancelar\">"+
	   "                  <span aria-hidden=\"true\">&times;</span>"+
	   "             </button>"+
	   "             <div class=\"row\"> "+			
       "                 <div class=\"col-sm-12\"> "+			   
	    "                       <div class=\"widget-box widget-color-green2\"> "+
		"                              <div class=\"widget-header\">"+
		"	                                <h4 class=\"widget-title lighter smaller\">Iconos</h4>"+
		"                              </div>"+
		"                              <div style=\"overflow-y: auto;height:200px;width:100%;\">"+
		"		                                   <ul id=\"tree\"></ul>"+
		"                              </div>"+
	    "                       </div>"+
	    "                 </div>"+
	    "             </div>"+	    
       "          </div>"+
	   "      </div>"+
	   "   </div>"+
	   "</div>";
	   $("#modalDocument").remove();
	   
	    if (! ( $("#modalDocument").length )) { $("body").append(script);}
	    

	    $('#modalDocument').modal({show:true});
	    
	elsql="SELECT nombre, nombre, icon FROM ICONOS order by 1";  
	parametros={sql:elsql,dato:sessionStorage.co,bd:"SQLite",sel:'0',tipo:"tree"}

	$.ajax({
		type: "POST",
		data:parametros,
        url: 'dameselectima.php', 
        success: function(data){  
        	$("#tree").html(data);
     },
     error: function(data) {
        alert('ERROR: '+data);
     }
   }); 
}

function ICONOclicadd(){
	elemento="ICONO";
	mostrarico();
}

function elegirima(valor){
	$("#"+elemento).val("menu-icon blue "+valor);
	$('#modalDocument').modal("hide"); 
}

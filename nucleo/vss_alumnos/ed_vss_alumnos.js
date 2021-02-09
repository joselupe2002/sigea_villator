

function changeCALIFICACIONL(elemento, usuario, institucion, campus){
    
 $("#CALIFICACION2").val($("#CALIFICACIONL option:selected").text().split("-")[0]);
 $('#CALIFICACION2').prop('disabled', 'disabled');

}

function calculaCalif(){
    var prom=60;
    var cal1=((parseFloat($("#REP1EVAL").val())*0.9)+(parseFloat($("#REP1AUTO").val())*0.1)).toFixed(2);
    var cal2=((parseFloat($("#REP2EVAL").val())*0.9)+(parseFloat($("#REP2AUTO").val())*0.1)).toFixed(2);
    var cal3=((parseFloat($("#REP3EVAL").val())*0.9)+(parseFloat($("#REP3AUTO").val())*0.1)).toFixed(2);
    var cal4=((parseFloat($("#REPFEVAL").val())*0.9)+(parseFloat($("#REPFAUTO").val())*0.1)).toFixed(2);
    console.log(cal1+"+"+cal2+"+"+cal3+"+"+cal4);
    
    
    var eval=((parseFloat($("#REP1EVAL").val()))+(parseFloat($("#REP2EVAL").val()))+(parseFloat($("#REP3EVAL").val()))+(parseFloat($("#REPFEVAL").val())))/4;
    var auto=((parseFloat($("#REP1AUTO").val()))+(parseFloat($("#REP2AUTO").val()))+(parseFloat($("#REP3AUTO").val()))+(parseFloat($("#REPFAUTO").val())))/4;

    console.log("EVAL: "+eval+" AUTO:"+auto);
   // prom=((cal1+cal2+cal3)/3).toFixed(2);
   // prom2=((cal1+cal2+cal3)/3).toFixed(0);

    prom=((eval*0.9)+(auto*0.10)).toFixed(2);
    prom2=((eval*0.9)+(auto*0.10)).toFixed(0);


    if (isNaN(prom)) {prom=0;prom2=0;}
        
    $("#CALIFICACION").val(prom);
    $('#CALIFICACION').prop('disabled', 'disabled');
    elsql="SELECT CATA_DESCRIP FROM scatalogos where CATA_TIPO='CALIF_SS' AND  CATA_CLAVE='"+prom2+"'"+
          "ORDER BY CATA_DESCRIP ";
      
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
	    $.ajax({
			   type: "POST",
			   data:parametros,
			   url:  "../base/getdatossqlSeg.php",
	           success: function(data){  				      			             
                    $("#CALIFICACIONL").val(JSON.parse(data)[0][0]);
                    $("#CALIFICACION2").val(prom2);	
                    $('#CALIFICACIONL').prop('disabled', 'disabled');	
                    $('#CALIFICACION2').prop('disabled', 'disabled');																																												
			    }
	});	      	      			

    


    

}

function changeREP1EVAL(elemento, usuario, institucion, campus){
    calculaCalif();
   }

function changeREP1AUTO(elemento, usuario, institucion, campus){
    calculaCalif();
   }
   function changeREP2EVAL(elemento, usuario, institucion, campus){
    calculaCalif();
   }

function changeREP2AUTO(elemento, usuario, institucion, campus){
    calculaCalif();
   }
   function changeREP3EVAL(elemento, usuario, institucion, campus){
    calculaCalif();
   }

function changeREP3AUTO(elemento, usuario, institucion, campus){
    calculaCalif();
   }
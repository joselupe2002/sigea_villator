var todasColumnas;
var global,globalUni;
var abierto=false;
$(document).ready(function($) { var Body = $('body'); Body.addClass('preloader-site');});
$(window).load(function() {$('.preloader-wrapper').fadeOut();$('body').removeClass('preloader-site');});

$(document).ready(function($) { 
    cargarMaterias();
    });




function generaTabla(grid_data){
c=0;
ladefault="..\\..\\imagenes\\menu\\pdf.png";
$("#cuerpo").empty();
$("#tabHorarios").append("<tbody id=\"cuerpo\">");
jQuery.each(grid_data, function(clave, valor) { 	
        
    $("#cuerpo").append("<tr id=\"row"+valor.ID+"\">"); 
    $("#row"+valor.ID).append("<td><span class=\"badge badge-success\">"+valor.IDGRUPO+"</span></td>");  
    $("#row"+valor.ID).append("<td><span class=\"badge badge-primary\">"+valor.CICLO+"</span></td>");   	   
    $("#row"+valor.ID).append("<td>"+valor.GRUPO+"</td>");
    $("#row"+valor.ID).append("<td>"+valor.MATERIA+"</td>");
    $("#row"+valor.ID).append("<td>"+utf8Decode(valor.MATERIAD)+"</td>");
    $("#row"+valor.ID).append("<td>"+valor.PROFESOR+"</td>");
    $("#row"+valor.ID).append("<td>"+utf8Decode(valor.PROFESORD)+"</td>");
    if (valor.HIZO==0) {
    $("#row"+valor.ID).append("<td style= \"text-align: center;\" ><a  onclick=\"evaluar('"+valor.ID+"','"+valor.PROFESOR+"','"+
                               valor.MATERIA+"','"+valor.MATERIAD+"','"+valor.GRUPO+"','"+valor.CICLO+"','"+utf8Decode(valor.PROFESORD)+"','"+valor.IDGRUPO+"');\" title=\"Capturar actividades para la asignatura\" "+
            "class=\"btn btn-white btn-waarning btn-bold\">"+
            "<i class=\"ace-icon fa fa-check bigger-160 blue \"></i>"+
            "</a></td>");
    }
    else {
        $("#row"+valor.ID).append("<td style= \"text-align: center;\" >"+
        "<span class=\"badge badge-danger\"><i class=\"ace-icon fa fa-check bigger-160 white \"></i> Hecha</span>" +
        "</a></td>");
    }
});
$('#dlgproceso').modal("hide"); 

$('.fileSigea').ace_file_input({
    no_file:'Sin archivo ...',
    btn_choose:'Buscar',
    btn_change:'Cambiar',
    droppable:false,
    onchange:null,
    thumbnail:false, //| true | large
    whitelist:'pdf',
    blacklist:'exe|php'
    //onchange:''
    //
});

}		

function cargarMaterias() {
    $('#dlgproceso').modal({show:true, backdrop: 'static'});
    elsql="select CICLO,CICL_DESCRIP AS CICLOD, COUNT(*) AS HAY from ecortescal a, ciclosesc b where  "+
    " STR_TO_DATE(DATE_FORMAT(now(),'%d/%m/%Y'),'%d/%m/%Y') Between STR_TO_DATE(INICIA,'%d/%m/%Y')  "+
    " AND STR_TO_DATE(TERMINA,'%d/%m/%Y') and CLASIFICACION='EVALDOC'"+
    " and a.CICLO=b.CICL_CLAVE";

    parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
    $.ajax({
    type: "POST",
    data:parametros,
    url:  "../base/getdatossqlSeg.php",
    success: function(data){
        losdatos=JSON.parse(data);
        cad1="";cad2="";
        jQuery.each(losdatos, function(clave, valor) { cad1=valor.CICLO; cad2=valor.CICLOD; abierto=valor.HAY;});   
                $("#elciclo").html(cad1);
                $("#elciclod").html(cad2);


                sqlmater="select e.ID, e.ALUCTR as MATRICULA,e.IDGRUPO,e.PDOCVE AS CICLO, e.MATCVE AS MATERIA, f.MATE_DESCRIP AS MATERIAD, "+
                " e.GPOCVE AS GRUPO, e.LISTC15 as PROFESOR, concat(EMPL_NOMBRE,' ',EMPL_APEPAT,' ',EMPL_APEMAT) AS PROFESORD, "+
                " (select count(*) from ed_respuestasv2 where IDDETALLE=e.ID and MATRICULA='"+elusuario+"' and TERMINADA='S') HIZO "+
                " from dlista e, cmaterias f, pempleados g  where  e.LISTC15=g.EMPL_NUMERO and e.MATCVE=f.MATE_CLAVE "+
                " AND e.ALUCTR='"+elusuario+"' and e.PDOCVE="+cad1+" and e.BAJA='N' and LISTC15<>9999";
                parametros={sql:sqlmater,dato:sessionStorage.co,bd:"Mysql"}

                if (abierto>0) { 
                        $.ajax({
                            type: "POST",
                            data:parametros,
                            url:  "../base/getdatossqlSeg.php",
                            success: function(data){        	    
                                    generaTabla(JSON.parse(data));	   
                                    $('#dlgproceso').modal("hide");      	     
                                },
                            error: function(data) {	                  
                                    alert('ERROR: '+data);
                                    $('#dlgproceso').modal("hide");  
                                }
                        });
                    }
                else 
                    {   $("#tabHorarios").empty();
                        $("#tabHorarios").append("<span class=\"text-danger bogger-130\"> No esta abierto el periodo de Evaluación Docente</span>");
                        $('#dlgproceso').modal("hide"); }
                                
        },
    error: function(data) {	                  
            alert('ERROR: '+data);
            $('#dlgproceso').modal("hide");  
        }
    });

}



function evaluar(id,profesor,materia,materiad,grupo,ciclo,profesord,idgrupo){
      window.location="evalDocv2.php?idgrupo="+idgrupo+"&id="+id+"&ciclo="+ciclo+"&profesord="+profesord+"&profesor="+profesor+"&grupo="+grupo+"&materia="+materia+"&materiad="+materiad;
}





function imprimirAcuse() {
    

    elsql="SELECT COUNT(*) as HAY FROM ( "+
        "select (select count(*) from ed_respuestasv2 h where h.`MATRICULA`=e.ALUCTR"+
        " AND h.CICLO=PDOCVE AND h.MATERIA=MATCVE AND TERMINADA='S') AS ESTA"+
        " from dlista e"+
        " where e.ALUCTR='"+elusuario+"' and e.PDOCVE='"+ $("#elciclo").html()+"' and e.BAJA='N' and LISTC15<>9999) u"+
        " WHERE ESTA=0";
    
    parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
    
    $.ajax({
        type: "POST",
        data:parametros,
        url:  "../base/getdatossqlSeg.php",
        success: function(data){
            losdatos=JSON.parse(data);
            if (losdatos[0]["HAY"]==0) {
                enlace="nucleo/pa_evaldocv2/acuse.php?mat="+elusuario+"&ciclo="+ $("#elciclo").html();
                abrirPesta(enlace,"Acuse");
            }
            else {
                alert ("Para poder imprimir su ACUSE debe evaluar todas sus asignaturas")
            }
        }
    });

    
}
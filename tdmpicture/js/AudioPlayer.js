/**
 * ****************************************************************************
 *  - TDMPicture By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - Licence PRO Copyright (c)  (http://www.tdmxoops.net)
 *
 * Cette licence, contient des limitations!!!
 *
 * 1. Vous devez posséder une permission d'exécuter le logiciel, pour n'importe quel usage.
 * 2. Vous ne devez pas l' étudier,
 * 3. Vous ne devez pas le redistribuer ni en faire des copies,
 * 4. Vous n'avez pas la liberté de l'améliorer et de rendre publiques les modifications
 *
 * @license     TDMFR PRO license
 * @author		TDMFR ; TEAM DEV MODULE 
 *
 * ****************************************************************************
 */

 var nbrFichiers = 0;
 
    //var releaseClick;
    function gObj(nom){
		if (navigator.appName.indexOf("Microsoft")!=-1){
			return window[nom];
		} else  { 
			return document.getElementById(nom);
		}
	}
	
 function checkHover() {
	if (obj) {
		obj.fadeOut('fast');
	} //if
} //checkHover


var $tdmpicture = jQuery.noConflict();
var obj = null;


//multiupload

function getFileName(fileName)
{
  if (fileName != "") {
    if (fileName.match(/^(\\\\|.:)/)) {
      var temp = new Array();
      temp = fileName.split("\\");
      var len = temp.length;
      fileName = temp[len-1];
    } else {
      temp = fileName.split("/");
      var len = temp.length;
      if(len>0)
        fileName = temp[len-1];
    }
  }  
  return fileName;
}


$tdmpicture(document).ready( function() {

    //Création d'un premier input
   //creerInput();

	$tdmpicture(".tdmimg").hover(function(){
		//$tdmpicture("#menuimg").slideDown('fast');
		$tdmpicture(this).children("span").slideDown('fast');
		obj = null;
	}, function() {
		obj = $tdmpicture(this).children("span").slideDown('fast');
		setTimeout( "checkHover()", 400);
	});
	
	
	}); 
	
/*creer input*/
function creerInput()
{

$tdmpicture("<input>", {
	name: "tdmfile[]",
	type: "file",
	change: function(){
	creerLink(this);
    creerInput(this);
	$tdmpicture(this).fadeOut("slow");
	setTimeout(function(){ 
	$tdmpicture(this).hide();
	
	},
	5000);	
  }
}).appendTo("#tdmfiletext");
//$tdmpicture("#tdmfiletext").append( getFileName($tdmpicture(this).val()));
}	

/* creer link */
//submit save
function creerLink(link) 
{ // bind click event to link
  
 if(nbrFichiers == $tdmpicture('#tdmfiletext').attr('maxlength')) {
alert('stop');
$tdmpicture(this).remove();
		} else {

   //Création de la ligne dans la liste des fichiers à uploader
    var fichier = getFileName($tdmpicture(link).val());
    
	//masque l'input//
	$tdmpicture(link).hide();
	//creer element jquery
$tdmpicture("<div/>", {
"id": fichier,
  "class": "test",
  "css" : { 
"background" : "url(images/cross.png) no-repeat",
"padding-left": "20px",
"height": "20px",
	"top": "-5px",
	"left": "-2px"
} ,
  "text": fichier,
  click: function(){	
  nbrFichiers--;
    //$tdmpicture(this).toggleClass("test");
	$tdmpicture(this).fadeOut("slow");
	setTimeout(function(){ 	 
	$tdmpicture(this).remove();
	$tdmpicture(link).remove();

	},
	100);	
  }
}

).appendTo("#tdmfiletext");
  nbrFichiers++;
}
}

/* Plalist  */
function swith()
{

		var answer = $tdmpicture("#display").attr("class");

        if (answer == 'display thumb_view')  {
        $tdmpicture ("ul.display").fadeOut("fast", function() {
	  	$tdmpicture (this).fadeIn("fast").removeClass("thumb_view");
	
		 });
		 
		 display = 'display';
					
         } else {
        $tdmpicture ("ul.display").fadeOut("fast", function() {
	  	$tdmpicture (this).fadeIn("fast").addClass("thumb_view");
		

		}); 		
		 display = 'display thumb_view';
		}
		
    // Utilisation d'Ajax / jQuery pour session
     $tdmpicture.ajax({
       type: "POST",
       url: "./include/jquery.php",
	   data: "op=cookie&display="+display,
     });

    // Nous retournons "false" au navigateur afin que la page ne soit pas actualisé

}


function RemovePlayList(id)
{
    // Utilisation d'Ajax / jQuery pour l'envoie
     $.ajax({
       type: "POST",
       url: "./include/jquery.php",
	   data: "op=remove&pl_file="+id,
	   success: function(msg){
		alert(msg);
		
		//reload la playlist
		funct_PlayList();
	}
     });

    // Nous retournons "false" au navigateur afin que la page ne soit pas actualisé
    return false;
}



function AddPlayLists(id)
{
    // Utilisation d'Ajax / jQuery pour l'envoie
     $.ajax({
       type: "POST",
       url: "./include/jquery.php",
	   data: "op=adds&alb_id="+id,
	   success: function(msg){
		alert(msg);
		
	}
     });

    // Nous retournons "false" au navigateur afin que la page ne soit pas actualisé
    return false;
}

/* ajoute lecture d'un fichier  */
function AddHits(id)
{

//trouver le moyen de le faire depuis la fonction update du lecteur
    // Utilisation d'Ajax / jQuery pour l'envoie
     $.ajax({
       type: "POST",
       url: "./include/jquery.php",
	   data: "op=hits&file_id="+id,
	   	success: function(msg){
	}
     });

    // Nous retournons "false" au navigateur afin que la page ne soit pas actualisé
    return false;
}

/* ajoute un album pour la lecture  */
function AddPlays(id)
{

//trouver le moyen de le faire depuis la fonction update du lecteur
    // Utilisation d'Ajax / jQuery pour l'envoie
     $.ajax({
       type: "POST",
       url: "./include/jquery.php",
	   data: "op=plays&alb_id="+id,
	   	success: function(msg){
		var $tabs = $('#tabs').tabs();
		$tabs.tabs('select', 2);
		$('#TDMPicture2').html(msg);
		
	//reint le lecteur
	currTab = "#TDMPicture2";
	initShowUpPlayer(currTab);
	PlayList('play', currTab);
			
	}
     });

    // Nous retournons "false" au navigateur afin que la page ne soit pas actualisé
    return false;
}



function AddVote(id)
{
var $tdmpicture = jQuery.noConflict();
//trouver le moyen de le faire depuis la fonction update du lecteur
    // Utilisation d'Ajax / jQuery pour l'envoie
     $tdmpicture.ajax({
       type: "POST",
       url: "./include/jquery.php",
	 data: "op=addvote&vote_id="+id,
	   success: function(msg){
	   alert(msg);
	}
     });

    // Nous retournons "false" au navigateur afin que la page ne soit pas actualisé
    return false;
}

function RemoveVote(id)
{
var $tdmpicture = jQuery.noConflict();
//trouver le moyen de le faire depuis la fonction update du lecteur
    // Utilisation d'Ajax / jQuery pour l'envoie
     $tdmpicture.ajax({
       type: "POST",
       url: "./include/jquery.php",
	 data: "op=removevote&vote_id="+id,
	   success: function(msg){
		alert(msg);
	}
     });

    // Nous retournons "false" au navigateur afin que la page ne soit pas actualisé
    return false;
}

function masque(id) {

   	var $tdmpicture = jQuery.noConflict();
	
	$tdmpicture(document).ready(function(){

	 if ($tdmpicture("#masque_" +id+ ":visible").length != 0) {
		$tdmpicture("#masque_" +id).fadeOut("fast", function() {
            $tdmpicture("#masque_" +id).fadeIn("fast").hide();
        });
   
    } else {	    
	$tdmpicture("#masque_" +id).fadeOut("fast", function() {
         $tdmpicture("#masque_" +id).fadeIn("fast").show();
     });
}

});

}
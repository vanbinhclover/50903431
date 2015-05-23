/* - - - - - - - - - - - - - - - - - - - - - - -

Online Clinic Javascript

 - - - - - - - - - - - - - - - - - - - - - - - */

var ie45,ns6,ns4,dom;

if (navigator.appName=="Microsoft Internet Explorer") ie45=parseInt(navigator.appVersion)>=4;

else if (navigator.appName=="Netscape"){  ns6=parseInt(navigator.appVersion)>=5;  ns4=parseInt(navigator.appVersion)<5;}

dom=ie45 || ns6;

var http=createRequestObject();

var objectId = '';

var anum=/(^\d+$)/



function createRequestObject(htmlObjectId){

    var obj;

    var browser = navigator.appName;

    

    if(browser == "Microsoft Internet Explorer"){

        obj = new ActiveXObject("Microsoft.XMLHTTP");

    }

    else{

        obj = new XMLHttpRequest();

    }

    return obj;    

}



function sendReq(serverFileName, variableNames, variableValues,objId) {

    var paramString = '';

    

    objectId = objId;

    variableNames = variableNames.split(',');

    variableValues = variableValues.split(',');

    

    for(i=0; i<variableNames.length; i++) {

        paramString += variableNames[i]+'='+variableValues[i]+'&';

    }

    paramString = paramString.substring(0, (paramString.length-1));

            

    if (paramString.length == 0) {

        http.open('get', serverFileName);

    }

    else {

        http.open('get', serverFileName+'?'+paramString);

    }

    http.onreadystatechange = handleResponse;

    http.send(null);

}



function handleResponse() {

    

    if (http.readyState == 4) {

        responseText = http.responseText;

        getobj(objectId).innerHTML = responseText;

    } else {

        getobj(objectId).innerHTML = "<div align=center style='padding:3px'><img src='images/wait.gif' border=0></div>";

    }

        

}



function change_icon(imgDocID,url) {

document.images[imgDocID].src = url;

}  



function showhide(id) {

el = document.all ? document.all[id] :   dom ? document.getElementById(id) :   document.layers[id];

els = dom ? el.style : el;

  if (dom){

    if (els.display == "none") {

        els.display = "";

      } else {

        els.display = "none";

      }

    }

  else if (ns4){

    if (els.display == "show") {

        els.display = "hide";

      } else { 

      els.display = "show";

         }

  }

}



function getobj(id) {

el = document.all ? document.all[id] :   dom ? document.getElementById(id) :   document.layers[id];

return el;

}



function showobj(id) {

obj=getobj(id);

els = dom ? obj.style : obj;

 	if (dom){

	    els.display = "";

    } else if (ns4){

        els.display = "show";

  	}

}



function hideobj(id) {

obj=getobj(id);

els = dom ? obj.style : obj;

 	if (dom){

	    els.display = "none";

    } else if (ns4){

        els.display = "hide";

  	}

}



function MM_openBrWindow(theURL,winName,features) { //v2.0

  window.open(theURL,winName,features);

}



function openPopUp(url, windowName, w, h, scrollbar) {

           var winl = (screen.width - w) / 2;

           var wint = (screen.height - h) / 2;

           winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scrollbar ;

		   win = window.open(url, windowName, winprops);

           if (parseInt(navigator.appVersion) >= 4) { 

              	win.window.focus(); 

           } 

}



function gotopage(query){	

	var url = "?cmd=act:"+query;

	document.location = url;

}



function loadimages(url) {

	imageover=new Image;

	imageover.src=url;

}



function showsendfriend(id,cat) {

	openPopUp("SendFriend.php?cat="+cat+"&amp;id="+id, "SendFriend", 500, 500, "yes");

}



function showprint(id,cat) {

	openPopUp("Print.php?cat="+cat+"&amp;id="+id, "Print", 700, 600, "yes");

}



function Xem_Chuong(id,chuong) {
	scroll(0,100);
    var vvalue = 'act:TruyenDai|id:'+id+'|chuong:'+chuong;

    sendReq('ndkajax.php','cmd', vvalue,'noidung_chuong');

}



function getVarChuong(){

var url;

var act;

	url=window.location.href;

	url=url+'#';

	url=url.split('#');

	url=url[1];

	url=url+'_';

	url=url.split('_');

	tid=url[0];

	act=url[1];

	url=url[2];

if ((act=='Chuong')&&(url!='')) {

	Xem_Chuong(tid,url)

	}

}
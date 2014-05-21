// Evento para ajustar el tamaño del iframe al contenido que se muestra
function iframeLoaded() 
{
	var iFrameID = document.getElementById('idIframe');
	if(iFrameID) {
   		// Se reinicia el tamaño y se asigna el nuevo
    	iFrameID.height = "";
    	iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + "px";
	}   
}

<script type="text/javascript">
<!--
    //Funções javascript
	
	var GLArray = new Array();
	

	
	
	function popup(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'Gráfico','dependent=yes,width=800,height=600,scrollbars=no,statusbar=no,resizable=no');
      	x.moveTo(10,10);
	  	
		return false
     }

	function popupWH(pagina,larg,altur)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'Gráfico','dependent=yes,width='+(larg+20)+',height='+(altur+20)+',scrollbars=no,statusbar=no,resizable=no');
      	x.moveTo(10,10);
	  	
		return false
     }


	 function popup_alerta(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'_blank','dependent=yes,width=600,height=400,scrollbars=yes,statusbar=no,resizable=yes');
      	
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		//x.moveTo(100,100);
	  	
		return false
     }
	
	 function mini_popup(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'_blank','dependent=yes,width=400,height=250,scrollbars=yes,statusbar=no,resizable=yes');
      	
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		//x.moveTo(100,100);
	  	
		return false
     }




	function popup_alerta_wide(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'_blank','dependent=yes,width=800,height=400,scrollbars=yes,statusbar=no,resizable=yes');
      	
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		//x.moveTo(100,100);
	  	
		return false
     }
	 
	 
	function mensagem(msg){
		alert(msg);
		return false
	}
	 
	 
	 function redirect(url){
		window.location.href=url;
	 }
	 
	
	
	//criar acesso ao submit de excluir
	function confirma(msg,url){
		if (confirm(msg)){
			redirect(url);
		}
	}


	function confirmaAcao (msg, url, param){ //variavel php
		if (confirm(msg)){
			url += '?'+param;
			redirect(url);
		} 
		return false;
	}
	
	
	
	
	
	
	function cancelLink () {
	  return false;
	}
	function disableLink (link) {
	  if (link.onclick)
		link.oldOnClick = link.onclick;
	  link.onclick = cancelLink;
	  if (link.style)
		link.style.cursor = 'default';
	}
	function enableLink (link) {
	  link.onclick = link.oldOnClick ? link.oldOnClick : null;
	  if (link.style)
		link.style.cursor = 
		  document.all ? 'hand' : 'pointer';
	}
	function toggleLink (link) {
	  if (link.disabled) 
		enableLink (link)
	  else 
		disableLink (link);
	  link.disabled = !link.disabled;
	}
	
	function desabilitaLinks(permissao){
		if (permissao!=1) {
			for (i=0; i<(document.links.length); i++) {
				toggleLink (document.links[i]);
			}
		}
	}

	
		function destaca_BKP(id){
			var obj = document.getElementById(id);
			obj.style.background = '#D5D5D5';//C7C8C6
		}			
		
		function libera_BKP(id){
			var obj = document.getElementById(id);
			if (obj.style.background != '#FFCC99') {
				obj.style.background = '';
			}
		}					

		
		function marca_BKP(id){
			var obj = document.getElementById(id);
			if (obj.style.background == '#FFCC99') {
				obj.style.background = '';
			} else
				obj.style.background = '#FFCC99';
		}					

				function par(n) {
				 var na = n;
				 var nb = (na / 2);
				  nb = Math.floor(nb);
				  nb = nb * 2;
				 if ( na == nb ) { return(1) } else { return(0) }
				}
				
				function corNaturalOLD(id) {//F8F8F1
				 var obj = document.getElementById(id);
				 if( par(id.charAt(id.length-1)) == 1 ) { obj.style.background = '#EAE6D0' } else { obj.style.background = '#F8F8F1' }
				}		

                function corNatural(id) {//F8F8F1
					var obj = document.getElementById(id);
					//obj.style.background = obj.getAttributeNode('cN').value; /* Para ser usado lendo propriedade cN='cor' do objeto */
					if (navigator.userAgent.indexOf('MSIE') !=-1){ //IE
						var classe = obj.getAttributeNode('class').value;
					} else
						var classe ='';	

					if ( classe != '') {  
						if ( classe == 'lin_par'  ) {  obj.style.background = '#EAE6D0' } else //'#EAE6D0' //
						if ( classe == 'lin_impar' ) { obj.style.background = '#F8F8F1' } //'#F8F8F1'
					} 
						else { obj.style.background = '' } 
                }        
		
		
		
		function destaca(id){
			if ( verificaArray('', id) == false ) {
				var obj = document.getElementById(id);
				obj.style.background = '#D5D5D5';//C7C8C6
			}
		}			
		
		function libera(id){
			if ( verificaArray('', id) == false ) {
				var obj = document.getElementById(id);
				//obj.style.background = '';
				corNatural(id); /* retorna à cor natural */
			}
		}					

		
		function marca(id){
			var obj = document.getElementById(id);
			
			if ( verificaArray('', id) == false ) {
				verificaArray('marca', id)
				
				obj.style.background = '#FFCC99';
			} else {
				verificaArray('desmarca', id)
				//obj.style.background = '';
				destaca(id);
			}
			
		}		
		
		function verificaArray(acao, id) {
			var i;
			var tamArray = GLArray.length;
			var existe = false;
			
			for(i=0; i<tamArray; i++) {
				if ( GLArray[i] == id ) {
					existe = true;
					break;
				}
			}
			
			if ( (acao == 'marca') && (existe==false) ) {
				GLArray[tamArray] = id;
			} else if ( (acao == 'desmarca') && (existe==true) ) {
				var temp = new Array(tamArray-1); //-1
				var pos = 0;
				for(i=0; i<tamArray; i++) {
					if ( GLArray[i] != id ) {
						temp[pos] = GLArray[i];
						pos++;
					}
				}
				
				GLArray = new Array();
				var pos = temp.length;
				for(i=0; i<pos; i++) {
					GLArray[i] = temp[i];
				}
			}
			
			return existe;
		}

		
function validaForm(id,tipo,campo,obrigatorio){
	var regINT = /^[1-9]\d*$/; //expressão para validar numeros inteiros não iniciados com zero
	var regINTFULL = /^\d*$/; //expressão para validar numeros inteiros quaisquer
	var regDATA = /^((0?[1-9]|[12]\d)\/(0?[1-9]|1[0-2])|30\/(0?[13-9]|1[0-2])|31\/(0?[13578]|1[02]))\/(19|20)?\d{2}$/;
	var regDATA_ = /^((0?[1-9]|[12]\d)\-(0?[1-9]|1[0-2])|30\-(0?[13-9]|1[0-2])|31\-(0?[13578]|1[02]))\-(19|20)?\d{2}$/;
	var regEMAIL = /^[\w!#$%&'*+\/=?^`{|}~-]+(\.[\w!#$%&'*+\/=?^`{|}~-]+)*@(([\w-]+\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/;
	var regMOEDA = /^\d{1,3}(\.\d{3})*\,\d{2}$/;
	var regMOEDASIMP = /^\d*\,\d{2}$/;
	var regETIQUETA = /^[1-9]\d*(\,\d+)*$/; //expressão para validar consultas separadas por vírgula;
	var regALFA = /^[A-Z]|[a-z]([A-Z]|[a-z])*$/;
	var regALFANUM = /^([A-Z]|[a-z]|[0-9])([A-Z]|[a-z]|[0-9])*\.?([A-Z]|[a-z]|[0-9])([A-Z]|[a-z]|[0-9])*$/; //Valores alfanumérias aceitando separação com no máximo um ponto.
	
	//var d = document.cadastro;
	
	var obj = document.getElementById(id);
	var valor = obj.getAttributeNode('name').value;
	
	//alert (obj); 
	
	//verificar se está preenchido
	
	
	if ((obj.value == "")&&(obrigatorio==1)){
		alert("O campo " + campo + " deve ser preenchido!");
		obj.focus();
		return false;
	} 
         

	
	if ((tipo == "INTEIRO")&&(obj.value != "")) {
		//validar dados numéricos
		if (!regINT.test(obj.value)){
			alert ("O campo "+ campo +" deve conter apenas numeros inteiros não iniciados por ZERO!");
			obj.focus();
			return false;
		}
	} else
	
	if ((tipo == "COMBO")&&(obj.value != "")) {
		//validar dados numéricos
		if (!regINT.test(obj.value)){
			alert ("O campo "+ campo +" deve ser selecionado!");
			obj.focus();
			return false;
		}
	} else	
	
	if ((tipo == "INTEIROFULL")&&(obj.value != "")) {
		//validar dados numéricos
		if (!regINTFULL.test(obj.value)){
			alert ("O campo "+ campo +" deve conter apenas numeros inteiros!");
			obj.focus();
			return false;
		}
	} else	
	
	if ((tipo == "DATA")&&(obj.value != "")) {
		//validar data
		if (!regDATA.test(obj.value)){
			alert("Formato de data invalido! dd/mm/aaaa");
			obj.focus();
			return false;
			}
	} else
	
	if ((tipo == "DATA-")&&(obj.value != "")) {
		//validar data
		if (!regDATA_.test(obj.value)){
			alert("Formato de data invalido! dd-mm-aaaa");
			obj.focus();
			return false;
			}
	} else	
	
	if ((tipo == "EMAIL")&&(obj.value != "")){
		//validar email(verificao de endereco eletrônico)
		if (!regEMAIL.test(obj.value)){
			alert("Formato de e-mail inválido!");
			obj.focus();
			return false;
		}
	} else
	
	if ((tipo == "MOEDA")&&(obj.value != "")){
		//validar valor monetário
		if (!regMOEDA.test(obj.value)){
			alert("Formato de moeda inválido!");
			obj.focus();
			return false;
		}
	} else
	
	if ((tipo == "MOEDASIMP")&&(obj.value != "")){
		//validar valor monetário
		if (!regMOEDASIMP.test(obj.value)){
			alert("Formato de moeda inválido! XXXXXX,XX");
			obj.focus();
			return false;
		}
	} else	
	
	if ((tipo == "ETIQUETA")&&(obj.value != "")){
		//validar valor monetário
		if (!regETIQUETA.test(obj.value)){
			alert("o Formato deve ser de valores inteiros não iniciados por Zero e separados por vírgula!");
			obj.focus();
			return false;
		}
	}	else
	
	if ((tipo == "ALFA")&&(obj.value != "")){
		//validar valor monetário
		if (!regALFA.test(obj.value)){
			alert("Esse campo só aceita carateres do alfabeto sem espaços!");
			obj.focus();
			return false;
		}
	}	else
	
	if ((tipo == "ALFANUM")&&(obj.value != "")){
		//validar valor monetário
		if (!regALFANUM.test(obj.value)){
			alert("Esse campo só aceita valores alfanuméricos sem espaços ou separados por um ponto(no máximo um)!");
			obj.focus();
			return false;
		}
	}					
	
	
         return true;
}

	function exibeEscondeImg(obj) {
		var item = document.getElementById(obj);
		if (item.style.display=='none'){
			item.style.display='';
		} else {
			item.style.display='none';
		}
	}

				
		
//-->	
</script>
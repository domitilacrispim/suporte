


<?
include ("../../includes/functions/funcoes.inc");
include ("../../includes/javascript/funcoes.js");
	
//este programa realiza o cadastro na tabela agenda
if($_REQUEST['enviar']) 
{

 mysql_connect("localhost","ocomon",'3c5x9cfg') or die(mysql_error());
 mysql_query("use ocomon") or die("erro: " . mysql_error());
   
  
   $hoje = date("Y-m-d H:i:s");
   
   $acesso .= "<b> Aplicativos: </b>";
   foreach($_REQUEST["checkbox"] as $checkbox)
    {
        $acesso  .=  $checkbox. " - " ;
    }
    
	
	$contato = $_REQUEST['nome'];
	$setor  = $_REQUEST['setor']; 
    $telefone = $_REQUEST['telefone']; 
	$cargo = $_REQUEST['cargo'];
	$chapa = $_REQUEST['chapa'];
    $email  = $_REQUEST['email'];
	$teste = $_REQUEST['loc_id']; 
	
	$msg .= " <b> Nome: </b> $nome <br>";
	$msg .= " <b> Cargo: </b> $cargo <br>";
	$msg .= " <b> Siape/Chapa: </b> $chapa <br>";
	$msg .= $acesso;
	$msg .= " <br><b> Email: </b> $email <br>";
	$msg .= " <b> Ip:</b> $IP <br>";
	
	$descricao = $msg;
	
	$problema = 17;
	$sistema = 2;
	$data = $hoje;
	$status = 1;
	$instituicao = 2;
	$s_uid = 1;
	$local = $teste;
	$msg2 = "Aguarde Resposta do Suporte de Inform�tica";
	
	
   $sql = "INSERT INTO ocorrencias (problema, descricao, instituicao, equipamento, sistema, contato, telefone, local, operador, data_abertura, data_fechamento, status, data_atendimento, aberto_por ) 
   values ($problema,'$descricao',$instituicao,NULL,$sistema,'$contato','$telefone',$local,$s_uid,'$data',NULL,1,NULL,$s_uid)";
   mysql_query($sql);
   
  print "<script>mensagem('".$msg2."'); window.close();</script>";
   
}
else
{?>
<html>
<head>
<title>Envio de Mensagem</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<script>

 function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function leech(v){
    v=v.replace(/o/gi,"0")
    v=v.replace(/i/gi,"1")
    v=v.replace(/z/gi,"2")
    v=v.replace(/e/gi,"3")
    v=v.replace(/a/gi,"4")
    v=v.replace(/s/gi,"5")
    v=v.replace(/t/gi,"7")
    return v
}
 
 function soNumeros(v){
    return v.replace(/\D/g,"")
}
	
function Valida(){
		
		if(form1.nome.value == ""){
			alert("Informe seu nome por gentileza.");
			form1.nome.focus();return false;
			}

		if(form1.telefone.value == ""){
			alert("Informe seu ramal por gentileza.");
			form1.telefone.focus();return false;
			}
		
  }

</script>
</head>

<body onLoad="javascript:form1.nome.focus();">

  <form name="form1" action="newUser.php" onSubmit="return Valida();">
  <br>
  <table width="73%" border="0">
    <tr> 
      <td><div align="center"> <img src="../../includes/logos/hc.png" width="136" height="81"><br>
          <strong>Hospital de Cl&iacute;nicas de Uberl&acirc;ndia</strong> </div></td>
      <td><div align="center">
          <p><font face="Verdana" size="1"><strong>Para a Cria&ccedil;&atilde;o 
            de uma nova conta de acesso aos programas abaixo, </strong>por favor 
            preencha o formul&aacute;rio, que em breve <br>
            entraremos em contato para maiores informa&ccedil;&otilde;es. <br>
            Agradecemos desde j&aacute; a sua colabora��o!</font> 
          <p><font size="2" face="Verdana"><strong><font color="#000000" face="Arial, Helvetica, sans-serif">D&uacute;vidas: 
            Suporte de Inform&aacute;tica </font></strong></font> <font color="#000000" face="Arial, Helvetica, sans-serif"><strong><font size="2"><br>
            Ramal: 2322</font> </strong></font></div></td>
    </tr>
    <tr> 
      <td><div align="right">Nome Completo:</div></td>
      <td> <div align="left"> 
          <input name=nome type="text" size="60" maxlength="90" id="nome">
        </div></td>
    </tr>
    <tr> 
      <td><div align="right">Cargo:</div></td>
      <td><input name="cargo" type="text" size="30" maxlength="30"></td>
    </tr>
    <tr>
      <td><div align="right">Chapa / Siape:</div></td>
      <td><input name="chapa" type="text" size="20" maxlength="20"></td>
    </tr>
    <tr> 
      <td><div align="right">Ramal:</div></td>
      <td><div align="left"> 
          <input name="telefone" type="text" size="10" maxlength="4" id="inumeros" onkeypress="mascara(this,soNumeros)">
        </div></td>
    </tr>
    <tr> 
      <td><div align="right">Setor:</div></td>
      <td><div align="left"> 
          <? 
           mysql_connect("localhost","ocomon",'3c5x9cfg') or die(mysql_error());
           mysql_query("use ocomon") or die("erro: " . mysql_error());         											        
          
		  $sql = "select loc_id, local from localizacao order by local ";
          $res = mysql_query($sql);
          echo "<select  name = loc_id style='width:260'> <option> </option>";
          while($val = mysql_fetch_array($res)) 
           	{      
             echo "<option height='104' value=\"{$val['loc_id']}\">{$val['local']}</option>\n";
             }
             echo "</select>\n";
			 
                      ?>
        </div></td>
    </tr>
    <tr> 
      <td><div align="right">Email:</div></td>
      <td><div align="left"> 
          <input name="email" type="text" id="email" size="40" maxlength="40">
        </div></td>
    </tr>
    <tr> 
      <td valign="top"> <div align="right">Selecione os Aplicativos que deseja 
          ter acesso:</div></td>
      <td><div align="left"> 
          <table width="100%" border="1">
            <tr> 
              <td><font size="2"><b> SUPORTE INFORMATICA:</b>
                <table width="100%" border="0">
                  <tr> 
                    <td><input type="checkbox" name="checkbox[]" value="PowerPoint">
                      PowerPoint </td>
                    <td><input type="checkbox" name="checkbox[]" value="Word">
                      Word </td>
                    <td><input type="checkbox" name="checkbox[]" value="Excell">
                      Excell</td>
                    <td><input type="checkbox" name="checkbox[]" value="Vision">
                      Vision</td>
                  </tr>
                  <tr> 
                    <td><input type="checkbox" name="checkbox[]" value="Webmail">
                      Webmail</td>
                    <td><input type="checkbox" name="checkbox[]" value="PDF">
                      Leitor PDF </td>
                    <td><input type="checkbox" name="checkbox[]" value="Etiqueta">
                      Etiqueta </td>
                    <td><input type="checkbox" name="checkbox[]" value="Corel">
                      Corel Draw</td>
                  </tr>
                  <tr> 
                    <td><input type="checkbox" name="checkbox[]" value="Cracha">
                      Cracha</td>
                    <td><input type="checkbox" name="checkbox[]" value="APAC">
                      APAC</td>
                    <td><input type="checkbox" name="checkbox[]" value="CartaoSUS">
                      Cart�o SUS</td>
                    <td><input type="checkbox" name="checkbox[]" value="Winzip">
                      Winzip</td>
                  </tr>
                  <tr> 
                    <td><input type="checkbox" name="checkbox[]" value="Clear Canvas">
                      Clear Canvas</td>
                    <td><input type="checkbox" name="checkbox[]" value="CDRom">
                      CD-ROM</td>
                    <td><input type="checkbox" name="checkbox[]" value="Acesso Z:\">Acesso Z:\</td>
                    <td><input type="checkbox" name="checkbox[]" value="Acesso C:\">Acesso C:\</td>
                  </tr>
                </table>
                </td>
            </tr>
          </table>
          <table width="100%" border="1">
            <tr> 
              <td><font size="2">Para ter acesso a Internet &eacute; necessario 
                  a leitura e assinatura da documenta&ccedil;&atilde;o. Entregar 
                  no Suporte de Inform&aacute;tica.</font><br>
                <input type="checkbox" name="checkbox[]" value="Internet">
                Internet 
                
                </td>
            </tr>
          </table>
          <table width="100%" border="1">
            <tr> 
              <td> <font size="2"><b> SETOR DE DESENVOLVIMENTO:<br>
                </b>Para ter acesso aos programas abaixo &eacute; necessario levar 
                <a href="../../docs/MI-Nupro.doc">MI</a> com todos os recursos 
                que deseja acessar dentro dos sistemas. Entregar no Nupro Bloco: 
                2Y </font> 
                <table width="99%" border="0">
                  <tr>
                    <td><input type="checkbox" name="checkbox[]2" value="Sih">
                      Sih </td>
                    <td><input type="checkbox" name="checkbox[]4" value="Sysmat">
                      Sysmat </td>
                    <td><input type="checkbox" name="checkbox[]5" value="Siaf">
                      Siaf </td>
                    <td><input type="checkbox" name="checkbox[]12" value="Custo">
                      Custo </td>
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="checkbox[]6" value="Escala">
                      Escala </td>
                    <td><input type="checkbox" name="checkbox[]3" value="Contabilidade">
                      Contabilidade </td>
                    <td><input type="checkbox" name="checkbox[]11" value="Ocomon">
                      Ocomon </td>
                    <td><input type="checkbox" name="checkbox[]15" value="Refeitorio">
                      Fefeitorio </td>
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="checkbox[]7" value="Escala">
                      SGE </td>
                    <td><input type="checkbox" name="checkbox[]8" value="Folha">
                      Sistema de Folha </td>
                    <td><input type="checkbox" name="checkbox[]13" value="Siaenf">
                      Siaenf </td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="checkbox[]10" value="Intranet">
                      Intranet </td>
                    <td><input type="checkbox" name="checkbox[]9" value="Ouvidoria">
                      Ouvidoria </td>
                    <td><input type="checkbox" name="checkbox[]14" value="Cseih">
                      Cseih </td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
                <font size="2">&nbsp;
                
                </font> 
              </td>
            </tr>
          </table>
        </div></td>
    </tr>
    <tr> 
      <td><div align="center"> 
          
        </div></td>
      <td><input type=submit name=enviar value="Enviar" id="ileech" onkeypress="mascara(this,leech)" > 
	  <input type="hidden" name="IP" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">
        <?php print "<input type='button' name='fecha' value='Fechar' onClick=\"javascript:window.close()\">"; ?> 
      </td>
    </tr>
  </table>
</form>
<?php } ?>

<?	
    include ("includes/classes/headers.class.php");
    include ("includes/classes/conecta.class.php");
    include ("includes/classes/auth.class.php");
	include ("includes/classes/dateOpers.class.php");
	include ("includes/var_sessao.php");
    include ("includes/functions/funcoes.inc");
    include ("includes/javascript/funcoes.js");
	//include ("includes/javascript/calendar1.js");
 	include ("includes/config.inc.php");
	include ("includes/versao.php");
	
	include ("includes/languages/".LANGUAGE."");
	include ("includes/menu/menu.php");
	
	include ("includes/queries/queries.php");			

	print "<link rel=stylesheet type='text/css' href='/ocomon/includes/css/estilos.css'>";
		$conec = new conexao;
		$conec->conecta('MYSQL');
 
?>

<script language=javascript>

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
    

function Validar(){
		
		if(form1.contato.value == ""){
			alert("Informe seu nome por gentileza.");
			form1.contato.focus();
			return false;
			}

		if(form1.telefone.value == ""){
			alert("Informe seu ramal por gentileza.");
			form1.telefone.focus();
			return false;
			}
		
		if(form1.local.value == ""){
			alert("Informe seu setor por gentileza.");
			form1.local.focus();
			return false;
			}

		if(form1.descricao.value == ""){
			alert("Informe o problema/servi�o por gentileza.");
			form1.descricao.focus();
			return false;
			}
  }

</script>
<html>
<head>
<title>Envio de Mensagem</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<style type="text/css">
<!--
.style1 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size:10px}
-->
</style>
<link rel=stylesheet type='text/css' href='includes/css/estilos.css'>
</html>
</head>

<body onLoad="javascript:form1.descricao.focus();"  onSubmit="return Validar();">

<?
include("menuChamado.php");
?>
<FORM name='form1' method='POST' action='gravaChamadoTeste.php'  onSubmit="return Validar();">
  
  <table width="700" border="0"  align='center'><tr><td>
    <TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
      <TD bgcolor=#ECECDB> <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=#ECECDB>
          <TR> 
            <TD bgcolor=#ECECDB nowrap><b>Abertura de ocorrências</b></TD>
            <td width='20%' nowrap><p class='parag'><b><? echo date ("d/m/Y H:i");?></b></p></TD>
          </TR>
        </TABLE></TD>
    </TABLE>
    <br>
    <TABLE border="0"  align="center" width="100%" bgcolor=#F6F6F6>
      <tr> 
        <td width="20%" bgcolor=#ECECDB><div align="right" class="style1">* Nome 
            Completo:</div></td>
        <td width="80%" bgcolor=#ECECDB> <div align="left" class="style1"> 
            <INPUT type='text' class='text' name='contato' id='idContato' value='Patricia'>
          </div></td>
      </tr>
      <tr> 
        <td bgcolor=#ECECDB><div align="right" class="style1">* Ramal:</div></td>
        <td bgcolor=#ECECDB><div align="left" class="style1"> 
            <INPUT value='2324' type='text' class='text2' name='telefone' id='idTelefone' onkeypress="mascara(this,soNumeros)">
          </div></td>
      </tr>
      <tr> 
        <td bgcolor=#ECECDB><div align="right" class="style1">Email:</div></td>
        <td bgcolor=#ECECDB><div align="left" class="style1"> 
            <INPUT type='text' class='text2' name='email' id='idEmail'>
          </div></td>
      </tr><tr>
      <td bgcolor=#ECECDB><div align="right" class="style1">* Setor:</div></td><td bgcolor=#ECECDB><div align="left" class="style1"><SELECT class='select' name='local' id='idLocal' size=1>
      <option value="" selected>- Selecione um setor -</option>
      <?				$query ="SELECT l .  * , r.reit_nome, pr.prior_nivel AS prioridade, d.dom_desc AS dominio, pred.pred_desc as predio
						FROM localizacao AS l
						LEFT  JOIN reitorias AS r ON r.reit_cod = l.loc_reitoria
						LEFT  JOIN prioridades AS pr ON pr.prior_cod = l.loc_prior
						LEFT  JOIN dominios AS d ON d.dom_cod = l.loc_dominio
						LEFT JOIN predios as pred on pred.pred_cod = l.loc_predio
						WHERE loc_status not in (0) 
						ORDER  BY LOCAL ";
				$resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
	$invLoc = 5;

                while ($rowi = mysql_fetch_array($resultado))
                {
					print "<option value='".$rowi['loc_id']."'";
						if ($rowi['loc_id'] == $invLoc) print " selected";
					print ">".$rowi['local']." - ".$rowi['predio']."</option>";
                }
?></td></tr>
      <!--tr>
      <td colspan=2 bgcolor=#ECECDB><table width="100%" border="0" class="style1">
        <tr>
          <td class="style1" bgcolor=#ECECDB> <b> Selecione o Sistema:<br>
            </b>
              <table width="99%" border="0" class="style1">
                <tr>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Sih </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">            
                  Sysmat </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Siaf </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Custo </td>
                </tr>
                <tr>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Escala </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">            
                  Contabilidade </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Ocomon </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Refeitorio </td>
                </tr>
                <tr>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            SGE </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">                    Sistema de Folha </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Siaenf </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton"> 
                    Nosologia</td>
                </tr>
                <tr>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Intranet </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">            
                  Ouvidoria </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Cseih </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
                    Servi&ccedil;os Prestados </td>
                </tr>
                <tr>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
                    Site do HC </td>
                  <td colspan="3"><input name="radiobutton" type="radio" value="radiobutton">
                    Outros (especificar na descri&ccedil;&atilde;o qual o nome do sistema) </td>
                </tr>
              </table>            </td>
        </tr>
      </table></td>
    </tr--><tr><td colspan=2 bgcolor=#ECECDB><div align="left" class="style1"><table width="100%" border="0" class="style1"><tr><td bgcolor=#ECECDB><p><strong>* Problema/servi�o solicitado:</strong><b>:</b><br><TEXTAREA class='textarea' name='descricao' id='idDescricao'></textarea></p></td></tr>
    </table></div></td>
    <tr> 
      <td>Obs: * Campos obrigat&oacute;rios </tr>
    <tr> 
      <td colspan=2 bgcolor=#ECECDB align="center"><span class="style1"> 
        <input type=submit name=enviar value="Enviar">
        <input type="hidden" name="IP" value="<?echo $_SERVER['REMOTE_ADDR'];?>">
        <input type='button' name='fecha' value='Fechar' onClick="javascript:window.close()">
<?        print "<input type='hidden' name='data_gravada' value='".date("Y-m-d H:i:s")."'>";?>
        </span></td>
    </tr>
  </table>
</td>
</tr>
</table>
</form>

<?
 /*                        Copyright 2005 Fl�vio Ribeiro
  
         This file is part of OCOMON.
  
         OCOMON is free software; you can redistribute it and/or modify
         it under the terms of the GNU General Public License as published by
         the Free Software Foundation; either version 2 of the License, or
         (at your option) any later version.
  
         OCOMON is distributed in the hope that it will be useful,
         but WITHOUT ANY WARRANTY; without even the implied warranty of
         MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
         GNU General Public License for more details.
  
         You should have received a copy of the GNU General Public License
         along with Foobar; if not, write to the Free Software
         Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  */

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
    $conec = new conexao;
    $PDO = $conec->conectaPDO();
	$s_page_ocomon = "consulta_solucoes.php";
	session_register("s_page_ocomon");	
	
	print "<head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar1.js\"></script></head>";

 	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);		

?>

<BR>
<B>Consulta a Solu��es e Problemas:</B>
<BR>

<FORM method="POST" name="form1" action='mostra_resultado_solucoes.php' onSubmit="return valida()">
<TABLE border="0"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
        <TR>
                <TD width="15%" align="left" bgcolor=<?print TD_COLOR?>>Data Inicial:</TD>
                <TD width="15%" align="left" bgcolor=<?print BODY_COLOR?>><?print "<INPUT type=text class='data' name='data_inicial' id='idDataInicial' value=\"$hoje\" >";?><? print "<a href=\"javascript:cal1.popup();\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a>";?></TD>
                <TD width="15%" align="left" bgcolor=<?print TD_COLOR?>>Data Final:</TD>
                <TD width="15%" align="left" bgcolor=<?print BODY_COLOR?>><?print "<INPUT type=text class='data' name='data_final' id='idDataFinal' value=\"$hoje\" >";?><? print "<a href=\"javascript:cal2.popup();\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a>";?></TD>
                <TD width="10%" align="left" bgcolor=<?print TD_COLOR?>>Operador:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='operador' size=1>";
                print "<option value=-1 selected>-  Selecione um operador -</option>";
                $query = "SELECT * from usuarios order by nome";
                $resultado = $PDO->query($query);
                while ($aux=$resultado->fetch(PDO::FETCH_BOTH))
                {
                    ?>
                    <option value="<?print $aux[0];?>">
                        <?print $aux[1];?>
                    </option>
                    <?

                }
                ?>
                </SELECT>

                </TD>
        </TR>

        <TR>
                <TD width="15%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Problema:</TD>
                <TD colspan='5' width="80%" align="left" bgcolor=<?print BODY_COLOR?>><TEXTAREA class="textarea" name="problema" id="idDescricao"></textarea></TD>
        </TR>


        <TR>
                <TD width="15%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Retorna:</TD>
                <TD colspan='5' width="80%" align="left" bgcolor=<?print BODY_COLOR?>><input type='checkbox' name='onlyImgs'>Apenas chamados com anexos</TD>
        </TR>


        <TR>
                <BR>
                <TD colspan='3' align="center" width="50%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="    Ok    " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD colspan='3' align="center" width="50%" bgcolor=<?print BODY_COLOR?>><INPUT type="button" value="Cancelar" name="desloca" ONCLICK="javascript:location.href='abertura.php'"></TD>
        </TR>

</TABLE>
</FORM>
			<script language="JavaScript"> 
			 // create calendar object(s) just after form tag closed
				 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
				 // note: you can have as many calendar objects as you need for your application
				var cal1 = new calendar1(document.forms['form1'].elements['data_inicial']);
				cal1.year_scroll = true;
				cal1.time_comp = false;
				var cal2 = new calendar1(document.forms['form1'].elements['data_final']);
				cal2.year_scroll = true;
				cal2.time_comp = false;				
			
			function valida(){
				var ok = validaForm('idDataInicial','DATA-','Data inicial',0);	
				if (ok) var ok = validaForm('idDataFinal','DATA-','Data final',0);	
				if (ok) var ok = validaForm('idDescricao','','Problema',1);				
				
				return ok;
			
			}								
			
			//-->				
			</script>
</BODY>
</HTML>

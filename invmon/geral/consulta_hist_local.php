<?
 /*                        Copyright 2005 Flávio Ribeiro
  
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
	$s_page_invmon = "consulta_hist_local.php";
	session_register("s_page_invmon");			
	
	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);

	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);

    $cor1 = TD_COLOR;


?>

<BR>
<B>Busca de equipamentos por localização antiga e/ou período de remanejamento:</font></font></B>
<BR>

<FORM name="form1" method="POST" action="mostra_consulta_hist_local.php">
<TABLE border="0"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>



        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Tipo de equipamento: </b></TD>
                <TD colspan="3" width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class=select name='comp_tipo_equip' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
                $query = "SELECT * from tipo_equip  order by tipo_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
              	$i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>
                </TD>
		</tr>
				



        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Localização anterior:</b></TD>
                <TD colspan="3" width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class=select name='comp_local' size=1>";
                print "<option value=-1 selected>Todas</option>";
                $query = "SELECT * from localizacao  order by local";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>
                </TD>			
		</tr>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Data inicial:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><input type="text" class="text" disabled name="dInicio" size="10"></TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Data final:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><input type="text" class="text" disabled name="dFinal" size="10"></TD>
		</tr>

       
	   		<tr> <td colspan="4"></td></tr>
	    <TR>
                <BR>
                <TD align="center" width="20%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD colspan="3" align="center" width="30%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" onClick="javascript:history.back()"></TD>
        </TR>

        </table>


</form>

<script type="text/javascript">
<!--

	function desabilita(v)
	{
		document.form1.ok.disabled=v;
	}
 
	function Habilitar(){
		var ind_local = document.form1.comp_local.selectedIndex;
		var sel_local = document.form1.comp_local.options[ind_local].value;
		var d_inicial = document.form1.dInicio.value;
		var d_final = document.form1.dFinal.value;
		
			if ((sel_local==-1) && ((d_inicial=="")||(d_final=="")))
			{
				desabilita(true);
			
			} else {
				desabilita(false);

			}
		
	}
	window.setInterval("Habilitar()",100);

	
//-->
</script>      
	






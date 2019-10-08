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
	
	$s_page_invmon = "consulta_hist_inv.php";
	session_register("s_page_invmon");		
	
	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);

	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);

    $cor1 = TD_COLOR;
?>


<script type="text/javascript">
<!--

	function desabilita(v)
	{
		document.consulta.ok.disabled=v;
	}
 
	function Habilitar(){
		var etiqueta = document.consulta.comp_inv.value

			if (etiqueta=="")
			{
				desabilita(true);
			
			} else {
				desabilita(false);

			}
		
	}
	window.setInterval("Habilitar()",100);

	
//-->
</script>      
<BR>
<B>Consulta histórico por Etiqueta:</B>
<BR><br>

<FORM name="consulta" method="POST" action="mostra_historico.php">
<TABLE border="0"  width="40%" bgcolor=<?print BODY_COLOR?>>



        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Unidade:</font></font></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_inst' size=1>";
                //print "<option value=-1 selected>Selecione a Unidade:</option>";
                $query = "SELECT * from instituicao  order by inst_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>"selected>
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
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Etiqueta:</font></font></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="comp_inv" maxlength="30" size="40"></TD>
        </TR>
		<tr> <td colspan="2"></td></tr>
        <TR>
               
                <TD align="center" width="20%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="center" width="30%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" onClick="javascript:history.back()"></TD>
        </TR>

        </table>


</form>







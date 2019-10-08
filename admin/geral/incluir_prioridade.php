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

	$s_page_admin = "incluir_prioridade.php";
	session_register("s_page_admin");

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);	
	
	
?>	
<BR>
<B>Inclusão de níveis de prioridade para atendimento</B>
<BR>

<FORM method="POST" action="<?$PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="left" width="70%" bgcolor=<?print BODY_COLOR?>>
        <TR>
                <TD width="10%" align="left" bgcolor=<?print TD_COLOR?>>Nível:</TD>
                <TD width="90%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="p_nivel" id="idNivel"></TD>
        </TR>

        <TR>
                <TD width="10%" align="left" bgcolor=<?print TD_COLOR?>>SLA:</TD>
                <TD width="90%" align="left" bgcolor=<?print BODY_COLOR?>><select class='select' name="sla" id="idSla">
				<option value=-1>Selecione o SLA</option>
				<?
					$sql="select * from sla_solucao order by slas_tempo";
					$commit = mysql_query($sql);
					$i=0;
					while($row = mysql_fetch_array($commit)){
						print "<option value=".$row['slas_cod'].">".$row["slas_desc"]."</option>";
						$i++;
					} // while
				
				?>
				</select>				
				</td>
        </TR>



        <TR>
                <TD align="left" width="10%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="left" width="90%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar" onClick="javascript: history.back()"></TD>
        </TR>

        <?
                if ($rodou == "sim")
                {
                        $erro="não";

                        if (empty($p_nivel))
                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }

                        $query = "SELECT * FROM prioridades WHERE prior_nivel='$p_nivel'";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);

                        if ($linhas > 0)
                        {
                                $aviso = "Este nível de prioridade já está cadastrado!";
                                $erro = "sim";
                        }

                        if ($erro == "não")
                        {
                                $query = "INSERT INTO prioridades (prior_nivel, prior_sla) values ('".noHtml($p_nivel)."', $sla)";
                                $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = "ERRO ao incluir nível de prioridade.";
                                }
                                else
                                {
                                        $aviso = "OK. nível de prioridade incluido com sucesso.";
                                }
                        }
					print "<script>mensagem('".$aviso."'); redirect('prioridades.php');</script>";
                }
        ?>

</TABLE>
</FORM>

</body>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idNivel','','Nível',1);
		if (ok) var ok = validaForm('idSla','COMBO','SLA',1);
		//if (ok) var ok = validaForm('idPainel','COMBO','Painel',1);
		
		return ok;
	}		
-->	
</script>
</html>

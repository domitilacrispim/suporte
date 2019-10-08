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
	
	$s_page_admin = "incluir_problema.php";
	session_register("s_page_admin");

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);	
	
?>
<BR>
<B>Inclusão de problemas</B>
<BR>

<FORM method="POST" action="<?$PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="left" width="70%" bgcolor=<?print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Problema:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" name="problema" class="text" id="idProblema"></TD>
        </TR>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Área:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><select class='select' name="area" id="idArea">
				<option value=-1>Selecione a área</option>
				<?
					$sql="select * from sistemas where sis_status not in (0) and sis_atende=1 order by sistema";
					$commit = mysql_query($sql);
					$i=0;
					while($row = mysql_fetch_array($commit)){
						print "<option value=".$row['sis_id'].">".$row["sistema"]."</option>";
						$i++;
					} // while
				
				?>
				</select>				
				</td>
        </TR>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>SLA:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><select class='select' name="sla" id="idSla">
				<option value=-1>Selecione o SLA</option>
				<?
					$sql="select * from sla_solucao order by slas_tempo";
					$commit = mysql_query($sql);
					while($row = mysql_fetch_array($commit)){
						print "<option value=".$row['slas_cod'].">".$row["slas_desc"]."</option>";
					} // while
				
				?>
				</select>				
				</td>
        </TR>



        <TR>
                <TD align="left" width="20%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="left" width="20%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar" onClick="javascript: history.back()"></TD>
        </TR>

        <?
                if ($rodou == "sim")
                {
                        $erro="não";

                        if (empty($problema))
                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }

                        $query = "SELECT problema FROM problemas WHERE problema='$problema' and prob_area =".$area."";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);

                        if ($linhas > 0)
                        {
                                $aviso = "Este problema já está cadastrado!";
                                $erro = "sim";
                        }

                        $query = "SELECT * FROM problemas";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);
                        // $num=0;
                        // if ($linhas>0)
                                // $num = mysql_result($resultado,$linhas-1,0);
                        // $num++;

                        if ($erro == "não")
                        {
                                $query = "INSERT INTO problemas (problema, prob_area, prob_sla) values ('".noHtml($problema)."',$area, $sla)";
                                $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = "ERRO ao incluir problema!";
                                }
                                else
                                {
                                        $aviso = "OK. Problema incluido com sucesso!";
                                }
                        }
					print "<script>mensagem('".$aviso."'); redirect('problemas.php');</script>";
				}
        ?>

</TABLE>
</FORM>

</body>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idProblema','','Problema',1);
		if (ok) var ok = validaForm('idArea','COMBO','Área',1);
		if (ok) var ok = validaForm('idSla','COMBO','SLA',1);
		
		return ok;
	}		
-->	
</script>
</html>

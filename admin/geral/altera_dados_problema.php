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

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);
	
?>	

<BR>
<B>Alterar problema</B>
<BR>
<?        
            $query = "select * from problemas as p left join sistemas as s on prob_area = sis_id left join sla_solucao as sl on sl.slas_cod = p.prob_sla where p.prob_id = $prob_id order by p.problema, s.sistema";
            $resultado = mysql_query($query);
            $row = mysql_fetch_array($resultado);
?>
<FORM method="POST" action="<?PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="left" width="70%" bgcolor=<?php echo BODY_COLOR?>>
        <TR>
                <TD  width="20%" align="left" bgcolor=<?php echo TD_COLOR?> valign="top">Problema:</TD>
                <TD width="80%" align="left" bgcolor=<?php echo BODY_COLOR?>><INPUT type="text" class='text' name="problema" id="idProblema" value="<?php echo $row['problema'];?>" maxlength="100" size="60"></TD>
        </TR>
        <tr>
                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?> valign="top">Área:</TD>
                <TD width="80%" align="left" bgcolor=<?php echo BODY_COLOR?>><select class='select' name="area" id="idArea">
        <?
			$sql = "select * from sistemas where sis_id=".$row["prob_area"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value=-1 >Selecione a área</option>";
					$sql="select * from sistemas order by sistema";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["sis_id"].""; 
                        if ($rowB['sis_id'] == $row['prob_area'] ) {
                            print " selected";
                        }
                        print ">".$rowB["sistema"]."</option>";
					} // while
		
		?>
		</select>
		</td>
		</tr>
        
        <tr>
                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?> valign="top">SLA:</TD>
                <TD width="80%" align="left" bgcolor=<?php echo BODY_COLOR?>><select class='select' name="sla" id="idSla">
        <?
			$sql = "select * from sla_solucao where slas_cod=".$row["slas_cod"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value=-1 >Selecione o SLA</option>";
					$sql="select * from sla_solucao order by slas_tempo";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["slas_cod"].""; 
                        if ($rowB['slas_cod'] == $row['slas_cod'] ) {
                            print " selected";
                        }
                        print ">".$rowB["slas_desc"]."</option>";
					} // while
		
		?>
		</select>
		</td>
        </tr>

   <TR>
                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?> valign="top">Status:</TD>
                <TD width="80%" align="left" bgcolor=<?php echo BODY_COLOR?>><select class='select' name='status'>
				<?
					print"<option value=1";
					if ($row['prob_status']==1) print " selected";
					print ">ATIVO</option>";
					print"<option value=0";
					if ($row['prob_status']==0) print " selected";
					print">INATIVO</option>";
				
				?>
				</select>
				</TD>
        </TR>

        <TR>
                <BR>
                <TD align="center" width="20%" bgcolor=<?php echo BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="center" width="80%" bgcolor=<?php echo BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar" onClick="javascript:history.back()"></TD>
        </TR>
        

        <?
                if ($rodou == "sim" and !empty($problema))
                {
                        $query2 = "UPDATE problemas SET problema='".noHtml($problema)."', prob_area = $area, prob_sla = $sla WHERE prob_id='$prob_id'";
                        $resultado2 = mysql_query($query2);

                        if ($resultado2 == 0)
                        {
                                $aviso = "ERRO DE ACESSO. Um erro ocorreu ao tentar alterar o problema.";
                        }
                        else
                        {
                                $aviso = "Problema alterado com sucesso.";
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

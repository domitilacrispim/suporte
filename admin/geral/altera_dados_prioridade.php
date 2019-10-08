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

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);
	
?>

<BR>
<B>Alterar dados do n�vel de prioridade</B>
<BR>
<?
        $conec = new conexao;
        $PDO=$conec->conectaPDO();
        $query = $PDO->prepare("select p.*, sl.* from prioridades as p left join sla_solucao as sl on p.prior_sla = sl.slas_cod where p.prior_cod = $prior_cod");
        $query->execute();
        $resultado = $query->fetch(PDO::FETCH_NUM);
?>
<FORM method="POST" action="<?$PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="left" width="70%" bgcolor=<?print BODY_COLOR?>>
        <TR>
                <TD  width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">N�vel:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="p_nivel" id="idNivel" value="<?print $row['prior_nivel'];?>" maxlength="15" size="60"></TD>
        </TR>
        
        <tr>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">SLA:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><select class='select' name="sla" id="idSla">
        <?
        $query = $PDO->prepare("select * from sla_solucao where slas_cod=".$row["slas_cod"]."");
        $query->execute();
        $resultado = $query->fetch(PDO::FETCH_NUM);
				print "<option value=-1 >Selecione o SLA</option>";
					$sql=$PDO->prepare("select * from sla_solucao order by slas_tempo");
					$sql->execute();

                    print $sql  ->fetch(PDO::FETCH_NUM)."</option>";
					 // while
		
		?>
		</select>
		</td>
        </tr>

        <TR>
                <BR>
                <TD align="center" width="20%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="center" width="80%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar" onClick="javascript:history.back()"></TD>
        </TR>
        

        <?
                if ($rodou == "sim" and !empty($p_nivel))
                {
                        $query2 = "UPDATE prioridades SET prior_nivel='".noHtml($p_nivel)."', prior_sla = $sla WHERE prior_cod='$prior_cod'";
                        $resultado2 = mysql_query($query2);

                        if ($resultado2 == 0)
                        {
                                $aviso = "ERRO DE ACESSO. Um erro ocorreu ao tentar alterar a prioridade.";
                        }
                        else
                        {
                                $aviso = "Prioridade alterada com sucesso.";
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
		var ok = validaForm('idNivel','','N�vel',1);
		if (ok) var ok = validaForm('idSla','COMBO','SLA',1);
		//if (ok) var ok = validaForm('idPainel','COMBO','Painel',1);
		
		return ok;
	}		
-->	
</script>
</html>

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

	    $query = "SELECT S.*, STC.*
				FROM `status`  as S left join status_categ as STC on S.stat_cat = STC.stc_cod where  S.stat_id='$stat_id'";
        $resultado = mysql_query($query);
		$row = mysql_fetch_array($resultado);

	
	?>
<BR>
<B>Alterar status</B>
<BR>

<FORM method="POST" action="<?$PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Status:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="status" id="idStatus" value="<?print $row['status'];?>" maxlength="100" size="100"></TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Dependente:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>>
				<?
					$sql = "select * from status_categ order by stc_desc";
					$exec_sql = mysql_query($sql);
					print "<select class='select' name='categoria' id='idCategoria'>";
					print "<option value='null'>Selecione a dependência</option>";
					while ($rowCateg = mysql_fetch_array($exec_sql)) {
						print "<option value=".$rowCateg['stc_cod']." ";
						if ($rowCateg['stc_cod'] == $row['stat_cat']){
							print " selected";
						}
						print ">".$rowCateg['stc_desc']."</option>";
					}
					print "</select>";
				?>
				</TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Painel:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>>
				<?
					print "<select class='select' name='painel' id='idPainel'>";
					print "<option value='null'>Selecione o painel de exibição</option>";
					print "<option value='1' ";
						if ($row['stat_painel'] == 1) print "selected";
					print ">Superior</option>";
					print "<option value='2' ";
						if ($row['stat_painel'] == 2) print "selected";
					print ">Inferior</option>";
					print "<option value='3' ";
						if ($row['stat_painel'] == 3) print "selected";
					print ">Oculto</option>";
					print "</select>";
				?>
				</TD>
        </TR>
        <TR>
                <TD align="center" width="20%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="center" width="20%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar" onclick="javascript:history.back()"></TD>
        </TR>

        <?
                if ($rodou == "sim" and !empty($status)   and $categoria!="null" and $painel!="null")
                {
                        $query2 = "UPDATE status SET status='".noHtml($status)."', stat_cat=$categoria, stat_painel=$painel  WHERE stat_id='$stat_id'";
                        $resultado2 = mysql_query($query2);

                        if ($resultado2 == 0)
                        {
                                $aviso = "Um erro ocorreu ao tentar alterar dados do status. $query2";
                        }
                        else
                        {
                                $aviso = "Status alterado com sucesso.";
                        }
					print "<script>mensagem('".$aviso."'); redirect('status.php');</script>";
                } 
					
        ?>

</TABLE>
</FORM>

</body>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idStatus','','Status',1);
		if (ok) var ok = validaForm('idCategoria','COMBO','Dependência',1);
		if (ok) var ok = validaForm('idPainel','COMBO','Painel',1);
		
		return ok;
	}		
-->	
</script>
</html>

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
        
	$query = "select * from sistemas where sis_id='$sis_id'";
	$resultado = mysql_query($query);
	$row = mysql_fetch_array($resultado);
	
	
	?>
<BR>
<B>Alterar sistema</B>
<BR>

<FORM method="POST" action="<?PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Área:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="sistema" id="idArea" value="<?print $row['sistema'];?>">
					<? if ($row['sis_atende']) $check = " checked"; else $check = "";?>
					<input type='checkbox' name='areaatende' value='1' <?print $check;?> >Presta atendimento
				</TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">E-mail:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="email" id="idEmail" value="<?print $row['sis_email'];?>"></TD>
        </TR>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Status:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><select class='select' name='status'>
				<?
					print"<option value=1";
					if ($row['sis_status']==1) print " selected";
					print ">ATIVO</option>";
					print"<option value=0";
					if ($row['sis_status']==0) print " selected";
					print">INATIVO</option>";
				
				?>
				</select>
				</TD>
        </TR>

        <TR>
                
                <TD align="left" width="20%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="left" width="80%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar" onclick="javascript:history.back()"></TD>
        </TR>

        <?
		
				if (veremail($email)!="ok")
				{
						$aviso = "E-mail invalido.";
						$erro = true;
				}
		
                if ($rodou == "sim" and !empty($sistema) and !$erro)
                {
                        $query2 = "UPDATE sistemas SET sistema='".noHtml($sistema)."',sis_status=".$status.", ".
								"sis_email='".$email."', sis_atende='".$areaatende."' WHERE sis_id='".$sis_id."'";
                        $resultado2 = mysql_query($query2);

                        if ($resultado2 == 0)
                        {
                                $aviso =  "Um erro ocorreu ao tentar alterar dados no sistema.";
                        }
                        else
                        {
                                $aviso =  "Área alterada com sucesso.";
                        }
                        $origem = "sistemas.php";
                        session_register("aviso");
                        session_register("origem");
                        ?>
						<script language="javascript">
						<!--
							mensagem('<?print $aviso;?>');
							history.go(-2)();
						//-->
						</script>
						<?												
                        
						//echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                }
        ?>

</TABLE>
</FORM>

</body>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idArea','','Área',1);
		if (ok) var ok = validaForm('idEmail','EMAIL','Email',1);
		//if (ok) var ok = validaForm('idStatus','COMBO','Status',1);
		
		return ok;
	}		
-->	
</script>
</html>

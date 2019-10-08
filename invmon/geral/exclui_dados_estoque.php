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
	$cab = new headers;
	$cab->set_title(HTML_TITLE);

	$auth = new auth;


		if ($popup) {
			$auth->testa_user_hidden($s_usuario,$s_nivel,$s_nivel_desc,2);
		} else
			$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,2);

        $query = "SELECT * FROM estoque, itens, modelos_itens, localizacao where estoq_tipo = item_cod
					and estoq_tipo = mdit_tipo and estoq_desc = mdit_cod and estoq_local = loc_id
					and estoq_cod = $estoq_cod order by item_nome, estoq_desc";
        $resultado = mysql_query($query);
		$row = mysql_fetch_array($resultado);
?>

<BR>
<B>Excluir ítem do estoque:</B>
<BR><br>

<FORM method="POST" action=<?PHP_SELF?>>
<TABLE border="0" cellspacing="3" align="left" width="40%" bgcolor=<?print BODY_COLOR?>>

        <TR>
                <TD width="10%" align="left" bgcolor=<?print TD_COLOR?>>Tipo:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row['item_nome'];?></TD>
        </TR>


        <TR>
                <TD width="10%" align="left" bgcolor=<?print TD_COLOR?>>Descrição:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade']." ".$row['mdit_sufixo'];?></TD>
        </TR>

        <TR>
                <TD width="10%" align="left" bgcolor=<?print TD_COLOR?>>N.º Série:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row['estoq_sn'];?></TD>
        </TR>
        <TR>
                <TD width="10%" align="left" bgcolor=<?print TD_COLOR?>>Localização:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row['local'];?></TD>
        </TR>

        <?
                        ?>

						<tr> <td colspan="2"></td></tr>		

                        <TR>
                        <TD align="center" width="10%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                                <input type="hidden" name="rodou" value="sim">
                        </TD>
						<TD align="center" width="90%" bgcolor=<?print BODY_COLOR?>><INPUT type="button" value="Voltar" onClick="javascript:history.back()"> </TD>        
				
                        </TR>
                        <?
                        if ($rodou == "sim")
                        {
                                $query = "DELETE FROM estoque WHERE estoq_cod='$estoq_cod'";
                                $resultado = mysql_query($query);

                                if ($resultado == 0)
                                {
                                        $aviso = "ERRO ao excluir unidade do sistema.";

                                }
                                else
                                {
                                        $aviso = "OK. Unidade excluida com sucesso.";
                                }
                                $origem = "javascript:history.go(-2)";
                                session_register("aviso");
                                session_register("origem");
                                echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                        }

                 
        ?>

</TABLE>
</FORM>
</body>
</html>





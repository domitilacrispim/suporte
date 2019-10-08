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
	
	$s_page_ocomon = "emprestimos.php";
	session_register("s_page_ocomon");	

 	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";
    $conec = new conexao;
    $PDO = $conec->conectaPDO();
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);			
	

	print "<BR><B>Cadastro de Empr�stimos</B> <BR>";


        $query = "SELECT e.*, u.* from emprestimos as e, usuarios as u where e.responsavel = u.user_id order by data_devol";
        $resultado =$PDO->query($query);
        print "<TD align=right bgcolor=$cor1><a href=incluir_emprestimo.php> Incluir empr�stimo </a></TD><BR>";
        if ($resultado->rowCount() == 0)
        {
                echo mensagem("N�o h� nenhum empr�stimo pendente.");
        }
        else
        {
                $cor=TAB_COLOR;
                $cor1=TD_COLOR;
                $linhas = $resultado->rowCount();
                print "<TD>";
                print "Existe(m) <b>$linhas</b> empr�stimo(s) cadastrado(s) no sistema.<br>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='$cor'>";
                print "<TR class='header'><TD><b>Material</b></TD><TD><b>Respons�vel</b></TD><TD><b>Data do empr�stimo</b></TD>";
                print "<TD><b>Data de devolu��o</b></TD><TD><b>Quem</b></TD><TD><b>Local</b></TD><TD><b>Ramal</b></TD>
                <TD><b>Alterar</b></TD><TD><b>Excluir</b></TD></TR>";
                $i=0;
                $j=2;
                while ($resposta = $resultado->fetch(PDO::FETCH_ASSOC))
                {
                        if ($j % 2)
                        {
                                $color =  BODY_COLOR;
								$trClass = "lin_par";
						}
                        else
                        {
                                $color = white;
								$trClass = "lin_impar";
                        }
                        $local = $resposta[local];
                        $query = "SELECT local FROM localizacao WHERE loc_id=$local";
                        $resultado2 = $PDO->query($query);
                        $local = $resultado2->fetch(PDO::FETCH_ASSOC);
                        $j++;
                        print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
						?>
                       
                        <TD><?print $resposta[material];?></TD>
                        <TD><?print $resposta[nome];?></TD>
                        <TD><?print datab($resposta[data_empr]);?></TD>
                        <TD><?print datab($resposta[data_devol]);?></TD>
                        <TD><?print $resposta[quem];?></TD>
                        <TD><?print $local[local];?></TD>
                        <TD><?print $resposta[ramal];?></TD>
                        
						<?
						print "<TD><a onClick=\"redirect('altera_dados_emprestimo.php?empr_id=".$resposta['empr_id']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></TD>";
						print "<TD><a onClick=\"javascript:confirmaAcao('Deletar empr�stimo?','exclui_dados_emprestimo.php?empr_id=".$resposta['empr_id']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
                        print "</TR>";
                        $i++;
                }
                print "</TABLE>";
         }
        ?>


</body>
</html>

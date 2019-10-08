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
 
	//$s_page_admin = "ocorrencias.php";
	//session_register("s_page_admin");
?>

<HTML>
<BODY bgcolor=<?print BODY_COLOR?>>

<?
        if ($s_nivel!=1)
        {
                print "<script>window.open('../../index.php','_parent','')</script>";
				exit;
        }

?>

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?print TAB_COLOR?>>
                        <TR>
                        <?
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>OcoMon - M�dulo de Administra��o</b></TD>";
                        echo menu_usuario();
                        if ($s_nivel==1)
                        {
                                echo menu_admin();
                        }
                        ?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>
<?
		$queryTotal = "SELECT * from ocorrencias";
        $resultadoTotal = mysql_query($queryTotal);
        $linhasTotal = mysql_num_rows($resultadoTotal);
        
		
/*		$query = "SELECT o.*, u.*,s.status as status_nome, l.local as setor, p.problema as problema_nome FROM ocorrencias o, usuarios u, localizacao l, problemas p, status s
				WHERE o.operador = u.user_id and o.problema = p.prob_id and o.local = l.loc_id and s.stat_id =o.status ORDER BY numero";
   */     
				$query = $QRY["ocorrencias_full_ini"]." order by numero ";
				if (empty($min))  {
					$min =0; //Posso passar esse valor direto por par�metro se eu quiser!!
				};
				if (empty($max))  {
					$max =100; //Posso passar esse valor direto por par�metro se eu quiser!!
					if ($max>$linhasTotal) {$max=$linhasTotal;};
					$maxAux = $max;
				};

				if ($avanca==">") {
					$min+=$max;
					if ($min >($linhasTotal-$max)) {$min=($linhasTotal-$max);};
				}else
				if ($avanca==">>") {
					$min=$linhasTotal-$max;
				} else
				if ($avanca=="Todas") {
					$max=$linhasTotal;
					$min=0;
				} else
				if ($avanca=="<") {
					if (($max==$linhasTotal)and ($min==0)) {$max=$maxAux; $min=$linhasTotal;}; //Est� exibindo todos os registros na tela!
					$min-=$max;
					if ($min<0) {$min=0;};
				} else
				if ($avanca=="<<") {
					$min=0;
					$max=$maxAux;
				}
		
		$query.=" LIMIT $min,$max";	   
		
		
		
		
		$resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);
        $cor=TAB_COLOR;
        $cor1=TD_COLOR;

        if ($linhas == 0)
        {
                print "<TR class='header'><TD><B>N�o foi encontrada nenhuma ocorrência.</B></TD></TR>";
                exit;
        }
        if ($linhas>1){
				print "<table border='0' cellspacing='1' summary=''>";
				?><FORM method="POST" action="<?$PHP_SELF?>"><?
                
				$min++;
				if ($avanca=="Todos") {$top=$linhasTotal;} else$top=$min+($max-1);
				print "<tr>";
				print "<TD witdh='700' align=left><B>Foram encontradas <font color=red>$linhasTotal</font> ocorrências. Mostradas de <font color=red>$min</font> a <font color=red>$top</font>. </B></TD>";
				print "<TD width='100' align='left' ></td>";
				print "<TD width='224' align='left' ><input  type='submit' name='avanca' value='<<' title='Visualiza os $max primeiros registros.'> <input  type='submit' name='avanca' value='<' title='Visualiza os $max registros anteriores.'> <input  type='submit' name='avanca' value='>' title='Visualiza os pr�ximos $max registros.'> <input  type='submit' name='avanca' value='>>' title='Visualiza os �ltimos $max registros.'> <input  type='submit' name='avanca' value='Todas' title='Visualiza todos os $linhasTotal registros.'></td>";
				print "</tr>";
				$min--;
				print "<input type=hidden value=$min name=min>";
				print "<input type=hidden value=$max name=max>";
				print "<input type=hidden value=$maxAux name=maxAux>";
				print "</form>";
				print "</table>"; 
        
		}
		else
                print "<TR class='header'><TD><B>Foi encontrada somente 1 ocorrência.</B></TD></TR>";
        print "</TD>";

        print "<TD>";
        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
        print "<TR class='header'><TD>N�mero</TD><TD>Problema</TD><TD>Local</TD><TD>Operador</TD>
                <TD>Abertura</TD><TD>Status</TD><TD>Alterar</TD><TD>Excluir</TD></TR>";
        $i=0;
        $j=2;
        while ($row= mysql_fetch_array($resultado))
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
                $j++;
                print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
				?>
                <TD><a href="mostra_consulta.php?numero=<?print $row['numero']?>"><?print $row['numero'];?></a></TD>
                <TD><?print $row['problema']?></TD>
                <TD><?print $row['setor'];?></TD>
                <TD><?print $row['nome'];?></TD>
                <TD><?print datab($row['data_abertura']);?></TD>
                <TD><?print $row['chamado_status'];?></TD>
                <?
				print "<td><a onClick=\"redirect('altera_dados_ocorrencia.php?numero=".$row['numero']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></TD>";
				print "<td><a onClick=\"confirma('Tem Certeza que deseja excluir essa ocorrência do sistema?','excluir_ocorrencia.php?numero=".$row['numero']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
				print "</TR>";
                
				
				$i++;
        }
?>
</BODY>
</HTML>

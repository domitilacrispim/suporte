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
	$cab->set_title($TRANS["html_title"]);
	$auth = new auth;
	
		if ($popup) {
			$auth->testa_user_hidden($s_usuario,$s_nivel,$s_nivel_desc,2);
		} else
			$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,2);

    $hoje = date("Y-m-d");


		
        $cor  = TAB_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;

		$query = $QRY["garantia"];
		$query.= " and c.comp_inv=$comp_inv and c.comp_inst=$comp_inst
					order by aquisicao";
		
		$resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);
		$row = mysql_fetch_array($resultado);  

		$vencimento = $row['vencimento'];
		$dias = data_diff_dias($hoje,$vencimento);
		if ($dias>=0) {
			$status='Em garantia'; 
			$statusColor='green';
			if ($dias!=1) $s=' dias';  else
			$s=' dia'; 
			$expira= $dias.$s;
		} 
			else {
			$status='Garantia vencida'; 
			$statusColor='red'; 
			$expira = 'Expirado';
			}
#########################################################################
       print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%' bgcolor='$cor3'>";
                
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print "<tr><td width=100% align=left><b>Controle de garantias do fabricante.</b></td></tr>";

  
        print "<TD>";
        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%' bgcolor='$cor3'>";
       // print "<TR><TD bgcolor=$cor3><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><b>Equipamento</TD><TD bgcolor=$cor3><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><b>Quantidade</TD><TD bgcolor=$cor3><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><b>Percentual</TD></tr>";        
        $i=0;
        $j=2;
		print "</TABLE>";
				
######################################################################################
					
###############################################################################					
//INSERIR CÓDIGO


	if ($linhas == 0) {print ("<fieldset><table><p align='center'>Este equipamento <b>não</b> está cadastrado quando ao seu período de garantia!<br><br><a href='javascript:window.close()'>Fechar!</a></p></table></fieldset>") ;} else {
	

       print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%' bgcolor='$cor3'>";

	?>

                <TR>
				<TD bgcolor=<?print $cor1;?>><b>Etiqueta</b></TD>
                <TD bgcolor=<?print $cor1;?>><b>Garantia</b></TD>
                <TD bgcolor=<?print $cor1;?>><b>Tipo</b></TD>
				<TD bgcolor=<?print $cor1;?>><b>Fornecedor</b></TD>
                <TD bgcolor=<?print $cor1;?>><b>Contato</b></TD>
                <TD bgcolor=<?print $cor1;?>><b>Vencimento</b></TD>									
                <TD bgcolor=<?print $cor1;?>><b>Tempo restante</b></TD>									
                <TD bgcolor=<?print $cor1;?>><b>Status</b></TD>									

				</tr>
		
                <TR>
                <TD bgcolor=<?print $color;?>><?print $row["inventario"];?></TD>                
                <TD bgcolor=<?print $color;?>><?print $row["meses"].' meses';?></TD>
                <TD bgcolor=<?print $color;?>><?print $row["garantia"];?></TD>
                <TD bgcolor=<?print $color;?>><?print $row["fornecedor"];?></TD>
                <TD bgcolor=<?print $color;?>><?print $row["contato"];?></TD>
                <TD bgcolor=<?print $color;?>><?print $row["dia"].'/'.$row["mes"].'/'.$row["ano"];?></TD>
                <TD bgcolor=<?print $color;?>><font color=<?print $statusColor?>><b><?print $expira?></b></font></TD>
                <TD bgcolor=<?print $color;?>><font color=<?print $statusColor?>><b><?print $status?></b></font></TD>

				</tr>
	<?
	print "</table>";
	
	}
	
				

              
?>        


</BODY>
</HTML>

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

		//$model = 31;
		//$query = $QRY["garantia"];
		$query.= "SELECT * from materiais where mat_modelo_equip in ($model) order by mat_caixa,mat_nome";
		
		$resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);
		

#########################################################################
       print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%' bgcolor='$cor3'>";
                
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print "<tr><td width=100% align=left><b>Documentos associados a esse modelo de equipamento.</b></td></tr>";

  
        print "<TD>";
        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%' bgcolor='$cor3'>";
       // print "<TR><TD bgcolor=$cor3><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><b>Equipamento</TD><TD bgcolor=$cor3><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><b>Quantidade</TD><TD bgcolor=$cor3><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><b>Percentual</TD></tr>";        
        $i=0;
        $j=2;
		print "</TABLE>";
				
######################################################################################
					
###############################################################################					
//INSERIR CÓDIGO


	if ($linhas == 0) {print ("<fieldset><table><p align='center'>Não existem documentos associados para esse modelo de equipamento!<br><br><a href='javascript:window.close()'>Fechar!</a></p></table></fieldset>") ;} else {
	

       print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%' bgcolor='$cor3'>";

	?>
		
                <TR>
				<TD bgcolor=<?print $cor1;?>><b>Documento</b></TD>
                <TD bgcolor=<?print $cor1;?>><b>Localização</b></TD>
                <TD bgcolor=<?print $cor1;?>><b>Observaçao</b></TD>
				</tr>
		
	<? 
		$j = 2;
		while ($row = mysql_fetch_array($resultado))  {  
          if ($j % 2)
          {
                  $color = $cor3;
          }
          else
          {
                  $color = $cor;
          }
          $j++;	
	
	?>
                
				<TR>
                <TD bgcolor=<?print $color;?>><?print $row["mat_nome"];?></TD>                
                <TD bgcolor=<?print $color;?>><?print 'Caixa '.$row["mat_caixa"];?></TD>
                <TD bgcolor=<?print $color;?>><?print $row["mat_obs"];?></TD>
				</tr>
	
	<?
	}
	print "</table>";
	
	}
	
				

              
?>        


</BODY>
</HTML>

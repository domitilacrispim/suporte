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
	
	$s_page_invmon = "estat_vencimentos.php";
	session_register("s_page_invmon");			

	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);
	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);

        $hoje = date("Y-m-d H:i:s");

		
        $cor  = TAB_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;

			$query = $QRY["vencimentos"];
            $result = mysql_query($query);
			//----------------TABELA 14 -----------------//
			print "<br><br><p align='center'>PR�XIMOS VENCIMENTOS DE GARANTIA (at� 3 anos):</p>";
			print "<table cellspacing='0' border='1' align='center' style=\"{border-collapse:collapse;}\">";
			print "<tr><td><b>DATA</b></td><td><b>QUANTIDADE</b></td><td><b>TIPO</b></td><td><b>MODELO</b></td></tr>";			
			//-----------------FINAL DA TABELA 14 -----------------------//
		
            $tt_garant = 0;
			while ($row=mysql_fetch_array($result)) {
				$temp = explode(" ",datab($row['vencimento']));
				$vencimento = $temp[0];
				$tt_garant+= $row['quantidade'];
				print "<tr><td>".$vencimento."</td><td align='center'>".$row['quantidade']."</td><td>".$row['tipo']."</td><td>".$row['fabricante']." ".$row['modelo']."</td></tr>";
			} // while
			print "<tr><td><b>TOTAL</b></td><td colspan='3'><b>".$tt_garant."</b></td></tr>";
			print "</table><br><br>";
				
					
					
###############################################################################					
					
					
					print "<TABLE width=80% align=center>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";

					//print "<tr><td width=80% align=center><b><a href=relatorio_geral.php>Relat�rio Geral</a>.</b></td></tr>";				
					print "</TABLE>";				


					print "<TABLE width=80% align=center>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";

					print "<tr><td width=80% align=center><b>Sistema em desenvolvimento pelo setor de Helpdesk  do <a href='http://www.unilasalle.edu.br' target='_blank'>Unilasalle</a>.</b></td></tr>";				
					print "</TABLE>";				
				

              
?>        


</BODY>
</HTML>

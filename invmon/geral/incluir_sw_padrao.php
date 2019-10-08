<?php
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
	
	
	$hojeLog = date ("d-m-Y H:i:s");

	
                if ($submit == "Cadastrar")
                {
                        $erro="não";

                        
						if ($software == -1)
                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }

                        if ($erro == "não")
                        {
                                $query = "insert into sw_padrao (swp_sw_cod) values 
										($software)";
                                $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        print $query."<br>";
										$aviso = "ERRO ao incluir software ".$query."";
                                }
                                else
                                {
                                        $aviso = "OK. Software incluído com sucesso!";
                                }
                        }
						
				   		print "<script>mensagem('$aviso'); window.opener.location.reload(); </script>";                    
						$origem = "incluir_hws.php";
                        
						session_register("aviso");
                        session_register("origem");
                }
	
	
	
	
	
	print "<br>";
	print "<table class=corpo>";
	print "<tr>";
	print "<TD width='400' align='left'><B>Cadastro de configuração padrão de software</B></TD>";
	print "<TD width='224' align='left'></td>";
	print "</tr>";
	print "</table><br>";
	
	
	print "<form method='post' action=".$PHP_SELF.">";
	print "<table border='0' align='left' width='40%'>";
			print "<input type='hidden' name='popup' value='$popup'>";			

		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">Software:</TD>";	
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR.">";			
					print "<select class='select' name='software'>";
					print "<option value=-1 selected>Selecione o software</option>";

					$sql = "select * from sw_padrao;";
					$commit = mysql_query($sql);
					while($rowA = mysql_fetch_array($commit)){
						$softs.= $rowA["swp_sw_cod"].",";
					} 		
					if (isset($softs)) {
						$softs = substr($softs,0,-1);
					} else
						$softs = -1;
					
					//Retorna todos os softwares menos os já cadastrados como instalaçao padrão
					$select = "select s.*, f.* from softwares s, fabricantes f where f.fab_cod = s.soft_fab
								 and s.soft_cod not in ($softs) order by fab_nome, soft_desc";
					$exec = mysql_query($select);
					while($row = mysql_fetch_array($exec)){
						print "<option value=".$row['soft_cod'].">".$row['fab_nome']." ".$row['soft_desc']." ".$row["soft_versao"]."</option>";
					} // while
					print "</select>";
		print "</tr>";

	

		print "<tr>";
			print "<td align='center' width='20%' bgcolor=".BODY_COLOR."><input type='submit' value='Cadastrar' name='submit'></td>";	
			print "<td align='center' width='80%' bgcolor=".BODY_COLOR."><input type='reset' value='Cancelar' onClick=\"javascript:window.close()\" name='cancelar'></td>";		
		print "</tr>";		
	
	

	


	print "</table>";
	print "</form>";

	
	$cab->set_foot();

?>
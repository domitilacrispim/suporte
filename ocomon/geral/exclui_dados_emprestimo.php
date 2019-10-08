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

         
                        $sql = "select * from emprestimos where empr_id = '".$empr_id."'";
						$exec = mysql_query($sql);
						$row = mysql_fetch_array($exec);
						
						
						$query = "DELETE FROM emprestimos WHERE empr_id='$empr_id'";
                        $resultado = mysql_query($query);

                        if ($resultado == 0)
                        {
                                $aviso = "ERRO ao excluir empréstimo do sistema.";
                        }
                        else
                        {
                                $aviso = "OK. Empréstimo excluido com sucesso.";
							$texto = "Excluído empréstimo de ".$row['material']." que estava com ".$row['quem']." e foi emprestado por ".$row['responsavel']."";
							geraLog(LOG_PATH.'ocomon.txt',$hojeLog,$s_usuario,'exclui_dados_emprestimo.php',$texto);	

					   }
						
						?>
							<script>
								mensagem('<? print $aviso?>');
								redirect ('emprestimos.php');
							</script>
						
						
          

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

        if ($s_nivel!=1)
        {
                print "<script>window.open('../../index.php','_parent','')</script>";
				exit;
        }

		$total = 0; $texto = "";
		$sql_1 = "SELECT * from usuarios where AREA='".$sis_id."'";
		$exec_1 = mysql_query($sql_1);
		$total+=mysql_numrows($exec_1);
		if (mysql_numrows($exec_1)!=0) $texto.="usuarios, ";
		
		$sql_2 = "SELECT * FROM ocorrencias where sistema ='".$sis_id."'";
		$exec_2 = mysql_query($sql_2);
		$total+= mysql_numrows($exec_2);
		if (mysql_numrows($exec_2)!=0) $texto.="ocorrencias, ";

		$sql_3 = "SELECT * FROM problemas where prob_area ='".$sis_id."'";
		$exec_3 = mysql_query($sql_3);
		$total+= mysql_numrows($exec_3);
		if (mysql_numrows($exec_3)!=0) $texto.="problemas, ";
		
		if ($total!=0)
		{
			print "<script>mensagem('Esta área não pode ser excluída, existem pendências nas tabelas: ".$texto." associados a ela!'); 
				redirect('sistemas.php');</script>";
		}
		else
		{
			$query2 = "DELETE FROM sistemas WHERE sis_id='".$sis_id."'";
			$resultado2 = mysql_query($query2);
			
			if ($resultado2 == 0)
			{
					$aviso = "ERRO ao excluir Área!";
			}
			else
			{
					$aviso = "OK. Área excluida com sucesso!";
			}
			print "<script>mensagem('".$aviso."'); redirect('sistemas.php');</script>";
					
		}
?>

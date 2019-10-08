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
		$sql_1 = "SELECT * from equipamentos where comp_fab='".$fab_cod."'";
		$exec_1 = mysql_query($sql_1);
		$total+=mysql_numrows($exec_1);
		if (mysql_numrows($exec_1)!=0) $texto.="equipamentos, ";
		
		$sql_2 = "SELECT * FROM softwares where soft_fab ='".$fab_cod."'";
		$exec_2 = mysql_query($sql_2);
		$total+= mysql_numrows($exec_2);
		if (mysql_numrows($exec_2)!=0) $texto.="softwares, ";

		
		if ($total!=0)
		{
			print "<script>mensagem('Este fabricante não pode ser excluído, existem pendências nas tabelas: ".$texto." associados a ele!'); 
				redirect('fabricantes.php');</script>";
		}
		else
		{
			$query2 = "DELETE FROM fabricantes WHERE fab_cod='".$fab_cod."'";
			$resultado2 = mysql_query($query2);
			
			if ($resultado2 == 0)
			{
					$aviso = "ERRO ao excluir registro!";
			}
			else
			{
					$aviso = "OK. Registro excluido com sucesso!";
			}
			print "<script>mensagem('".$aviso."'); redirect('fabricantes.php');</script>";
					
		}
?>

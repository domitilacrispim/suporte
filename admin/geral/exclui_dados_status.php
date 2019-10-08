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


        if ($s_nivel!=1)
        {
                print "<script>window.open('../../index.php','_parent','')</script>";
				exit;
        }

        $query = "select * from ocorrencias where status='".$stat_id."'";
        $resultado = mysql_query($query);
		$linhas = mysql_numrows($resultado);

		if ($linhas!=0)
		{
			print "<script>mensagem('Esse status n�o pode ser exclu�do! Existem ocorrências associadas a ele!');
			       redirect('status.php');</script>";
		}
		else
		{
			$query2 = "DELETE FROM status WHERE stat_id='".$stat_id."'";
			$resultado2 = mysql_query($query2);
			if ($resultado2 == 0)
			{
					$aviso = "ERRO na exclus�o do registro!";
			}
			else
			{
					$aviso = "OK, Registro exclu�do com sucesso!";
			}
        	print "<script>mensagem('".$aviso."'); redirect('status.php');</script>";

		}
?>

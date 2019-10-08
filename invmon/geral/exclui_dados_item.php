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
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);
	
        $hoje = date("Y-m-d H:i:s");

		$query2 = "select * from equipamentos where comp_cdrom='".$item_cod."'";
		$resultado2 = mysql_query($query2);
		$linhas2 = mysql_numrows($resultado2);
		if ($linhas2!=0)
		{
			print "<script>mensagem('Esse componente não pode ser excluído, existem equipamentos associados a ele no sistema!');".
			      "redirect('itens.php?tipo=".$tipo."');</script>";			
		}
		else
        {
			$query = "DELETE FROM modelos_itens WHERE mdit_cod='".$item_cod."'";
			$resultado = mysql_query($query);

			if ($resultado == 0)
			{
					$aviso = "ERRO ao excluir unidade do sistema.";

			}
			else
			{
					$aviso = "OK. Unidade excluida com sucesso.";
			}
			print "<script>mensagem('".$aviso."'); redirect('itens.php?tipo=".$tipo."');</script>";
		}
			?>

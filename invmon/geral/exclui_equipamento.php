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

        if ($s_nivel!=1)
        {
        	$aviso = "".$TRANS["alerta_permissao"]."!";
		
		} else {
			
                                $sql = "DELETE FROM historico WHERE hist_inv = '$comp_inv' and hist_inst= '$comp_inst'";
								$resultadoSQL = mysql_query($sql);
								
								$query = "DELETE FROM equipamentos WHERE comp_inst='$comp_inst' and comp_inv='$comp_inv'";
                                $resultado = mysql_query($query);

                                if ($resultado == 0)
                                {
                                        $aviso = "".$TRANS["alerta_erro_excluir"]."!";
                                }
                                else
                                {
										$aviso = "".$TRANS["alerta_sucesso_excluir"]."!";
										$texto = " Excluído: Etiqueta= $comp_inv, Unidade= $comp_inst";
										geraLog(LOG_PATH.'invmon.txt',$hojeLog,$s_usuario,'exclui_dados_computador.php',$texto);	                                   
								}
                                $origem = "mostra_computadores.php";
                                session_register("aviso");
                                session_register("origem");
						
						}

		?>
		<script language="javascript">
		<!--
			history.back();
			mensagem('<?print $aviso; print $texto;?>');
		//-->
		</script>

</body>
</html>
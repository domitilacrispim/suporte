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
	
	$s_page_ocomon = "relatorios.php";
	session_register("s_page_ocomon");		

 	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);			
?>
<BR>
<B>Relatórios:</B>
<BR>
<?
        print "<TR><TD><B>Escolha um dos relatórios prontos, ou clique <a href=relatorio_total.php>AQUI</a> para um relatório personalizado. </B></TD></TR>";
        print "</TD>";
        print "<TD>";
        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
        print "<TR class='header'><TD>Relatórios por Periodo</TD><TD>Relatórios por...</TD>";

        $color =  BODY_COLOR;
?>
        <TR class='lin_impar'>
        <TD colspan='2'><a href=relatorio_problemas_areas.php>Problemas por área de atendimento</a></TD></TR><!-- -->
        </TR>

        <TR class='lin_par'>
        <TD colspan='2'><a href=relatorio_setores_areas.php>Locais mais atendidos</TD><!-- -->
        </TR>

        <TR class='lin_impar'>
        <TD colspan='2'><a href=relatorio_geral.php>Geral</TD><!-- -->
        </TR>

        
   <!--     <TR>
        <TD bgcolor=white><a href=relatorio_periodo_descricao.php>Assentamentos por período</a></TD> -->
        <tr class='lin_par'>
		<TD colspan='2'><a href="relatorio_slas_2.php">Indicadores baseados em níveis de status</a> |  <a href=relatorio_slas.php>SLA's</a></TD><!-- -->
        </TR>

        <TR class='lin_impar'>
   <!--    <TD bgcolor=><a href=relatorio_periodo_equipamento.php>Equipamentos por período</a></TD>-->
        <TD colspan='2'><a href=chamados_x_etiqueta.php>Chamados por equipamento</TD><!-- -->
        </TR>

<!--       
	   <TR>
        <TD bgcolor=white>Usuários por período</TD><!--<a href=relatorio_periodo_contato.php> 
        <TD bgcolor=white>Atendimentos por usuário</TD><!--<a href=relatorio_contato.php>
        <TD bgcolor=white>&nbsp;</TD>
        </TR>
 -->      

        <TR class='lin_par'>
        <TD colspan='2'><a onClick ="checa_permissao('relatorio_gerencial.php')">Gerência do Helpdesk</a></TD><!-- -->
        </TR>

        <TR class='lin_impar'>
        <TD colspan='2'><a href=relatorio_operadores_areas.php>Atendimentos por operador</a></TD><!-- -->
        </TR>

        <TR class='lin_par'>
        <TD colspan='2'><a href='relatorio_usuarios_areas.php'>Atendimentos por usuário</a></TD><!-- <a href=relatorio_status.php>-->
        </TR>
		<tr>
		<TD colspan='2'><a href='relatorio_chamados_area.php'>Quantidade de chamados: Área x período</a></TD><!-- -->
		</tr>

        <TR class='lin_par'>
        <TD colspan='2'><a href='relatorio_usuario_final.php'>Chamados abertos pelo usuário-final</a></TD><!-- -->
        </TR>
		
		
</BODY>
<script type='text/javascript'>

	 function redirect(url){
		window.location.href=url;
	 }


	 function checa_permissao(URL){
	 	var admin = '<?print $_SESSION['s_nivel'];?>';
	 	var area_admin = '<?print $_SESSION['s_area_admin']?>';
		if( (admin!=1) && (area_admin!=1) ) {
			window.alert('Acesso Restrito!');
		} else
			redirect(URL);

		return false;
	 }
	 
	 
	

</script>
</HTML>

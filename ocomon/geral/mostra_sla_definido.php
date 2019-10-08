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


//$popup = true;
?>

<script type='text/javascript'>

	 function popup_alerta(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'_blank','width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
      	//x.moveTo(100,100);
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);	  	
		return false
     }

</script>

<HTML><head><title>SLA Definido</title></head>
<BODY bgcolor=<?print BODY_COLOR?>>
<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?print TAB_COLOR?>>
                        <TR>
                        <?
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>OcoMon - M�dulo de ocorrências</b></TD>";
                        if (!$popup) {
							echo menu_usuario();
	                        if ($s_nivel==1)
	                        {
	                                echo menu_admin();
	                        }
                        }
						?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>



<?
        $hoje = date("Y-m-d H:i:s");
		
		//$numero = 18145;//encerrado
		//$numero = 18162;//em aberto
		
		$sql = "select o.numero, p.problema as problema, l.local as local, resp.slas_desc as resposta,
			sol.slas_desc as solucao
			FROM
				ocorrencias as o, prioridades as pr, sla_solucao as sol
				left join problemas as p on p.prob_sla = sol.slas_cod 
				left join localizacao as l on l.loc_prior= pr.prior_cod
				left join sla_solucao as resp on resp.slas_cod = pr.prior_sla
			where 
				o.problema = p.prob_id and o.local = l.loc_id and o.numero = $numero";
		
		$exec_sql = mysql_query($sql);
		$row=mysql_fetch_array($exec_sql);
		
			print "<br><b>SLAs para a ocorrência <font color='red'>".$numero."</font>:</b><br>";
			print "<table cellspacing='0' border='1' cellpadding='1' align='left' width='100%'>";
			//print "<tr><td width='20%'><b>ocorrência:</b></td><td colspan='3'><b>$numero</b></td></tr>";
			print "<tr><td width='20%'><b>Setor:</b></td><td width='30%'>".$row['local']."</td><td width='20%'><b>Problema:</b></td><td width='30%'>".$row['problema']."</td></tr>";
			print "<tr><td width='20%'><b>SLA de Resposta:</b></td><td width='30%'>".$row['resposta']."</td><td width='20%'><b>SLA de Solu��o:</b></td><td width='30%'>".$row['solucao']."</td></tr>";

		
			print "</table>";
		
		
?>
</body>
</html>

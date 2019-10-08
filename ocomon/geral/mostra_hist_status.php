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

<HTML><head><title>Hist�rico de status</title></head>
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
		
		$sql = "select T.ts_ocorrencia as chamado, S.status as status,  sum(T.ts_tempo) as total, sec_to_time(sum(T.ts_tempo)) as tempo, 
			SAT.status as status_atual, T.ts_status as codStat, O.status as codStatAtual, T.ts_data as data 
			from ocorrencias as O, tempo_status as T, `status` as S, `status` as SAT
			where O.numero = T.ts_ocorrencia and S.stat_id = T.ts_status and T.ts_ocorrencia = ".$numero." and O.status = SAT.stat_id
			group by T.ts_ocorrencia, T.ts_status
			order by T.ts_ocorrencia, T.ts_status";
		$exec_sql = mysql_query($sql);
		$qtd = mysql_num_rows($exec_sql);
		$tempoTotalSec = 0;
		$tempoSecStatAtual = 0;
		if ($qtd==0) {
			print  "<br><br><b>N�o existe hist�rico de status para esse chamado!</b> <br>Essa consulta s� � poss�vel para chamados abertos apartir de 09 de Setembro de 2004.<br><br>";
		}  else {
		
		
			print "<br><b>Hist�rico de status para a ocorrência <font color='red'>".$numero."</font>:</b><br>";
			print "<table cellspacing='1' border='1' cellpadding='1' align='left' width='100%'><tr><td><b>Status</b></td><td><b>Tempo</b></td></tr>";
			while ($row = mysql_fetch_array($exec_sql)) {
				
				$tempoTotalSec+=$row['total'];
				
				if ($row['codStatAtual'] != 4){
					if ($row['codStat'] == $row['codStatAtual']) {//Verifico o status atual para buscar a data
						$data = $row['data']; //s� preciso dessa data se o chamado n�o estiver encerrado!!
					}
				} else {
				//.....//
				}
				$codStatAtual = $row['codStatAtual'];
				$statAtual = $row['status_atual'];
				if ($row['codStat'] != $row['codStatAtual']) { //vou imprimir o status atual fora do loop
					print "<tr><td>".$row['status']."</td><td>".$row['tempo']."</td></tr>";
				} else $tempoSecStatAtual = $row['total'];
			
			}
			
			$dt = new dateOpers;
			
			if ($codStatAtual == 4) {//encerrada
				$tempoHora = $dt->secToHour($tempoTotalSec);
			} else {
				//chamados ainda n�o encerrados
				$areaChamado = 1;
				$dt->setData1($data);
				$dt->setData2($hoje);
	
				$dt->tempo_valido($dt->data1,$dt->data2,$H_horarios[$areaChamado][0],$H_horarios[$areaChamado][1],$H_horarios[$areaChamado][2],$H_horarios[$areaChamado][3],"H");
				$segundos = $dt->diff["sValido"]; //segundos v�lidos
				$tempoHoraStatAtual = $dt->secToHour($segundos+$tempoSecStatAtual);
				
				$tempoHora = $dt->secToHour($segundos+$tempoTotalSec);
				
			}
			
			//print "<tr><td>NO STATUS ATUAL</td><td>".$tempoHoraStatAtual."</td></tr>";
			print "<tr><td><b><font color='green'>".$statAtual."</b> (Status Atual)</td><td><b><font color='green'>".$tempoHoraStatAtual."</b></font></td></tr>";
			print "<tr><td>TEMPO TOTAL</td><td>".$tempoHora."</td></tr>";
			print "</table>";
		}
		
?>
</body>
</html>

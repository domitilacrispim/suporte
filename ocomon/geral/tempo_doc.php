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

<HTML><head><title>Tempo de Documenta��o</title></head>
<BODY bgcolor=<?print BODY_COLOR?>>
<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?print TAB_COLOR?>>
                        <TR>
                        <?
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>OcoMon - M�dulo de ocorrências</b></TD>";
						
						
			
			?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>



<?
			
		$sql = "select d.*, u.* from doc_time d, usuarios u where d.doc_user = u.user_id and doc_oco = ".$_GET['cod']." ";	
			
		$exec_sql = mysql_query($sql);
		$total = 0;
		$totalGeral = 0;
		print "<br><b>Tempo de documenta��o para a ocorrência <font color='red'>".$_GET['cod']."</font>:</b><br>";
		print "<table cellspacing='0' border='1' cellpadding='1' align='left' width='100%'>";
			print "<tr><td width='20%'><b>Operador</b></td><td width='20%'><b>Abertura</b></td><td width='20%'><b>Edi��o</b></td><td width='20%'><b>Encerramento</b></td><td width='20%'><b>Total</b></td></tr>";
		
		while ($row=mysql_fetch_array($exec_sql)) {
			$total= $row['doc_open']+$row['doc_edit']+$row['doc_close'];
			print "<tr><td width='20%'>".$row['nome']."</td><td width='20%'>".segundos_em_horas($row['doc_open'])."</td><td width='20%'>".segundos_em_horas($row['doc_edit'])."</td><td width='20%'>".segundos_em_horas($row['doc_close'])."</td><td width='20%'>".segundos_em_horas($total)."</td></tr>";			
			
			$totalGeral+=$total;
		
		}
		
		print "<tr><td colspan='4'><b>Tempo total em documenta��o</b></td><td><b>".segundos_em_horas($totalGeral)."</b></td></tr>";
			
			

		
			print "</table>";
		
		
?>
</body>
</html>

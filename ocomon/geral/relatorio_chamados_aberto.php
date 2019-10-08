<html>
<head>
   <script language="JavaScript" src="../../includes/javascript/calendar1.js"></script>
<?php

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
?>

</head>
<body>
<BR><BR>
<?php
	print testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);
    $conec = new conexao;
    $PDO = $conec->conectaPDO();

    $query = "SELECT  s.sistema, count( * ) as quant
		FROM `ocorrencias` o
		JOIN sistemas s ON o.sistema = s.sis_id
		WHERE o.status NOT IN ( 4, 12, 29 )
		  AND o.data_fechamento IS NULL
		GROUP BY  s.sistema";
	$resultado = $PDO->query($query);
	$linhas = $resultado->rowCount();
	if ($linhas==0) 
			   {
		       		$aviso = "N�o h� dados no per�odo informado. <br>Refa�a sua pesquisa. ";
			        $origem = 'javascript:history.back()';
			        session_register("aviso");
			        session_register("origem");
		            echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
		       } 
	else //if($linhas==0)
		   	   	{
                            echo "\n<br><br>";
							$background = '#CDE5FF';	 
							print "<p class='titulo' >Chamados em Aberto X �REA DE ATENDIMENTO</p>";
                            print "<table class='centro' cellspacing='0' border='1' align='center'>";
							
                            print "<tr><td bgcolor=$background><B>�REA DE ATENDIMENTO</td>
                                       <td bgcolor=$background ><B>QUANTIDADE</td>
								  </tr>";
                              $total = 0;    
							 while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
								print "<tr>";
								print "<td>".$row['sistema']."</td><td>".$row['quant']."</td>";
								print "</tr>";
								$total+=$row['quant'];
							}							 
							print "<tr><td><b>TOTAL</b></td><td><b>".$total."</b></td></tr>";
							print "<div style='text-align:center'>";
							print "<img src='graph_areas_aberto.php'>";
							print "</div>";												
				}

?>
</body>
</html>

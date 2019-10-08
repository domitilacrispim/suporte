<?

        include ("var_sessao.php");      
        include ("funcoes.inc");
       	include ("javascript/funcoes.js");
		include ("config.inc.php");
       	include ("logado.php");

        $hoje = date("d-m-Y H:i:s");
		

		
		//TIPO: tipo de relatório - formatação específica
		//SQL: Query no banco de dados
		//CAMPOS: Array com o nome dos campos que eu quero imprimir no relatório
		//HEADERS: Array com os cabeçalhos de cada coluna do relatório
		function gera_relatorio_temp($tipo,$sql,$campos,$headers,$logo,$msg1,$msg2,$msg3){
			//Estilo aplicado nos relatórios
			print "<style type=\"text/css\"><!--";
			print "table.relatorio_1 {width:80%; margin-left:auto; margin-right: auto; text-align:left; 
					border: 0px; border-spacing:1 ;background-color:white; padding-top:10px; 
					page-break-after: auto;}";
			print "td.linha {font-family:arial; font-size:12px; line-height:0.8em;}";			
			print "td.linha_par {font-family:arial; font-size:12px; line-height:0.8em; background-color:#EAEAEA}";
			print "td.linha_impar {font-family:arial; font-size:12px; line-height:0.8em;background-color:#C8C8C8}";
			print "td.cabs {font-family:arial; font-size:12px; font-weight:bold; background-color: #A3A352;}";
			print "td.foot {font-family:arial; font-size:12px; font-weight:bold; line-height:0.8em; background-color: #A8A8A8;}";
			//print "{page-break-after: always;}";
			print "--></STYLE>";			
			
			if (count($campos) != count($headers))  {//Verifica se cada campo da tabela possui um header!
				print "O número de campos não fecha com o número de headers!";
				exit;
			}//if campos == headers
			
			
			$commit = mysql_query($sql);
        	$linhas = mysql_num_rows($commit);
			$k=0;
			while($k < mysql_num_fields($commit)){ //quantidade de campos retornados da consulta
				$field = mysql_fetch_field($commit,$k);//Retorna um objeto com informações dos campos
				$fields.=$field->name; //Joga os nomes dos campos para uma string
				$k++;
			} // while
			
			
			
			
			if ($linhas==0) {
			    print "Nenhuma linha retornada pela consulta";
			}else{
				print cabecalho($logo,$msg1,$msg2,$msg3);
				
				
				if ($tipo==1|| $tipo==0) {//Tipo definido de relatório //
					print "<TABLE class=\"relatorio_1\" cellpadding=4>";
					print "<tr>";		
					for ($i=0; $i<count($headers); $i++){
						print "<td class=\"cabs\">".$headers[$i]."</td>";	
					}//for
					print "</tr>";				
					$l = 0; //variável que controla se a linha é par ou impar
					while($row=mysql_fetch_array($commit)){
						if ($l % 2) {
							$par_impar = "_par";
						} else {
							$par_impar = "_impar";
						}//if - else
						print "<tr>";		
						for ($i=0; $i<count($campos); $i++){ //IMPRIME CAMPO A CAMPO
							print "<td class=\"linha$par_impar\">";
							$sep = explode(",",$campos[$i]); //Se algum campo passado tem mais de um argumento é separado
							for ($j=0; $j<count($sep); $j++){
								$pos = strpos($fields,$sep[$j]); //Verifica na string gerada se o argumento passado existe como um nome de campo
								if ($pos===false) {
									print $sep[$j]; //Se o campo não existe é impresso literalmente
								} else
									print $row[$sep[$j]]; //Se o campo existe é impresso seu valor;
							} //for J//
							print "</td>";
						}//for i//
						print "</tr>";
						$l++;
					} // while	
						//RODAPÉ				
						print "<tr>";		
						for ($i=0; $i<count($campos); $i++){ //IMPRIME CAMPO A CAMPO
							if ($i==count($campos)-1) {
							    $total = $linhas;
							} else
							if ($i==count($campos)-2) {
							    $total = "TOTAL";
							}
							print "<td class=\"foot\">$total</td>";	
						}//for
						print "</tr>";
					print "</table>";
				} else 
				
				if ($tipo==2) {//Outra formatação para saída do relatório//
					print "<table class=\"relatorio_1\">";
					while($row=mysql_fetch_array($commit)){
						//print "<tr>";		
						for ($i=0; $i<count($campos); $i++){ //IMPRIME CAMPO A CAMPO
							print "<tr>";
							//print "<td>".$headers[$i]."</td>";
							print "<td>";
							$sep = explode(",",$campos[$i]); //Se algum campo passado tem mais de um argumento é separado
							for ($j=0; $j<count($sep); $j++){
								$pos = strpos($fields,$sep[$j]); //Verifica na string gerada se o argumento passado existe como um nome de campo
								if ($pos===false) {
									print $sep[$j]; //Se o campo não existe é impresso literalmente
								} else {
									print $row[$sep[$j]]; //Se o campo existe é impresso seu valor;
								}
								//print "</td>";
							} //for J//
							print "</td>";
						
						
						}//for i//
						print "</tr>";
					}//while
					print "</table>";
				} //fim do tipo==2 //
			
			
			
			} //else linhas != 0 //
		}//função
		
####################################################################################################		


 $query = "SELECT c.comp_inv as etiqueta, upper(c.comp_sn) as serial, c.comp_nome as nome, 
 			c.comp_nf as nota, inst.inst_nome as instituicao, inst.inst_cod as cod_inst,
 			c.comp_coment as comentario, c.comp_valor as valor, c.comp_data_compra as
			data_compra, c.comp_ccusto as ccusto, c.comp_situac as situacao, c.comp_data as data,
			equip.tipo_nome as equipamento, equip.tipo_cod as tipo_equipamento,
			t.tipo_imp_nome as impressora, loc.local, loc.loc_id as tipo_local, 
			loc.loc_reitoria as reitoria_cod, reit.reit_nome as reitoria,
			mb_fabricante as mb_fabricante, 
			mb_modelo as mb_modelo, proc.proc_fabricante as proc_fabricante, 
			proc.proc_modelo as proc_modelo, proc.proc_clock as proc_clock, 
			me.memo_desc as memoria, vid.vid_fabricante as vid_fabricante, 
			vid.vid_modelo as vid_modelo, som.som_fabricante as som_fabricante, 
			som.som_modelo as som_modelo, rede.rede_fabricante as rede_fabricante, 
			rede.rede_modelo as rede_modelo, hd.hd_modelo as hd_modelo, 
			hd.hd_fabricante as hd_fabricante, hd.hd_capacidade as hd_capacidade, 
			mo.mod_fabricante as mod_fabricante, mo.mod_modelo as mod_modelo, 
			cd.cd_fabricante as cd_fabricante, cd.cd_velocidade as cd_velocidade,
			dvd.dvd_fabricante as dvd_fabricante, dvd.dvd_velocidade as dvd_velocidade, 
			gr.grav_fabricante as grav_fabricante, gr.grav_velocidade as grav_velocidade, 
			fab.fab_nome as fab_nome, fab.fab_cod as fab_cod, fo.forn_cod as fornecedor_cod, 
			fo.forn_nome as fornecedor_nome, model.marc_cod as modelo_cod, model.marc_nome as modelo,
			pol.pole_cod as polegada_cod, pol.pole_nome as polegada_nome, 
			res.resol_cod as resolucao_cod, res.resol_nome as resol_nome,
			sit.situac_cod as situac_cod, sit.situac_nome as situac_nome,
			date_add(c.comp_data_compra, interval tmp.tempo_meses month)as vencimento
		
		FROM ((((((((((((((((((computadores as c left join  tipo_imp as t on 
			t.tipo_imp_cod = c.comp_tipo_imp) left join polegada as pol on c.comp_polegada
			 = pol.pole_cod) left join resolucao as res on c.comp_resolucao = res.resol_cod)
			left join mbs as mb on c.comp_mb = mb.mb_cod) left join cdroms as cd on cd.cd_cod =
			c.comp_cdrom) left join dvds as dvd on dvd.dvd_cod = c.comp_dvd) left join fabricantes
			as fab on fab.fab_cod = c.comp_fab) left join fornecedores as fo on fo.forn_cod =
			c.comp_fornecedor) left join gravadores as gr on gr.grav_cod = c.comp_grav) 
			left join hds as hd on hd.hd_cod = c.comp_modelohd) left join memorias as me 
			on me.memo_cod = c.comp_memo) left join modens as mo on mo.mod_cod = 
			c.comp_modem) left join processadores as proc on proc.proc_cod = c.comp_proc) 
			left join rede_placas as rede on rede.rede_cod = c.comp_rede) left join 
			som_placas as som on som.som_cod = c.comp_som) left join vid_placas as vid on
			vid.vid_cod = c.comp_video)left join situacao as sit on sit.situac_cod = c.comp_situac)
			left join tempo_garantia as tmp on tmp.tempo_cod =c.comp_garant_meses),
			
			localizacao as loc, instituicao as inst, marcas_comp as model, tipo_equip as equip,
			reitorias as reit
            WHERE
 			((c.comp_local = loc.loc_id) and
			(c.comp_inst = inst.inst_cod) and (c.comp_marca = model.marc_cod) and 
			(c.comp_tipo_equip = equip.tipo_cod) and (loc.loc_reitoria = reit.reit_cod) 
			and comp_tipo_equip=1
			
			) order by etiqueta limit 0,100";
			
		
		$cabs = array();
		$cabs[]= "Etiqueta";
		$cabs[]= "Tipo";
		$cabs[]= "Unidade";
		$cabs[]= "Modelo";
		$cabs[]= "Setor";
		$cabs[]= "N.S.";
		//$cabs[]= "Memória";
		
		$campos = array(); //Campos das tabelas que serão impressos no relatório;
		$campos[]= "etiqueta";
		$campos[]= "equipamento";
		$campos[]= "instituicao";
		$campos[]= "fab_nome, ,modelo"; //Dois campos concatenados
		$campos[]= "local";
		$campos[]= "serial";
		//$campos[]= "memoria, MB";
		

		gera_relatorio_temp(0,$query,$campos,$cabs,'logo_lasalle.gif','RELATÓRIO',$hoje,'RELATÓRIO DE TESTE');

		

?>
<?php

$QRY["total_equip"] = "SELECT count(*)total from equipamentos";



$QRY["full_detail_ini"] = "SELECT c.comp_inv as etiqueta, c.comp_sn as serial, c.comp_nome as nome, 
 			c.comp_nf as nota, inst.inst_nome as instituicao, inst.inst_cod as cod_inst,
 			c.comp_coment as comentario, c.comp_valor as valor, c.comp_data as data_cadastro, 
			c.comp_data_compra as data_compra, c.comp_ccusto as ccusto, c.comp_situac as situacao, 
			c.comp_local as tipo_local, loc.loc_reitoria as reitoria_cod, reit.reit_nome as reitoria,
			c.comp_mb as tipo_mb, c.comp_proc as tipo_proc,
			c.comp_tipo_equip as tipo, c.comp_memo as tipo_memo, c.comp_video as tipo_video,
			c.comp_modelohd as tipo_hd, c.comp_modem as tipo_modem, c.comp_cdrom as tipo_cdrom,
			c.comp_dvd as tipo_dvd, c.comp_grav as tipo_grav, c.comp_resolucao as tipo_resol,
			c.comp_polegada as tipo_pole, c.comp_tipo_imp as tipo_imp, c.comp_assist as assistencia_cod,
			equip.tipo_nome as equipamento, c.comp_rede as tipo_rede, c.comp_som as tipo_som,
			t.tipo_imp_nome as impressora, loc.local, 
			
			proc.mdit_fabricante as fabricante_proc, proc.mdit_desc as processador, proc.mdit_desc_capacidade as clock, proc.mdit_cod as cod_processador,
			proc.mdit_sufixo as proc_sufixo,
			hd.mdit_fabricante as fabricante_hd, hd.mdit_desc as hd, hd.mdit_desc_capacidade as hd_capacidade,hd.mdit_cod as cod_hd,
			hd.mdit_sufixo as hd_sufixo,
			vid.mdit_fabricante as fabricante_video, vid.mdit_desc as video, vid.mdit_cod as cod_video,
			red.mdit_fabricante as rede_fabricante, red.mdit_desc as rede, red.mdit_cod as cod_rede,
			md.mdit_fabricante as fabricante_modem, md.mdit_desc as modem, md.mdit_cod as cod_modem,
			cd.mdit_fabricante as fabricante_cdrom, cd.mdit_desc as cdrom, cd.mdit_cod as cod_cdrom,
			grav.mdit_fabricante as fabricante_gravador, grav.mdit_desc as gravador, grav.mdit_cod as cod_gravador,
			dvd.mdit_fabricante as fabricante_dvd, dvd.mdit_desc as dvd, dvd.mdit_cod as cod_dvd,
			mb.mdit_fabricante as fabricante_mb, mb.mdit_desc as mb, mb.mdit_cod as cod_mb,
			memo.mdit_desc_capacidade as memoria, memo.mdit_cod as cod_memoria, memo.mdit_sufixo as memo_sufixo,
			som.mdit_fabricante as fabricante_som, som.mdit_desc as som, som.mdit_cod as cod_som, 

			fab.fab_nome as fab_nome, fab.fab_cod as fab_cod, fo.forn_cod as fornecedor_cod, 
			fo.forn_nome as fornecedor_nome, model.marc_cod as modelo_cod, model.marc_nome as modelo,
			pol.pole_cod as polegada_cod, pol.pole_nome as polegada_nome, 
			res.resol_cod as resolucao_cod, res.resol_nome as resol_nome,
			sit.situac_cod as situac_cod, sit.situac_nome as situac_nome,

			tmp.tempo_meses as tempo, tmp.tempo_cod as tempo_cod,
			tp.tipo_garant_nome as tipo_garantia, tp.tipo_garant_cod as garantia_cod,
			
			date_add(c.comp_data_compra, interval tmp.tempo_meses month)as vencimento,
            soft.soft_desc as software, soft.soft_versao as versao,
			assist.assist_desc as assistencia
		
		FROM ((((((((((((((((((((((((equipamentos as c left join  tipo_imp as t on 
			t.tipo_imp_cod = c.comp_tipo_imp) left join polegada as pol on c.comp_polegada
			 = pol.pole_cod) left join resolucao as res on c.comp_resolucao = res.resol_cod)
			left join fabricantes as fab on fab.fab_cod = c.comp_fab) 
			left join fornecedores as fo on fo.forn_cod = c.comp_fornecedor) 
			left join situacao as sit on sit.situac_cod = c.comp_situac)
			left join tempo_garantia as tmp on tmp.tempo_cod =c.comp_garant_meses)
			left join tipo_garantia as tp on tp.tipo_garant_cod = c.comp_tipo_garant)
			
			left join assistencia as assist on assist.assist_cod = c.comp_assist)
			
			left join modelos_itens as proc on proc.mdit_cod = c.comp_proc)
			left join modelos_itens as hd on hd.mdit_cod = c.comp_modelohd)
			left join modelos_itens as vid on vid.mdit_cod = c.comp_video)
			left join modelos_itens as red on red.mdit_cod = c.comp_rede)
			left join modelos_itens as md on md.mdit_cod = c.comp_modem)
			left join modelos_itens as cd on cd.mdit_cod = c.comp_cdrom)
			left join modelos_itens as grav on grav.mdit_cod = c.comp_grav)
			left join modelos_itens as dvd on dvd.mdit_cod = c.comp_dvd)
			left join modelos_itens as mb on mb.mdit_cod = c.comp_mb)
			left join modelos_itens as memo on memo.mdit_cod = c.comp_memo)
			left join modelos_itens as som on som.mdit_cod = c.comp_som)
            
			left join hw_sw as hw on hw.hws_hw_cod = c.comp_inv and hw.hws_hw_inst = c.comp_inst)
			left join softwares as soft on soft.soft_cod = hw.hws_sw_cod)
			
			left join localizacao as loc on loc.loc_id = c.comp_local)
			left join reitorias as reit on reit.reit_cod = loc.loc_id),
            
			instituicao as inst, marcas_comp as model, tipo_equip as equip
        WHERE
 			(c.comp_inst = inst.inst_cod) and (c.comp_marca = model.marc_cod) and 
			(c.comp_tipo_equip = equip.tipo_cod) ";
            
$QRY["full_detail_fim"] = " group by comp_inv, comp_inst";            

$QRY["garantia"] = "SELECT c.comp_inv as inventario, i.inst_nome as instituicao, 
			i.inst_cod as instituicao_cod,
			c.comp_data_compra as aquisicao,
			ti.tipo_garant_nome as garantia,  t.tempo_meses as meses, date_add(comp_data_compra, interval tempo_meses month) 
			as vencimento,
			extract(day from date_add(comp_data_compra, 
			interval tempo_meses month)) as dia, 
			extract(month from date_add(comp_data_compra, 
			interval tempo_meses month)) as mes, 
			extract(year from date_add(comp_data_compra, 
			interval tempo_meses month)) as ano,
			f.forn_nome as fornecedor, 
			f.forn_fone as contato 
		FROM equipamentos as c 
			left join tempo_garantia as t on c.comp_garant_meses = t.tempo_cod
			left join tipo_garantia as ti on ti.tipo_garant_cod = c.comp_tipo_garant 
			left join fornecedores as f on f.forn_cod = c.comp_fornecedor,
			instituicao as i 
		WHERE c.comp_garant_meses is not null and 
			c.comp_data_compra<>'0000:00:00 00:00'  and 
			c.comp_inst=i.inst_cod ";
 
// monitores não inclusos
$QRY["vencimentos"] = "SELECT count(*) AS quantidade, 
                 date_add(date_format(comp_data_compra, '%Y-%m-%d') , INTERVAL tempo_meses MONTH) AS vencimento, 
                 marc_nome AS modelo, fab_nome AS fabricante, tipo_nome AS tipo
		FROM equipamentos, tempo_garantia, marcas_comp, fabricantes, tipo_equip
		WHERE date_add(comp_data_compra, INTERVAL tempo_meses MONTH) >= curdate() 
                AND comp_garant_meses = tempo_cod AND comp_tipo_equip NOT IN (5) 
                AND comp_marca = marc_cod AND comp_fab = fab_cod AND comp_tipo_equip = tipo_cod 
                AND (date_format(curdate() , '%Y') = date_format(date_add(comp_data_compra, INTERVAL tempo_meses MONTH) , '%Y') 
                OR date_format(curdate() , '%Y' )+3>= date_format(date_add(comp_data_compra, INTERVAL tempo_meses MONTH) , '%Y' ))
		GROUP BY vencimento, modelo
		ORDER BY vencimento, modelo";

        
$QRY["ocorrencias_full_ini"] = "SELECT 
				o.numero as numero, o.problema as prob_cod, o.descricao as descricao, o.equipamento as etiqueta, 
				o.sistema as area_cod, o.contato as contato, o.telefone as telefone, o.local as setor_cod, 
				o.operador as operador_cod, o.data_abertura as data_abertura, o.data_fechamento as data_fechamento, 
				o.status as status_cod, o.data_atendimento as data_atendimento, o.instituicao as unidade_cod, 
				o.aberto_por as aberto_por_cod, 
				
				i.inst_nome as unidade,

				p.problema as problema, p.prob_area as prob_area_cod, p.prob_sla as sla_solucao_cod, 
				
				a.sistema as area, a.sis_email as area_email, a.sis_atende as sis_atende,
				
				l.local as setor, l.loc_reitoria as reitoria_cod, l.loc_prior as loc_prior_cod, l.loc_dominio as dominio_cod, 
				l.loc_predio as predio_cod,
				
				pr.prior_nivel as prioridade_nivel, pr.prior_sla as sla_resposta_cod,
				
				u.login as login, u.nome as nome, u.email as user_email, u.AREA as user_area, u.user_admin as user_admin,
				
				ua.nome as aberto_por,
				
				s.status as chamado_status, s.stat_cat as stat_cat_cod, s.stat_painel as stat_painel_cod,
				
				stc.stc_desc as status_cat,
				
				sls.slas_desc as sla_solucao, sls.slas_tempo as sla_solucao_tempo,
				
				slr.slas_desc as sla_resposta, slr.slas_tempo as sla_resposta_tempo
			FROM
				ocorrencias as o left join sistemas as a on a.sis_id = o.sistema
				left join localizacao as l on l.loc_id = o.local
				left join instituicao as i on i.inst_cod = o.instituicao
				left join usuarios as u on u.user_id = o.operador
				left join usuarios as ua on ua.user_id = o.aberto_por
				left join `status` as s on s.stat_id = o.status
				left join status_categ as stc on stc.stc_cod = s.stat_cat
				left join problemas as p on p.prob_id = o.problema
				left join sla_solucao as sls on sls.slas_cod = p.prob_sla
				left join prioridades as pr on pr.prior_cod = l.loc_prior
				left join sla_solucao as slr on slr.slas_cod = pr.prior_sla 
				
				";
			

$QRY["useropencall"]= "SELECT c.*, a.*, b.sistema as ownarea, b.sis_id as ownarea_cod ".
					"FROM configusercall as c, sistemas as a, sistemas as b ".
					"WHERE c.conf_opentoarea = a.sis_id and c.conf_ownarea = b.sis_id";

$QRY["locais"] = "SELECT l .  * , r.reit_nome, pr.prior_nivel AS prioridade, d.dom_desc AS dominio, pred.pred_desc as predio, c.descricao as centro_custo
		FROM localizacao AS l
		LEFT  JOIN reitorias AS r ON r.reit_cod = l.loc_reitoria
		LEFT  JOIN prioridades AS pr ON pr.prior_cod = l.loc_prior
		LEFT  JOIN dominios AS d ON d.dom_cod = l.loc_dominio
		LEFT JOIN predios as pred on pred.pred_cod = l.loc_predio 
		LEFT OUTER JOIN CCUSTO c ON c.codigo = l.cod_custo ";
		

$QRY["categorias_status"] = "select count(*) total, s.*, stc.* ".
			"from ocorrencias o left join `status` s on o.status = s.stat_id ".
			"left join status_categ stc on stc.stc_cod = s.stat_cat WHERE s.stat_painel in(1,2) group by stc.stc_desc";					
					
			
?>
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
	
	$s_page_invmon = "incluir_computador.php";
	session_register("s_page_invmon");			

	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);
	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);


	$hoje = date("Y-m-d H:i:s");
	$hojeLog = date("d-m-Y H:i:s");
	$nulo = null;
		
	$habilita = "disabled";

	
		
	if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))) {
		
		$queryA = "SELECT 

			mold.mold_marca as padrao,
			mold.mold_inv as etiqueta, mold.mold_sn as serial, mold.mold_nome as nome, 
 			mold.mold_nf as nota,			
			
 			mold.mold_coment as comentario, mold.mold_valor as valor, mold.mold_data_compra as
			data_compra, mold.mold_ccusto as ccusto,
			
			inst.inst_nome as instituicao, inst.inst_cod as cod_inst,
			
			equip.tipo_nome as equipamento, equip.tipo_cod as equipamento_cod,
			
			t.tipo_imp_nome as impressora, t.tipo_imp_cod as impressora_cod,
			
			loc.local as local, loc.loc_id as local_cod,

			
			proc.mdit_fabricante as fabricante_proc, proc.mdit_desc as processador, proc.mdit_desc_capacidade as clock, proc.mdit_cod as cod_processador,
			hd.mdit_fabricante as fabricante_hd, hd.mdit_desc as hd, hd.mdit_desc_capacidade as hd_capacidade,hd.mdit_cod as cod_hd,
			vid.mdit_fabricante as fabricante_video, vid.mdit_desc as video, vid.mdit_cod as cod_video,
			red.mdit_fabricante as rede_fabricante, red.mdit_desc as rede, red.mdit_cod as cod_rede,
			md.mdit_fabricante as fabricante_modem, md.mdit_desc as modem, md.mdit_cod as cod_modem,
			cd.mdit_fabricante as fabricante_cdrom, cd.mdit_desc as cdrom, cd.mdit_cod as cod_cdrom,
			grav.mdit_fabricante as fabricante_gravador, grav.mdit_desc as gravador, grav.mdit_cod as cod_gravador,
			dvd.mdit_fabricante as fabricante_dvd, dvd.mdit_desc as dvd, dvd.mdit_cod as cod_dvd,
			mb.mdit_fabricante as fabricante_mb, mb.mdit_desc as mb, mb.mdit_cod as cod_mb,
			memo.mdit_desc as memoria, memo.mdit_cod as cod_memoria,
			som.mdit_fabricante as fabricante_som, som.mdit_desc as som, som.mdit_cod as cod_som, 

			
			fab.fab_nome as fab_nome, fab.fab_cod as fab_cod,
			
			fo.forn_cod as fornecedor_cod, fo.forn_nome as fornecedor_nome, 
			
			model.marc_cod as modelo_cod, model.marc_nome as modelo,
			
			pol.pole_cod as polegada_cod, pol.pole_nome as polegada_nome, 
			
			res.resol_cod as resolucao_cod, res.resol_nome as resol_nome
			 

		FROM ((((((((((((((((((moldes as mold 
			left join  tipo_imp as t on	t.tipo_imp_cod = mold.mold_tipo_imp) 
			left join polegada as pol on mold.mold_polegada = pol.pole_cod) 
			left join resolucao as res on mold.mold_resolucao = res.resol_cod)
			left join fabricantes as fab on fab.fab_cod = mold.mold_fab) 
			left join fornecedores as fo on fo.forn_cod = mold.mold_fornecedor) 

			left join modelos_itens as proc on proc.mdit_cod = mold.mold_proc)
			left join modelos_itens as hd on hd.mdit_cod = mold.mold_modelohd)
			left join modelos_itens as vid on vid.mdit_cod = mold.mold_video)
			left join modelos_itens as red on red.mdit_cod = mold.mold_rede)
			left join modelos_itens as md on md.mdit_cod = mold.mold_modem)
			left join modelos_itens as cd on cd.mdit_cod = mold.mold_cdrom)
			left join modelos_itens as grav on grav.mdit_cod = mold.mold_grav)
			left join modelos_itens as dvd on dvd.mdit_cod = mold.mold_dvd)
			left join modelos_itens as mb on mb.mdit_cod = mold.mold_mb)
			left join modelos_itens as memo on memo.mdit_cod = mold.mold_memo)
			left join modelos_itens as som on som.mdit_cod = mold.mold_som)

			left join instituicao as inst on inst.inst_cod = mold.mold_inst) 
			left join localizacao as loc on loc.loc_id = mold.mold_local),
			
			
			marcas_comp as model, tipo_equip as equip
			
		WHERE 
		
			(mold.mold_tipo_equip = equip.tipo_cod) and
			(mold.mold_marca = $comp_marca) and (mold.mold_marca = model.marc_cod)";
	
	        $resultadoA = mysql_query($queryA);
	        $linhasA = mysql_num_rows($resultadoA);
	        $row = mysql_fetch_array($resultadoA);
	
	        if (mysql_num_rows($resultadoA)>0)
	        {
	                $linhasA = mysql_num_rows($resultadoA)-1;
	        }
	        else
	        {
	                $linhasA = mysql_num_rows($resultadoA);
	        }
	
	
	}

	print "<BR>";
	print "<B>".$TRANS["head_inc_equip"].":"; 
	print "<BR>";

print "<FORM name='form1' method='POST' action='".$PHP_SELF."'  ENCTYPE='multipart/form-data'  onSubmit=\"return valida()\">";

?>
<TABLE border="0" colspace="3" width="100%" >
       
		<tr> <td colspan="4"></td> <b> <?print $TRANS["dados_gerais"];?>:</b></td></tr>

		<tr>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><a title="Campo obrigatório - Defina o tipo de equipamento que está cadastrando"><?print $TRANS["cx_tipo"]?>:</a></b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                 <? 
				 print "<SELECT class='select' name='comp_tipo_equip' size=1 onChange=\"fillSelectFromArray(this.form.comp_marca, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));\">";
			
				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print"<option value=$row[equipamento_cod] selected>$row[equipamento]</option>";} else
					//print "<option value=-1 selected>TESTANDO</option>";} else
					
					print "<option value=-1 selected>".$TRANS["cmb_selec_equip"]."</option>";
                $query = "SELECT * from tipo_equip order by tipo_nome";
                $resultado = mysql_query($query);
                while ($rowTipo = mysql_fetch_array($resultado))
                {
                       print "<option value='".$rowTipo['tipo_cod']."'>".$rowTipo['tipo_nome']."</option>";
                }
                ?>
                </SELECT>
                </TD>

				<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><a title="Campo obrigatório - Selecione o nome do fabricante do equipamento"><?print $TRANS["cx_fab"]?>:*</a> </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_fab' size=1>";
                
				//if (($ok2 == "Carregar Config") && (($comp_marca !=-1))) 
					//print"<option value=$row['fab_cod'] selected>$row[fab_nome]</option>"; else
								
				print "<option value=-1>".$TRANS["cmb_selec_fab"].": </option>";
                $query = "SELECT * from fabricantes  order by fab_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
              	$i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);
					   echo "\"";
					   if (mysql_result($resultado,$i,0)==$row['fab_cod']) {
					     echo "selected";  
					   }
					   ?>>
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>
                </TD>
 		</tr>
		
		
		
		<TR>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><a title="Campo obrigatório - Preencha com o número da etiqueta que foi colada ao equipamento"><?print $TRANS["cx_etiqueta"]?>*:</a></b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="comp_inv"  value="<?$comp_inv?>" id="idEtiqueta"></TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_sn"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="comp_sn" maxlength="30" size="30"></TD>
       </TR>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><a title="Campo obrigatório - Selecione o modelo do equipamento que está cadastrando"><?print $TRANS["cx_modelo"]?>*:</a></b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_marca' size=1>";

				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print"<option value=$row[modelo_cod] selected>$row[modelo]</option>";} else

                print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "SELECT * from marcas_comp order by marc_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($rowMarcas= mysql_fetch_array($resultado))
                {
                       print "<option value='".$rowMarcas['marc_cod']."'";
					   if ($rowMarcas['marc_cod']==$comp_marca) print " selected";
					   print ">".$rowMarcas['marc_nome']."</option>";
                }
                
				print "</select>";
				print "<input type='button' name='modelo' value='Novo' class='minibutton' onClick=\"javascript:mini_popup('incluir_marca_comp.php?action=incluir&popup=true')\">";
				print "</td>";
				?>
					
			
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><a title="Campo Obrigatório - Selecione o setor onde este equipamento está localizado"><?print $TRANS["cx_local"]?>*:</a></b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_local' size=1>";
                print "<option value=-1 selected>".$TRANS["cmb_selec_local"]."</option>";
                $query = "SELECT * from localizacao  order by local";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>
                </TD>			
		</tr>
		<tr>
 		        <td colspan="2" width="20%" align="left"><input type="submit" class="button" value="<?print $TRANS["bt_carrega_config"]?>" name="ok2"  title="Carrega as configurações de hardware do modelo selecionado" disabled>
				<input type='button' class='button' name='configuracao' value='Cadastrar configuração' onClick="redirect('incluir_molde.php')"></td>
				<td colspan="2" width="80"></td>
		</tr>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><a title="Campo Obrigatório - Selecione a situação do equipamento"><?print $TRANS["cx_situacao"]?>*:</a></b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_situac' size=1>";

				//if (($ok2 == "Carregar Config") && (($comp_marca !=-1)))  { 
					//print"<option value=$row[modelo_cod] selected>$row[modelo]</option>";} else

                print "<option value=-1 selected>".$TRANS["cmb_selec_situacao"]."</option>";
				$query = "SELECT * from situacao order by situac_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                
                }
                print "</select>";
                print "</td>";
                
                print "<td width='20%' bgcolor='".TD_COLOR."'><b>Anexar imagem</b></td>";
		print "<TD width='30%' align='left' bgcolor=".BODY_COLOR."><INPUT type='file' class='text' name='img' id='idImg'></TD>";
                
                
                
                print "</tr>";
                
                
                
                ?>
   <!--  --------------------------------------------------------------------------------------- -->

	
	<TR>
		<td colspan="4"></td>
    </TR>		
	<tr> <td colspan="3"><b><?print $TRANS["dados_config"];?>:</b></td><td><input type="button" class="button" value="<?print $TRANS["bt_componente"]?>" Onclick="return popup_alerta('incluir_item.php?popup=true')"></td></tr>
	<TR>
		<td colspan="4"></td>
    </TR>		


   
   <!--  --------------------------------------------------------------------------------------- --> 
   
        <tr>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_nome"]?>:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="comp_nome" maxlength="15" size="15"></TD>
         
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_mb"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select'  name='comp_mb' size=1>";
                
				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print "<option value=".$row['cod_mb']." selected>".$row['fabricante_mb']." ".$row['mb']."</option>";} else
					
					print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				
				$query = "select * from modelos_itens where mdit_tipo = 10 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
                
				print "<option value=-1>".$TRANS["cmb_selec_nenhum"]."</option>";
				?>
					
		                
				</SELECT>
                </TD>
		 </tr>
		
		
	
	   
	   
	   
	    <tr>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_proc"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_proc' size=1>";
                
				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print"<option value=$row[cod_processador] selected>$row[processador] $row[clock] MHZ</option>";} else
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
                
				$query = "select * from modelos_itens where mdit_tipo = 11 order by mdit_fabricante, mdit_desc, mdit_desc_capacidade";
				$commit = mysql_query($query);
				$sufixo = "MHZ";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
				print "<option value=-1>".$TRANS["cmb_selec_nenhum"]."</option>";
				?>
											                                
				</SELECT>
                </TD>


                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_memo"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_memo' size=1>";
                
				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print"<option value=$row[cod_memoria] selected>$row[memoria] MB</option>";} else
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 7 order by mdit_fabricante, mdit_desc, mdit_desc_capacidade";
				$commit = mysql_query($query);
				$sufixo = "MB";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
				print "<option value=-1>".$TRANS["cmb_selec_nenhum"]."</option>";
				?>
								                                
				</SELECT>
                </TD>
			</tr>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_video"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_video' size=1>";
                
				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print"<option value=$row[cod_video] selected>$row[fabricante_video] $row[video]</option>";} else
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 2 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
				print "<option value=-1>".$TRANS["cmb_selec_nenhum"]."</option>";
				?>
								                                
				</SELECT>
                </TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_som"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_som' size=1>";
                
				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print"<option value=$row[cod_som] selected>$row[fabricante_som] $row[som]</option>";} else
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 4 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
				print "<option value=-1>".$TRANS["cmb_selec_nenhum"]."</option>";				?>
							                                
				</SELECT>
                </TD>
		</tr>
        
		<TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_rede"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_rede' size=1>";
                
				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print"<option value=$row[cod_rede] selected>$row[fabricante_rede] $row[rede]</option>";} else
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 3 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
				print "<option value=-1>".$TRANS["cmb_selec_nenhum"]."</option>";				?>
								                                
				</SELECT>
                </TD>


                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_modem"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_modem' size=1>";
                
				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print"<option value=$row[cod_modem] selected>$row[fabricante_modem] $row[modem]</option>";} else
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 6 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
				print "<option value=-1>".$TRANS["cmb_selec_nenhum"]."</option>";
                ?>
								                                
				</SELECT>
                </TD>
		</tr>


        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_hd"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_modelohd' size=1>";
                
				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print"<option value=$row[cod_hd] selected>$row[fabricante_hd] $row[hd] $row[hd_capacidade] GB</option>";} else
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 1 order by mdit_fabricante, mdit_desc_capacidade";
				$commit = mysql_query($query);
				$sufixo = "GB";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
				print "<option value=-1>".$TRANS["cmb_selec_nenhum"]."</option>";				?>
							                                
				</SELECT>
                </TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_grav"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_grav' size=1>";
                
				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print"<option value=$row[cod_gravador] selected>$row[fabricante_gravador] $row[gravador]</option>";} else
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 9 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
				print "<option value=-1>".$TRANS["cmb_selec_nenhum"]."</option>";?>
								                                
				</SELECT>
                </TD>
        </tr>
		
		<TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_cdrom"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_cdrom' size=1>";
                
				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print"<option value=$row[cod_cdrom] selected>$row[fabricante_cdrom] $row[cdrom]</option>";} else
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 5 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
				print "<option value=-1>".$TRANS["cmb_selec_nenhum"]."</option>";
				
				?>
								                                
				</SELECT>
                </TD>



                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_dvd"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_dvd' size=1>";
                
				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print"<option value=$row[cod_dvd] selected>$row[fabricante_dvd] $row[dvd]</option>";} else
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 8 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
				print "<option value=-1>".$TRANS["cmb_selec_nenhum"]."</option>";
				?>
							                                
				</SELECT>
                </TD>
			</tr>


	<TR>
		<td colspan="4"></td>
    </TR>		
	<tr> 
		<td colspan="4"><b><?print $TRANS["dados_extra"];?>:</b></td>
	</tr>
	
	<TR>
		<td colspan="4"></td>
    </TR>		
	
	
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_impressora"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_tipo_imp' size=1>";
                
				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print"<option value=$row[impressora_cod] selected>$row[impressora]</option>";} else
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_imp"].": </option>";
                $query = "SELECT * from tipo_imp  order by tipo_imp_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
				print "<option value=-1>".$TRANS["cmb_selec_nenhum"]."</option>";
				?>
							                                
				</SELECT>
                </TD>
        


                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_monitor"]?>:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_polegada' size=1>";
                
				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print"<option value=$row[polegada_cod] selected>$row[polegada_nome]</option>";} else
				
				print "<option value =-1 selected>".$TRANS["cmb_selec_monitor"].": </option>";
                $query = "SELECT * from polegada  order by pole_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
				print "<option value=-1>".$TRANS["cmb_selec_nenhum"]."</option>";
				?>
									                                
				</SELECT>
                </TD>
              </tr>
			  <tr>  
				
				<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_scanner"]?>:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_resolucao' size=1>";
                
				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print"<option value=$row[resolucao_cod] selected>$row[resol_nome]</option>";} else
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_scanner"].": </option>";
                $query = "SELECT * from resolucao  order by resol_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
				print "<option value=-1>".$TRANS["cmb_selec_nenhum"]."</option>";
				?>
								                                
				</SELECT>
                </TD>
 			</tr>		
		
       
	<TR>
		<td colspan="4"></td>
    </TR>		
	<tr> <td colspan="4"><b> <?print $TRANS["dados_contab"];?>:</b></td></tr>
	<TR>
		<td colspan="4"></td>
    </TR>		

         


        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><a title="Campo Obrigatório - Selecione a Unidade proprietária desse equipamento"><?print $TRANS["cx_inst"]?>*:</a></b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_inst' size=1>";
                
				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print"<option value=$row[cod_inst] selected>$row[instituicao]</option>";} else
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_inst"]." </option>";
                $query = "SELECT * from instituicao  order by inst_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
                
				?>
                </SELECT>
                </TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_cc"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_ccusto' size=1>";
				
				print "<option value = -1 selected>".$TRANS["cmb_selec_cc"]." </option>";
                $query = "SELECT * from ".`DB_CCUSTO`.".".TB_CCUSTO."  order by ".CCUSTO_DESC.""; //where ano='2003'
                $resultado = mysql_query($query);
                while ($rowCcusto = mysql_fetch_array($resultado))
                {
					print "<option value='".$rowCcusto[CCUSTO_ID]."'>".$rowCcusto[CCUSTO_DESC]." - ".$rowCcusto[CCUSTO_COD]."</option>";
                }
                
				?>
                </SELECT>
                </TD>
		</tr>
		 
		 
		 

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_fornecedor"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_fornecedor' size=1>";
                
				if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					print"<option value=$row[fornecedor_cod] selected>$row[fornecedor_nome]</option>";} else
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_fornecedor"]."</option>";
                $query = "SELECT * from fornecedores  order by forn_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>
                </TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_nf"]?>:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="comp_nf" ></TD>
   	</tr>


        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_valor"]?>:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="comp_valor" id="idValor"></TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_data_compra"]?>:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="comp_data_compra" id="idDataCompra" ></TD>
        </tr>

		
<!--
#################################################################################
-->		
		


        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_tipo_garantia"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_tipo_garant' size=1>";
                
				//if (($ok2 == $TRANS["bt_carrega_config"]) && (($comp_marca !=-1))){ 
					//print"<option value=$row[cd_cod] selected>$row[cd_fabricante] $row[cd_velocidade]</option>";} else
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_tipo"]."</option>";
                $query = "SELECT * from tipo_garantia  order by tipo_garant_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
                print "<option value=-1 selected>".$TRANS["cmb_selec_tipo"]."</option>";
				?>
								                                
				</SELECT>
                </TD>



                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_tempo_garantia"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select' name='comp_garant_meses' size=1>";
                
				//if (($ok2 == "Carregar Config") && (($comp_marca !=-1))){ 
					//print"<option value=$row[dvd_cod] selected>$row[dvd_fabricante] $row[dvd_velocidade]</option>";} else
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_tempo"]."</option>";
                $query = "SELECT * from tempo_garantia  order by tempo_meses";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1).' meses';?>
                       </option>
                       <?
                       $i++;
                }
                print "<option value=-1 selected>".$TRANS["cmb_selec_tempo"]."</option>";
				?>
									                                
				</SELECT>
                </TD>
			</tr>


<!--
#################################################################################
-->		

		
		<tr>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_coment"]?>:</b></TD>
                <TD width="30%" colspan='3' align="left" bgcolor=<?print BODY_COLOR?>><TEXTAREA cols="75" rows="5" name="comp_coment"></textarea></TD>
        </TR>



        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b><?print $TRANS["cx_data_cadastro"]?>:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print datab($hoje);?></TD>
        </TR>

			

        <TR>
                <TD colspan="2"  align="right" bgcolor=<?print BODY_COLOR?>><input type="submit" value="<?print $TRANS["bt_cadastrar"]?>" name="ok" title="Cadastrar as informações fornecidas." disabled >
                      <!--  <input type="hidden" name="rodou" value="sim"> -->
                </TD>
                <TD colspan="2" align="right" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="<?print $TRANS["bt_cancelar"]?>" onClick="javascript:history.back()"></TD>
        </TR>

</TABLE>
</FORM>




		<?
                if ($ok=="Cadastrar")             
                {
                        $erro="não";

#############################################

			$querySN = "SELECT c.* FROM equipamentos as c 
						WHERE c.comp_marca='$comp_marca' and 
							c.comp_sn='$comp_sn'";

			$resultadoSN = mysql_query($querySN);
                        $linhasSN = mysql_numrows($resultadoSN);
                        if ($linhasSN > 0)
                        {
                                $aviso = "Este número de série já está cadastrado no sistema com esse modelo!";
                                $erro = "sim";
                        }

#############################################

                        $query2 = "SELECT c.* FROM equipamentos as c 
									WHERE ((c.comp_inv='$comp_inv') and 
									(c.comp_inst = '$comp_inst'))";
									
			$resultado2 = mysql_query($query2);
                        $linhas = mysql_numrows($resultado2);
                        if ($linhas > 0)
                        {
                                $aviso.= "Este código de Etiqueta já está cadastrado no sistema com essa Unidade!";
                                $erro = "sim";
                        }
############################################


                        if (($comp_marca==-1) or empty($comp_inv) or ($comp_local==-1) or ($comp_inst ==-1) or
							($comp_tipo_equip == -1) or ($comp_fab ==-1) or ($comp_situac == -1))
                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }

						
			if (isset($_FILES['img']) and $_FILES['img']['name']!="") {
				$qryConf = "SELECT * FROM config";
				$execConf = mysql_query($qryConf) or die ("NÃO FOI POSSÍVEL ACESSAR AS INFORMAÇÕES DE CONFIGURAÇÃO, A TABELA CONF FOI CRIADA?");
				$rowConf = mysql_fetch_array($execConf);
				$arrayConf = array();
				$arrayConf = montaArray($execConf,$rowConf);
				
				$upld = upload('img',$arrayConf);	
				if ($upld =="OK") {
					$gravaImg = true;
				} else { 
					$upld.="<br><a align='center' onClick=\"exibeEscondeImg('idAlerta');\"><img src='".ICONS_PATH."/stop.png' width='16px' height='16px'>&nbsp;Fechar</a>";
					print "</table>";
					print "<div class='alerta' id='idAlerta'><table bgcolor='#999999'><tr><td colspan='2' bgcolor='yellow'>".$upld."</td></tr></table></div>";
					exit;
				}
			}
						
						
						
                        if ($erro=="não")
                        {

				if ($gravaImg) {
					//INSERÇÃO DA IMAGEM NO BANCO
					$fileinput=$_FILES['img']['tmp_name'];
					$tamanho = getimagesize($fileinput);
					
					if(chop($fileinput)!=""){
						// $fileinput should point to a temp file on the server
						// which contains the uploaded image. so we will prepare
						// the file for upload with addslashes and form an sql
						// statement to do the load into the database.
						$image = addslashes(fread(fopen($fileinput,"r"), 1000000));
						$SQL = "Insert Into imagens (img_nome, img_inst, img_inv, img_tipo, img_bin, img_largura, img_altura) values ".
								"('".$_FILES['img']['name']."', '".$comp_inst."', '".$comp_inv."', '".$_FILES['img']['type']."', '".$image."', ".$tamanho[0].", ".$tamanho[1].")";
						// now we can delete the temp file
						unlink($fileinput);
					} /*else {
						echo "NENHUMA IMAGEM FOI SELECIONADA!";
						exit;
					}*/
					$exec = mysql_query($SQL); //or die ("NÃO FOI POSSÍVEL GRAVAR O ARQUIVO NO BANCO DE DADOS! ");
					if ($exec == 0) $aviso.= "NÃO FOI POSSÍVEL ANEXAR A IMAGEM!<br>";
					
				}	

							$sql = "INSERT INTO historico (hist_inv, hist_inst, hist_local, hist_data) 
									VALUES ('$comp_inv', '$comp_inst', '$comp_local', '$hoje')";
						    $resultadoSQL = mysql_query($sql);

                                
								
								
								$data = $hoje;
								$comp_valor = str_replace(",",".",$comp_valor);	
								
								
								$comp_data_compra = datam($comp_data_compra);
								if ($comp_sn == -1) { $comp_sn = "null";};// else $comp_sn = "$comp_sn";
								if ($comp_mb == -1) { $comp_mb = "null";} else $comp_mb = "'$comp_mb'";
								if ($comp_proc == -1) { $comp_proc = "null";} else $comp_proc = "'$comp_proc'";	
								if ($comp_memo == -1) { $comp_memo = "null";} else $comp_memo = "'$comp_memo'";	
								if ($comp_video == -1) { $comp_video = "null";} else $comp_video = "'$comp_video'";
								if ($comp_som == -1) { $comp_som = "null";} else $comp_som = "'$comp_som'";
								if ($comp_rede == -1) { $comp_rede = "null";} else $comp_rede = "'$comp_rede'";
								if ($comp_modelohd == -1) { $comp_modelohd = "null";} else $comp_modelohd = "'$comp_modelohd'";
								if ($comp_modem == -1) { $comp_modem = "null";} else $comp_modem = "'$comp_modem'";
								if ($comp_cdrom == -1) { $comp_cdrom = "null";} else $comp_cdrom = "'$comp_cdrom'";
								if ($comp_dvd == -1) { $comp_dvd = "null";} else $comp_dvd = "'$comp_dvd'";
								if ($comp_grav == -1) { $comp_grav = "null";} else $comp_grav = "'$comp_grav'";
								if ($comp_nome == -1) { $comp_nome = "null";} ;//else $comp_nome = "'$comp_nome'";
								if ($comp_nf == -1) { $comp_nf = "null";};// else $comp_nf = "'$comp_nf'";
								if ($comp_coment == -1) { $comp_coment = "null";};// else $comp_coment = "'$comp_coment'";
								if ($comp_ccusto == -1) { $comp_ccusto = "null";} else $comp_ccusto = "'$comp_ccusto'";
								if ($comp_tipo_imp == -1) { $comp_tipo_imp = "null";} else $comp_tipo_imp = "'$comp_tipo_imp'";
								if ($comp_resolucao == -1) { $comp_resolucao = "null";} else $comp_resolucao = "'$comp_resolucao'";
								if ($comp_polegada == -1) { $comp_polegada = "null";} else $comp_polegada = "'$comp_polegada'";						    
								if ($comp_fornecedor == -1) { $comp_fornecedor = "null";} else $comp_fornecedor = "'$comp_fornecedor'";						    
								
								if ($comp_tipo_garant == -1) { $comp_tipo_garant = "null";} else $comp_tipo_garant = "'$comp_tipo_garant'";								
								if ($comp_garant_meses == -1) { $comp_garant_meses = "null";} else $comp_garant_meses = "'$comp_garant_meses'";																

                                        $query = "INSERT INTO equipamentos (comp_inv, comp_sn, comp_marca, comp_mb, comp_proc, comp_memo, comp_video, comp_som,
                                                  comp_rede, comp_modelohd, comp_modem, comp_cdrom, comp_dvd, comp_grav, comp_nome, comp_local,
                                                  comp_fornecedor, comp_nf, comp_coment, comp_data, comp_valor, comp_data_compra, comp_inst,
												  comp_ccusto, comp_tipo_equip, comp_tipo_imp, comp_resolucao, comp_polegada, comp_fab, comp_situac,
												  comp_tipo_garant, comp_garant_meses)
												   VALUES ('$comp_inv','".noHtml($comp_sn)."','$comp_marca',$comp_mb,$comp_proc,$comp_memo,$comp_video,$comp_som,
                                                  $comp_rede,$comp_modelohd,$comp_modem,$comp_cdrom,$comp_dvd,$comp_grav,'".noHtml($comp_nome)."',
                                                  '$comp_local',$comp_fornecedor,'".noHtml($comp_nf)."','".noHtml($comp_coment)."','$data','$comp_valor','$comp_data_compra',
												  '$comp_inst', $comp_ccusto, '$comp_tipo_equip',$comp_tipo_imp,$comp_resolucao,
												  $comp_polegada,'$comp_fab','$comp_situac',$comp_tipo_garant,$comp_garant_meses)";
                                         $resultado = mysql_query($query) or die ('ERRO NA TENTATIVA DE INCLUIR O REGISTRO!<br>'.$query);
									

                                if ($resultado == 0)
                                {
                                       // print $query;

                                        $aviso = "ERRO na inclusão dos dados.";
                                }
                                else
                                {
                                        $numero = mysql_insert_id();                                                 //$numero
                                        $aviso = "OK. Equipamento cadastrado com sucesso!";
											//$aviso = $query; #####################

										$texto = "[Etiqueta = $comp_inv], [Unidade = $comp_inst]";
										geraLog(LOG_PATH.'invmon.txt',$hojeLog,$s_usuario,'incluir_computador.php',$texto);	   
                                }
                        }
                        $origem = "incluir_computador.php";
                        session_register("aviso");
                        session_register("origem");
                        ?>
						<script language="javascript">
						<!--
							mensagem('<?print $aviso;?>');
							//history.go(-2)();
						//-->
						</script>
						<?												
                        echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mostra_consulta_inv.php?comp_inv=".$comp_inv."&comp_inst=".$comp_inst."\">";
						//echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                
				
				
	} 				
	
	$cab->set_foot();	              

 
  ?>      
<script type="text/javascript">
<!--

	function valida(){
		var ok = validaForm('idEtiqueta','INTEIRO','Etiqueta',1);
		if (ok) var ok = validaForm('idValor','MOEDASIMP','Valor do equipamento',0);
		if (ok) var ok = validaForm('idDataCompra','DATA','Data da compra',0);
		
		return ok;
	
	}	
	
	
	function desabilita(v)
	{
		document.form1.ok.disabled=v;
	}
 
 	function desabilitaCarrega(v){
		document.form1.ok2.disabled=v;
	}
 
	function Habilitar(){
		var inventario = document.form1.comp_inv.value;
		var ind_tipo_equip = document.form1.comp_tipo_equip.selectedIndex;
		var sel_tipo_equip = document.form1.comp_tipo_equip.options[ind_tipo_equip].value;
		var ind_comp_marca = document.form1.comp_marca.selectedIndex;
		var sel_comp_marca = document.form1.comp_marca.options[ind_comp_marca].value;
		var ind_fab = document.form1.comp_fab.selectedIndex;
		var sel_fab = document.form1.comp_fab.options[ind_fab].value;
		var ind_local = document.form1.comp_local.selectedIndex;
		var sel_local = document.form1.comp_local.options[ind_local].value;
		var ind_sit = document.form1.comp_situac.selectedIndex;
		var sel_sit = document.form1.comp_situac.options[ind_sit].value;
		var ind_inst = document.form1.comp_inst.selectedIndex;
		var sel_inst = document.form1.comp_inst.options[ind_inst].value;
			
			
			if ((inventario =="")||(sel_tipo_equip==-1)||(sel_comp_marca==-1)||(sel_fab==-1)||(sel_local==-1)||(sel_sit==-1)||(sel_inst==-1))
			{
				desabilita(true);
				//desabilita(false);
			
			} else {
				desabilita(false);

			}
		
	}
	
	function HabilitarCarrega(){
		var ind_comp_marca = document.form1.comp_marca.selectedIndex;
		var sel_comp_marca = document.form1.comp_marca.options[ind_comp_marca].value;
		if (sel_comp_marca==-1) {
			desabilitaCarrega(true);
		} else{
			desabilitaCarrega(false);
		}
	
	}
	
	window.setInterval("Habilitar()",100);
	window.setInterval("HabilitarCarrega()",100);
	

team = new Array(
<?
$sql="select * from tipo_equip order by tipo_nome";//Somente as áreas ativas
$sql_result=mysql_query($sql);
echo mysql_error();
$num=mysql_numrows($sql_result);
while ($row_A=mysql_fetch_array($sql_result)){
$conta=$conta+1;
	$cod_item=$row_A["tipo_cod"];
		echo "new Array(\n";
		$sub_sql="select * from marcas_comp where marc_tipo='$cod_item' order by marc_nome";
		$sub_result=mysql_query($sub_sql);
		$num_sub=mysql_numrows($sub_result);
		if ($num_sub>=1){
			echo "new Array(\"Selecione o modelo\", -1),\n";
			while ($rowx=mysql_fetch_array($sub_result)){
				$codigo_sub=$rowx["marc_cod"];
				$sub_nome=$rowx["marc_nome"];
			$conta_sub=$conta_sub+1;
				if ($conta_sub==$num_sub){
					echo "new Array(\"$sub_nome\", $codigo_sub)\n";
					$conta_sub="";
				}else{
					echo "new Array(\"$sub_nome\", $codigo_sub),\n";
				}
			}
		}else{
			echo "new Array(\"Qualquer\", -1)\n";
		}
	if ($num>$conta){
		echo "),\n";
	}
}
echo ")\n";
echo ");\n";
?>

function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem) {
	var i, j;
	var prompt;
	// empty existing items
	for (i = selectCtrl.options.length; i >= 0; i--) {
		selectCtrl.options[i] = null; 
	}
	prompt = (itemArray != null) ? goodPrompt : badPrompt;
	if (prompt == null) {
		j = 0;
	}
	else {
		selectCtrl.options[0] = new Option(prompt);
		j = 1;
	}
	if (itemArray != null) {
		// add new items
		for (i = 0; i < itemArray.length; i++) {
			selectCtrl.options[j] = new Option(itemArray[i][0]);
			if (itemArray[i][1] != null) {
				selectCtrl.options[j].value = itemArray[i][1]; 
			}
			j++;
		}
	// select first item (prompt) for sub list
	selectCtrl.options[0].selected = true;
   }
}	
	
	
//-->
</script>      


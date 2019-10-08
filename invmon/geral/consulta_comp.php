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
	//include ("../../includes/javascript/monitora.js");
	
	$s_page_invmon = "consulta_comp.php";
	session_register($s_page_invmon);
	//print "<script>window.parent.location.reload();</script>"; 

	
	//print "<script>window.alert(window.parent.location.href)</script>";
	
	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);

	
	
	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);

	$hoje = date("Y-m-d H:i:s");

    $cor1 = TD_COLOR;

	
	
?>
<BR>
<B>Consulta personalizada (visualização normal ou como relatório):</B>
<BR>


	<SCRIPT LANGUAGE="JAVASCRIPT">
		<!--
		function checar() {
			var checado = false;
			if (document.form1.novaJanela.checked){
		      	checado = true;
				//document.form1.target = "_blank";
			} else {
		      	checado = false;
			}
			return checado;
		}		
		
		window.setInterval("checar()",3000);
		
		function submitForm()
		{
			//document.form1.action = "mostra_consulta_comp.php";
			if (checar() == true) {
				document.form1.target = "_blank";
				document.form1.submit();
			} else {
				document.form1.target = "";
				document.form1.submit();
			}
			
		}
		
		function invertView(id) {
			var element = document.getElementById(id);
			var elementImg = document.getElementById('img'+id);
			var address = '../../includes/icons/';
				
			if (element.style.display=='none'){
				element.style.display='';
				elementImg.src = address+'close.png';
			} else {
				element.style.display='none';
				elementImg.src = address+'open.png';
			}
		}
	
	
	desabilitaLinks(<?print $s_invmon;?>);	
		//-->
	</SCRIPT>
	
<form method='post' name='form1' action='mostra_consulta_comp.php'>
<TABLE border="0"  align="left" width="100%">
        
	<tr><td colspan="4"></td></tr>
		<tr>
		<td colspan="4"><b>Dados complementares - GERAIS:</b></td><td></td><td></td><td></td>
        </tr>
	<tr><td colspan="4"></td></tr>
		
        <tr>
				<TD align="left" bgcolor=<?print TD_COLOR?>><b>Tipo de equipamento: </b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='comp_tipo_equip' size=1>
				   <option value=-1 selected>---------------------Todos---------------------</option>
                <?
                $query = "SELECT * from tipo_equip  order by tipo_nome";
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
				<TD align="left" bgcolor=<?print TD_COLOR?>><b>Fabricante:</b></TD>
                <TD align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='comp_fab' size=1>
				  <option value=-1 selected>---------------------Todos---------------------</option>
                <?
                $query = "SELECT * from fabricantes  order by fab_nome";
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

                <TD align="left" bgcolor=<?print TD_COLOR?>><b>Etiqueta(s):</b></TD>
                <TD align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text2" name="comp_inv"></TD>

                <TD align="left" bgcolor=<?print TD_COLOR?>><b>Número de Série:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text2" name="comp_sn" maxlength="30" size="30"></TD>

		</tr>    

     	<tr>
                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Modelo:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='comp_marca' size=1>
				<option value=-1 selected>---------------------Todos---------------------</option>
                <?                
                $query = "SELECT * from marcas_comp order by marc_nome";
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
			
			
			
			
                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Localização:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='comp_local' size=1>
				<option value=-1 selected>---------------------Todos---------------------</option>
                <?
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



        <TR>
                <TD align="left" bgcolor=<?print TD_COLOR?>><b>Situação:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='comp_situac' size=1>
				<option value=-1 selected>---------------------Todos---------------------</option>
                <?
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
                ?>
                </SELECT>
                </TD>
		</tr>


   <!--  --------------------------------------------------------------------------------------- -->

		<tr><td colspan="4"></td></tr>
		<tr><td colspan="4"><b>Dados complementares - COMPUTADORES:</b></td></tr>
		<tr><td colspan="4"></td></tr>


   
   <!--  --------------------------------------------------------------------------------------- --> 
   
        <tr>
                <TD align="left" bgcolor=<?print TD_COLOR?>><b>Nome do computador:</b></TD>
                <TD align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text2" name="comp_nome" maxlength="15" size="15"></TD>
         
                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>MB:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
                <SELECT class='select2'name='comp_mb' size=1>
				<option value=-1 selected>---------------------Todos---------------------</option>
				<?
				$query = "select * from modelos_itens where mdit_tipo = 10 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
                </SELECT>
                </TD>
        
		

        </tr>
       
		
		
		
	
	   
	   
	   
	    <tr>

                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Processador:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='comp_proc' size=1>
				<option value=-1 selected>---------------------Todos---------------------</option>
                <?
                $query = "select * from modelos_itens where mdit_tipo = 11 order by mdit_fabricante,mdit_desc,mdit_desc_capacidade";
				$commit = mysql_query($query);
				$sufixo = "MHZ";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
                </SELECT>
                </TD>


                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Memória RAM:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='comp_memo' size=1>
				<option value=-1 selected>---------------------Todos---------------------</option>
                <?
                $query = "select * from modelos_itens where mdit_tipo = 7 order by mdit_fabricante, mdit_desc, mdit_desc_capacidade";
				$commit = mysql_query($query);
				$sufixo = "MB";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
                
				<option value=-2>Não nulo</option>
				<option value=-3>Nulo</option>
				</SELECT>
                </TD>
			   </tr>






        <TR>
                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Placa de vídeo:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='comp_video' size=1>
				<option value=-1 selected>---------------------Todos---------------------</option>
                <?
				$query = "select * from modelos_itens where mdit_tipo = 2 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
                </SELECT>
                </TD>




                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Placa de som:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='comp_som' size=1>
				<option value=-1 selected>---------------------Todos---------------------</option>
                <?                
				$query = "select * from modelos_itens where mdit_tipo = 4 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
                </SELECT>
                </TD>
		</tr>
        
		

		
		
		
		<TR>
                <TD align="left" bgcolor=<?print TD_COLOR?>><b>Placa de rede:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='comp_rede' size=1>
				<option value=-1 selected>---------------------Todos---------------------</option>
                <?                
				$query = "select * from modelos_itens where mdit_tipo = 3 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
                </SELECT>
                </TD>


                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Placa fax/modem:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2' name='comp_modem' size=1>
				<option value=-1 selected>---------------------Todos---------------------</option>
                <?                
				$query = "select * from modelos_itens where mdit_tipo = 6 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
						<option value=-2>Não possui</option>
						<option value=-3>Possui qualquer</option>				                                										                                                
                
				</SELECT>
                </TD>
        
		</tr>


        <TR>
                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Modelo do HD:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2' name='comp_modelohd' size=1>
				<option value=-1 selected>---------------------Todos---------------------</option>
                <?                
				$query = "select * from modelos_itens where mdit_tipo = 1 order by mdit_fabricante, mdit_desc_capacidade";
				$commit = mysql_query($query);
				$sufixo = "GB";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while

                ?>
                </SELECT>
                </TD>

                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Unidade Gravador de CD:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2' name='comp_grav' size=1>
				<option value=-1 selected>---------------------Todos---------------------</option>
                <?                
				$query = "select * from modelos_itens where mdit_tipo = 9 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
						<option value=-2>Não possui</option>
						<option value=-3>Possui qualquer</option>				                                										                                                

				</SELECT>
                </TD>

            </tr>
        
        <TR>
                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Unidade de CDROM:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2' name='comp_cdrom' size=1>
				<option value=-1 selected>---------------------Todos---------------------</option>
                <?                
				$query = "select * from modelos_itens where mdit_tipo = 5 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
						<option value=-2>Não possui</option>
						<option value=-3>Possui qualquer</option>				                                										                                                
				</SELECT>
                </TD>



                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Unidade de DVD:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2' name='comp_dvd' size=1>
				<option value=-1 selected>---------------------Todos---------------------</option>
                <?                
				$query = "select * from modelos_itens where mdit_tipo = 8 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
                </SELECT>
                </TD>
            
	</tr>

        <TR>
                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Com o software:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2' name='software' size=1>
				<option value=-1 selected>---------------------Todos---------------------</option>
                <?                
				$query = "select * from softwares s, fabricantes f where s.soft_fab = f.fab_cod order by f.fab_nome, s.soft_desc";
				$commit = mysql_query($query);
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['soft_cod'].">".$row['fab_nome']." ".$row['soft_desc']." ".$row['soft_versao']."</option>";
				} // while
                ?>
						
				</SELECT>
                </TD>
	</tr>
	


	<tr><td colspan="4"></td></tr>
	<tr> <td colspan="4" ><b>Dados complementares - IMPRESSORAS/ MONITORES/ SCANNERS:</b></td></tr>
	<tr><td colspan="4"></td></tr>

	
        <TR>
                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Tipo de impressora: </b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='comp_tipo_imp' size=1>
				<option value=-1 selected>--------Todas-------- </option>
                <?
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
                ?>
                </SELECT>
                </TD>
        


                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Monitor:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='comp_polegada' size=1>
				<option value =-1 selected>--------Todos--------</option>
                <?                
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
                ?>
                </SELECT>
                </TD>
			</tr>                
				
			<tr>	
				<TD  align="left" bgcolor=<?print TD_COLOR?>><b>Scanner:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='comp_resolucao' size=1>
				<option value=-1 selected>--------Todos--------</option>
                <?                
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
                ?>
                </SELECT>
                </TD>
 	
		</tr>	
		
	
	
		


	<tr><td colspan="4"></td></tr>
	<tr> <td colspan="4"><b>Dados complementares - CONTÁBEIS:</font></font> </b></td></tr>
	<tr><td colspan="4"></td></tr>


        <TR>
                <TD  align="left" bgcolor=<?print TD_COLOR?>><b><a title='É possível selecionar mais de uma Unidade utilizando a tecla CTRL!'>Unidade:</a></b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select' name='comp_inst[]' size=1 multiple='yes'>
				<option value=-1 title='Utiliza a tecla CTRL e as teclas direcionais para seleção múltipla!'>--------Todas--------</option>
                <?                
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
        				

                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Centro de Custo:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='comp_ccusto' size=1>
				<option value = -1 selected>---------------Todos----------------- </option>
                <?                
                $query = "SELECT * from ".DB_CCUSTO.".".TB_CCUSTO." order by ".CCUSTO_DESC."";
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
                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Fornecedor:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='comp_fornecedor' size=1>
				<option value=-1 selected>---------------------Todos---------------------</option>
                <?                
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

                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Nota Fiscal:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text2" name="comp_nf" maxlength="30" size="30"></TD>

        </tr>



        <TR>
                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Valor R$:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text2" name="comp_valor" maxlength="30" size="30"></TD>

                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Data da Compra:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text2" name="comp_data_compra" maxlength="30" size="30"></TD>


        </tr>

		<tr>
                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Comentário:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text2" name="comp_coment" maxlength="200" size="100"></TD>

                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Assistência:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='comp_assist' size=1>
				<option value=-1 selected>---------------------Todos---------------------</option>
                <?                
                $query = "SELECT * from assistencia order by assist_desc";
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
				<option value=-2>Não Definida</option>
				</SELECT>
                </TD>


		</TR>
		
        <tr>
                <TD  align="left" bgcolor=<?print TD_COLOR?>><b>Data do cadastro:</b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text2" name="comp_data" maxlength="15" size="15"></TD>
        
                <TD  align="left" bgcolor=<?print TD_COLOR?>><b><a title="Selecione o equipamento quanto ao seu status de garantia.">Garantia:</a></b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='garantia' size=1>
  				  <option value='-1' selected>Todas</option>
				  <option value='1'>Em Garantia</option>
				  <option value='2'>Fora da garantia</option>
				</select>
				</td>
		
		
		
        </TR>
		
        <TR>
                <TD  align="left" bgcolor=<?print TD_COLOR?>><b><a title="Escolha por qual campo deseja ordenar a consulta">Ordenar por:</a></b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select2'name='ordena' size=1>
				<option value='etiqueta' selected>Etiqueta</option>
				<option value='instituicao,etiqueta'>Unidade</option>
				<option value='equipamento,modelo'>Tipo</option>
				<option value='fab_nome,modelo'>Modelo</option>
				<option value='local'>Localização</option>
				</select>
                </TD>
                <TD  align="left" bgcolor=<?print TD_COLOR?>><b><a title="Escolha como será o formato de saída da sua consulta">Formato de saída:</a></b></TD>
                <TD  align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT class='select2'name='visualiza' size=1>";
                print "<option value='tela' selected>Normal</option>";
                print "<option value='impressora'>Relatório 5 linhas</option>";  
                print "<option value='relatorio'>Relatório 1 linha</option>";  
				print "<option value='mantenedora1'>Mantenedora 1 linha</option>";
				print "<option value='texto'>Texto com delimitador</option>";
				print "<option value='config'>Configuração</option>";
				print "<option value='termo'>Termo de compromisso</option>";
				print "<option value='transito'>Formulário de trânsito</option>";
             	print"</selected>";
			 	
				
				?>
				
				</td>
			</tr>
        

		<tr>
                <TD  align="left" bgcolor=<?print TD_COLOR?>><b><a title='Digite aqui o texto que será exibido como cabeçalho se a saída for no formato de relatório.'>Cabeçalho (se for saída=relatório):</a></b></TD>
				
                <TD  align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text2" name="header"></TD>
				<?print "<td><input type='checkbox' name='novaJanela' title='Selecione para que a saída seja em uma nova janela.'>Nova Janela (para impressão)<td>";?>
        </TR>






        <TR>
                <BR>
                <TD colspan="2" align="right"  bgcolor=<?print BODY_COLOR?>><input type="button" value="  Ok  " name="ok" onClick="javascript:submitForm()" >
                        <input type="hidden" name="rodou" value="sim">
						
                </TD>
                <TD colspan="2" align="right"  bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" onClick="javascript:history.back()"></TD>
        </TR>

		
		
</TABLE>
</FORM>

</body>
</html>


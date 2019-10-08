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

	$s_page_admin = "usuarios.php";
	session_register("s_page_admin");

	$hoje = date("d-m-Y H:i:s");
	$hoje2 = date("d/m/Y");	

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);


		$query = "SELECT u.*, n.*,s.* from usuarios u left join sistemas as s on u.AREA = s.sis_id ". 
					"left join nivel as n on n.nivel_cod =u.nivel ";
        if ($_GET['login']) {
			$query.=" WHERE u.user_id = ".$_GET['login']."";
		} else
		if ($_GET['nivel']) {
			$query.= "WHERE n.nivel_cod = ".$_GET['nivel']."";
		} 
		$query.=" ORDER BY u.nome";
		$resultado = mysql_query($query);
		$registros = mysql_num_rows($resultado);
        
		if ($_GET['n_desc']) {
			$n_descricao = $_GET['n_desc'];
		} else
			$n_descricao = "TODOS";
				
		
	if ((!$_GET['action']) && empty($_POST['submit'])) {		
		
		print "<BR>";
		print "<B>Usu�rios(operadores do sistema). N�vel exibido: <font color='red'>".$n_descricao."</font></b>";
		print "<BR>";		
		print "<tr>";
		print "<TD bgcolor='".$cor1."'><a href='usuarios.php?action=incluir'>Incluir usu�rio</a></TD>".
				"<td>&nbsp;|&nbsp;</td><td><a href='usuarios.php?action=stat'>Resumo</a></td>";
		if ($_GET['n_desc']) {
			print "<td>&nbsp;|&nbsp;</td><td><a href='usuarios.php'>Mostra todos</a></td>";	
		}	
		print "</tr>";
		print "<BR>";
        
		
		if ($registros == 0)
        {
           	print mensagem("N�o h� nenhum usu�rio cadastrado no sistema.");
        }
        else
        {
			$cor=TAB_COLOR;
			$cor1=TD_COLOR;
			//print "<TD>";
			print "Existe(m) <b>".$registros."</b> usu�rio(s) cadastrado(s) no sistema.<br>";
			print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='".$cor."'>";
			print "<TR class='header'><TD>Nome</TD><TD>Login</TD><TD>�rea</TD><TD>�rea admin</TD><TD>Data de inclus�o</TD><TD>Data de admiss�o</TD>".
					"<TD>E-mail</TD><TD>Telefone</TD><TD>N�vel</TD><TD>Alterar</TD><TD>Excluir</TD></TR>";
			$i=0;
			$j=2;
			while ($row=mysql_fetch_array($resultado))
			{
				$i++;
				if ($j % 2)
				{
						$color =  BODY_COLOR;
						$trClass = "lin_par";
				}
				else
				{
						$color = white;
						$trClass = "lin_impar";
				}
				$j++;
				
				print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
				
				print "<TD>".$row['nome']."</TD>";
				print "<TD>".$row['login']."</TD>";
				print "<TD>".$row['sistema']."</TD>";
				print "<TD>".transbool($row['user_admin'])."</TD>";
				print "<TD>".datab($row['data_inc'])."</TD>";
				print "<TD>".datab($row['data_admis'])."</TD>";
				print "<TD>".$row['email']."</TD>";
				print "<TD>".$row['fone']."</TD>";
				print "<TD><a href='usuarios.php?nivel=".$row['nivel_cod']."&n_desc=".$row['nivel_nome']."'>".$row['nivel_nome']."</a></TD>";
				print "<td><a onClick=\"redirect('usuarios.php?action=alter&login=".$row['user_id']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Alterar o registro'></a></td>";
				print "<TD><a onClick=\"javascript:confirmaAcao('Tem certeza que deseja deletar ".$row['nome']."?','usuarios.php','action=excluir&login=".$row['user_id']."');\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></TD>";						
				
				print "</TR>";
				
			}
			print "</TABLE>";
         }
	} else
	if (($_GET['action'] == "incluir")&& empty($_POST['submit'])) {
	
		$row = mysql_fetch_array($resultado);
		
		print "<BR>";
		print "<B>Inclus�o de usu�rios</B>";
		print "<BR>";
		
		print "<FORM name='incluir' method='POST' action='".$PHP_SELF."' onSubmit='return valida()'>";
		print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";
        print "<TR>";
       		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Login:</TD>";
            print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='login' id='idLogin'></TD>";
                
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Categoria *:</TD>";
            print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
            print "<SELECT class='select' name='categoria' id='idCategoria'>";
			print "<option value=-1 selected>Selecione o n�vel de acesso:</option>";
			$query = "SELECT * from nivel order by nivel_nome";
			$resultado = mysql_query($query);
			$registros = mysql_num_rows($resultado);
			$i=0;
			while ($rownivel = mysql_fetch_array($resultado)){
				print "<option value='".$rownivel['nivel_cod']."'>".$rownivel['nivel_nome']."</option>";
			}
			print "</select>";
			print "</TD>";			
        print "</TR>";
        print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Nome:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='nome' id='idNome'></TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Senha:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='password' class='text' name='password' id='idSenha'></TD>";
        print "</TR>";
        print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Data de inclus�o:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='data_inc' id='idDataInc' value='".$hoje2."'></TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Data de admiss�o:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='data_admis' id='idDataAdmis' value='".$hoje2."'></TD>";
        print "</TR>";
        print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>E-mail:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='email' id='idEmail'></TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Telefone:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='telefone' id='idTelefone'></TD>";
        print "</TR>";
	    print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>�rea prim�ria:</TD>";
			print "<TD colspan='3' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='area' size=1 id='idArea'>";
			print "<option value=-1 selected>Selecione a �rea de trabalho:</option>";
			$query = "SELECT * from sistemas where sis_status not in (0) order by sistema";
			$resultado = mysql_query($query);
			$registros = mysql_num_rows($resultado);
			while ($rowarea = mysql_fetch_array($resultado)) {
				print "<option value='".$rowarea['sis_id']."'>".$rowarea['sistema']."</option>";
			}
            print "</SELECT>";
            print "<input type='checkbox' name='areaadmin' value=1>Administrador da �rea";
            print "<input type='checkbox' name='naoatribuidos' value=1>Ver chamados n�o atribu�dos";

			print "</TD>";
        print "</TR>";
		
			$qry = "select * from sistemas where sis_status not in (0) and sis_atende =1";
			$exec = mysql_query($qry);
			$i=0;
			print "<tr><td colspan='4'>�rea(s) secund�ria(s):</td></tr>";
			while ($rowa=mysql_fetch_array($exec)){
				print "<tr><td colspan='4'>";
				print "<input type='checkbox' name='grupo[".$i."]' value='".$rowa['sis_id']."'>".$rowa['sistema']."";
				print "</td></tr>";
				$i++;
			}
		print "<TR>";
		print "<BR>";
        	print "<TD colspan='2' align='center' width='50%' bgcolor='".BODY_COLOR."'><input type='submit' value='Cadastrar' name='submit'>";
            print "<input type='hidden' name='rodou' value='sim'>";
            print "</TD>";
            print "<TD colspan='2' align='center' width='50%' bgcolor='".BODY_COLOR."'><INPUT type='reset' value='Cancelar' onClick=\"javascript:history.back()\" name='cancelar'></TD>";
        print "</TR>";
	

	
	
	} else
	if (($_GET['action']=="alter") && empty($_POST['submit'])){
	

	
		print "<BR>";
		print "<B>Altera dados do usu�rio:</B>";
		print "<BR>";				
		
		$row = mysql_fetch_array($resultado);
		print "<FORM method='POST' action='".$PHP_SELF."' onSubmit=\"return valida()\">";
		print "<TABLE border='0' align='center' width='100%' bgcolor='".BODY_COLOR."'>";
        print "<TR>";
		
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Login:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>".$row['login']."</TD>";
	
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Categoria:</TD>";
					
			$qrynivel = "SELECT * FROM nivel order by nivel_nome";
			$execnivel = mysql_query($qrynivel);
			
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
            print "<SELECT class='select' name='categoria' size=1 id='idCategoria'>";
			print "<option value=-1>Selecione a categoria</option>";
			while ($rownivel = mysql_fetch_array($execnivel)){
				print "<option value='".$rownivel['nivel_cod']."'";
				if ($rownivel['nivel_cod'] == $row['nivel_cod']){
					print " selected";
				}
				print ">".$rownivel['nivel_nome']."</option>";	
			}
            print "</SELECT>";
            print "</TD>";
        print "</TR>";
        print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Nome:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='nome' id='idNome' value='".$row['nome']."'></TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Senha:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='password' class='text' name='password' id='idSenha' value='".$row['password']."'></TD>";
            
			$password2 = md5($row['password']);
		print "</TR>";
        print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Data de inclus�o:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='data_inc' id='idDataInc' value='".datab2($row['data_inc'])."'></TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Data de admiss�o:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='data_admis' id='idDataAdmis' value='".datab2($row['data_admis'])."'></TD>";
        print "</TR>";
        print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>E-mail:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='email' id='idEmail' value='".$row['email']."'></TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Ramal:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='telefone' id='idTelefone' value='".$row['fone']."'></TD>";
        print "</TR>";
        print "<TR>";
		print "<tr>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>�rea prim�ria:</TD>";
			$qryarea = "SELECT * FROM sistemas WHERE sis_status not in (0)";
			$execarea = mysql_query($qryarea);	
            print "</td>";
			print "<TD colspan='3' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='area' size=1 id='idArea'>";
			print "<option value=-1>Selecione a �rea</option>";
			while ($rowarea = mysql_fetch_array($execarea)){
				print "<option value='".$rowarea['sis_id']."'";
				if ($rowarea['sis_id'] == $row['sis_id']){
					print " selected";
				}
				print ">".$rowarea['sistema']."</option>";	
         	}
            print "</SELECT>";

			$check = ($row['user_admin']) ? "checked" : "" ;

			print "<input class='ml-10' type='checkbox' name='areaadmin' value=1 {$check}>Administrador da �rea";

            $check = ($row['ver_nao_atribuidos']) ? "checked" : "" ;

            print "<input class='ml-10' type='checkbox' name='naoatribuidos' {$check} value=1>Ver chamados n�o atribu�dos";

            print "</TD>";
        print "</TR>";

			print "<TD align='center' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' value='Alterar' name='submit'>";
			print "<input type='hidden' name='login' value='".$_GET['login']."'>";
			print "<input type='hidden' name='password2' value='".$password2."'>";
			print "</TD>";
            print "<TD colspan='3' align='center' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' value='Cancelar' onClick=\"javascript:history.back()\" name='cancelar'></TD>";
        print "</TR>";

        
			$qry_areas = "select * from usuarios_areas where uarea_uid=".$_GET['login']."";
			$exec_areas = mysql_query($qry_areas);
			$total_areas = 0;
			while ($row_areas = mysql_fetch_array($exec_areas)){
				$uareas[$total_areas]= $row_areas['uarea_sid'];
				$total_areas++;
			}
			
			$qry = "select * from sistemas order by sistema";
			$exec = mysql_query($qry);
			
			$i = 0;
			print "<tr><td colspan='4'>�rea(s) secund�ria(s):</td></tr>";
			while ($rowa=mysql_fetch_array($exec)){
				print "<tr><td colspan='4'>";
				for ($j=0; $j<$total_areas; $j++){
					if ($uareas[$j]== $rowa['sis_id']) {
						$checked[$i] = "checked";
					} 
				}
				print "<input type='checkbox' name='grupo[".$i."]' value='".$rowa['sis_id']."' ".$checked[$i].">".$rowa['sistema']."";
				print "</td></tr>";
				$i++;
			}	
	} else
	
	if (($_GET['action']=="stat") && empty($_POST['submit'])){
	
		$qryStat = "SELECT count(*) quantidade, n.nivel_nome nivel, n.nivel_cod nivel_cod FROM usuarios u, nivel n 
					WHERE u.nivel = n.nivel_cod GROUP by nivel ORDER BY quantidade desc, nome";
		
		$execStat = mysql_query($qryStat) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE USU�RIOS! '.$qryStat);
		
		$background = '#CDE5FF';		
		
		print "<br><center><b/>USU�RIOS CADASTRADOS</center><br>";
		print "<table class='centro' cellspacing='0' border='1' align='center'>";
		print "<tr bgcolor='".$background."'><td>N�VEL</td><td>QUANTIDADE</td></tr>";
		$TOTAL = 0;
		while ($rowStat = mysql_fetch_array($execStat)) {
			print "<tr><td><a href='usuarios.php?nivel=".$rowStat['nivel_cod']."&n_desc=".$rowStat['nivel']."'>".$rowStat['nivel']."</a></td><td>".$rowStat['quantidade']."</td></tr>";
			$TOTAL+=$rowStat['quantidade'];
		}
		print "<tr><td>TOTAL</td><td>".$TOTAL."</td></tr>";
		print "</table>";
		
	
		
		$qryTmp = "SELECT * FROM utmp_usuarios ORDER BY utmp_nome, utmp_cod";
		$execTmp = mysql_query($qryTmp);
		$registrosTmp = mysql_num_rows($execTmp);
		if ($registrosTmp > 0) {
			print "<br><BR><center><b/>AGUARDANDO CONFIRMA��O</center><br>";
			print "<table class='centro' cellspacing='0' border='1' align='center'>";
			print "<tr bgcolor='".$background."'><td>NOME</td><td>LOGIN</td><td>E-MAIL</td><td>CONFIRMAR</td><td>EXCLUIR</td></tr>";
			while ($rowtmp = mysql_fetch_array($execTmp)) {
				print "<tr><td>".$rowtmp['utmp_nome']."</a></td><td>".$rowtmp['utmp_login']."</td><td>".$rowtmp['utmp_email']."</td>";
				print "<TD><a onClick=\"javascript:confirmaAcao('Tem certeza que deseja confirmar o cadastro de ".$rowtmp['utmp_nome']."?','usuarios.php','action=addtmp&cod=".$rowtmp['utmp_cod']."');\"><img height='16' width='16' src='".ICONS_PATH."ok.png' title='Confirmar o cadastro'></TD>";						
				print "<TD><a onClick=\"javascript:confirmaAcao('Tem certeza que deseja deletar ".$rowtmp['utmp_nome']."?','usuarios.php','action=deltmp&cod=".$rowtmp['utmp_cod']."');\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></TD>";						
				print "</tr>";
			}		
			
			print "<tr><td colspan='2'><b>TOTAL</b></td><td colspan='3'><b>".$registrosTmp." registro(s)</b></td></tr>";
			print "</table>";
		
		} else 
			print "NENHUMA PEND�NCIA DE CONFIRMA��O DE CADASTRO!"; 
	
	
	} else
	
	
	if ($_GET['action'] == "excluir"){
		
		$qrydel = "select * from ocorrencias where operador=".$_GET['login']." OR aberto_por = ".$_GET['login']." ";
		$execdel = mysql_query($qrydel) or die ('N�O FOI POSS�VEL ACESSAR AS ocorrênciaS PARA ESSE USU�RIO! Linha 59: '.$query.' '.$qrydel);
		
		$regs = mysql_num_rows($execdel);

		if ($regs!=0){
			print "<script>mensagem('Usu�rio vinculado a ocorrência(s) no sistema. N�o pode ser excluido!');".
					"redirect ('usuarios.php');</script>";
			exit;
		}
		else {
			$qrydel = "DELETE FROM usuarios WHERE user_id=".$_GET['login']."";
			$execdel = mysql_query($qrydel) or die ('Erro na exlus�o do registro'.$qrydel);
			print "<script>mensagem('Registro exclu�do com sucesso!');".
					"redirect ('usuarios.php');</script>";
		}

	} else
	
	if ($_GET['action'] == "deltmp"){	 
		$qrydel = "DELETE FROM utmp_usuarios where utmp_cod = ".$_GET['cod']."";
		$execdel = mysql_query($qrydel) or die ('ERRO NA TENTATIVA DE EXCLUIR O REGISTRO!');
		
		print "<script>mensagem('Registro exclu�do com sucesso!');".
					"redirect ('usuarios.php?action=stat');</script>";
		
		
	} else
	
	if ($_GET['action'] == "addtmp"){	 
		//print "<script>mensagem('FUN��O DE CONFIRMA��O AINDA N�O IMPLEMENTADA!'); redirect('usuarios.php?action=stat');</script>";
		$qryadd = "SELECT utmp_rand FROM utmp_usuarios WHERE utmp_cod = ".$_GET['cod']."";
		$execadd = mysql_query($qryadd) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DO REGISTRO!');
		$rowadd = mysql_fetch_array($execadd);
		print "<script>redirect('../../ocomon/geral/confirma.php?rand=".$rowadd['utmp_rand']."&fromAdmin=true');</script>";
		
		
	} else
	
	if ($_POST['submit'] == "Cadastrar"){	
       		
		$erro=false;
		$pass = md5($_POST['password']);

		if (veremail($_POST['email'])!="ok") {
			$aviso = "E-mail invalido.";
			$erro = true;
		}
		
		$qrytesta = "SELECT * FROM sistemas where sis_id = ".$_POST['area']."";
		$execteste = mysql_query($qrytesta);
		$rowtesta = mysql_fetch_array($execteste);
		
		if ($_POST['categoria'] == 3 and $rowtesta['sis_atende']) {
			$aviso = "Usu�rios para abertura de chamados n�o podem pertencer � �reas que prestam atendimento!";
			$erro = true;
		} else
		if ($_POST['categoria'] != 3 and !$rowtesta['sis_atende']) {
			$aviso = "Usu�rios operadores n�o podem pertencer � �reas que n�o prestam atendimento!";
			$erro = true;
		}
		

		$qryins= "SELECT login FROM usuarios WHERE login = '".$_POST['login']."'";
		$execins = mysql_query($qryins) or die('N�O FOI POSSIVEL ACESSAR A BASE DE USU�RIOS! '.$qryins);
		$regs = mysql_num_rows($execins);
		if ($regs > 0){
			$aviso = "Este usu�rio j� est� cadastrado no sistema!";
			$erro = true;
		}

		if (!$erro) {
			$data_inc = datam($_POST['data_inc']);
			$data_admis = datam($_POST['data_admis']);

/*			if ($_POST['areaadmin']){
				
			}*/
			
			$qryins = "INSERT INTO usuarios (login, nome, password, data_inc, data_admis, email, fone, nivel, AREA, user_admin, ver_nao_atribuidos) ".
					"values ('".noHtml($_POST['login'])."','".noHtml($_POST['nome'])."','".$pass."','".$data_inc."'".
					",'".$data_admis."','".$_POST['email']."','".$_POST['telefone']."', ".$_POST['categoria'].", "
					.$_POST['area'].", '".$_POST['areaadmin']."', '".$_POST['naoatribuidos']."')";
			$execins = mysql_query($qryins) or die ('ERRO AO INCLUIR USU�RIO NO SISTEMA! '.$qryins);
			$uid = mysql_insert_id();
			
			$qrycountarea = "SELECT count(*) tAreas from sistemas";
			$execcountarea = mysql_query($qrycountarea) or die ('N�O FOI POSS�VEL ACESSAR A TABELA DE �REAS DO SISTEMA"'.$qrycountarea);
			$rowcountarea = mysql_fetch_array($execcountarea);
			for ($j=0; $j<$rowcountarea['tAreas']; $j++){
				if (!empty($_POST['grupo'][$j])){
					$qry_areas = "insert into usuarios_areas (uarea_uid,uarea_sid) values (".$uid.",".$_POST['grupo'][$j].")";
					$exec_qryareas = mysql_query($qry_areas) or die ('ERRO NA INSER��O DAS �REAS SECUND�RIAS!');
				}
				//$error.=$qry_areas." | ";
			}
			$aviso = "Usu�rio inclu�do com sucesso no sistema!";
		}
		print "<script>mensagem('".$aviso."'); redirect('usuarios.php');</script>";
	} else
	if ($_POST['submit'] == "Alterar"){	
		$erro = false;
		if (veremail($_POST['email'])!="ok") {
			$aviso = "E-mail inv�lido!";
			$erro = true;
		}
		
		if (!$erro) {
	
			$data_inc = converte_dma_para_amd($_POST['data_inc']);		
			$data_admis = converte_dma_para_amd($_POST['data_admis']);		
			$pass = md5($_POST['password']);
			if ($pass == $_POST['password2'])
					$query2 = "UPDATE usuarios SET nome='".noHtml($_POST['nome'])."', data_inc='".$data_inc."', ".
						"data_admis='".$data_admis."', email='".$_POST['email']."', fone=".$_POST['telefone'].",".
						"nivel=".$_POST['categoria'].", AREA=".$_POST['area'].", user_admin='".$_POST['areaadmin']."',".
                        "ver_nao_atribuidos='".$_POST['naoatribuidos']."' WHERE user_id=".$_POST['login'];
			else
					$query2 = "UPDATE usuarios SET nome='".noHtml($_POST['nome'])."', password='".$pass."', ".
						"data_inc='".$data_inc."', data_admis='".$data_admis."', email='".$_POST['email']."', ".
						"fone='".$_POST['telefone']."', nivel=".$_POST['categoria'].", AREA=".$_POST['area'].", ".
						" user_admin='".$_POST['areaadmin']."', ver_nao_atribuidos='".$_POST['naoatribuidos']."' WHERE user_id=".$_POST['login'];
			$resultado2 = mysql_query($query2) or die ('Erro - '.$query2);
			
			/*     ----------------------------------------------------------------------------------------  */
			
			$qry = "delete from usuarios_areas where uarea_uid=".$_POST['login'];
			$exec = mysql_query($qry) or die('ERRO NA EXCLUS�O DA �REAS SECUND�RIAS ANTERIORES! '.$qry);
			
			$qrycountarea = "SELECT count(*) tAreas from sistemas";
			$execcountarea = mysql_query($qrycountarea) or die ('N�O FOI POSS�VEL ACESSAR A TABELA DE �REAS DO SISTEMA"'.$qrycountarea);
			$rowcountarea = mysql_fetch_array($execcountarea);
			for ($j=0; $j<$rowcountarea['tAreas']; $j++){
				if (!empty($_POST['grupo'][$j])){
					$qry_areas = "insert into usuarios_areas (uarea_uid,uarea_sid) values (".$_POST['login'].",".$_POST['grupo'][$j].")";
					$exec_qry = mysql_query($qry_areas) or die('ERRO NA TENTATIVA DE ADICIONAR AS �REAS SECUND�RIAS');
				}
				//$error.=$qry_areas." | ";
			 }
			
			/*-----------------------------------------------------------------------------------------------*/
			
			$aviso = "Dados do usu�rio alterados com sucesso.";
		}
		print "<script>mensagem('".$aviso."'); redirect('usuarios.php');</script>";
	}
     
?>
<script type="text/javascript">
<!--			
	function valida(){
		
		var ok = validaForm('idLogin','ALFANUM','Login',1) 
		if (ok) var ok = validaForm('idCategoria','COMBO','Categoria',1);
		if (ok) var ok = validaForm('idNome','','Nome',1);
		if (ok) var ok = validaForm('idSenha','ALFANUM','Senha',1);
		if (ok) var ok = validaForm('idDataInc','DATA','Data Inscri��o',1);
		if (ok) var ok = validaForm('idDataAdmis','DATA','Data Admiss�o',1);
		if (ok) var ok = validaForm('idEmail','EMAIL','Email',1);
		if (ok) var ok = validaForm('idTelefone','INTEIRO','Telefone',1);
		if (ok) var ok = validaForm('idArea','COMBO','�rea',1);
		
		return ok;
	}		
-->	
</script>
<?	 


print "</body>";
print "</html>";


?>

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
		

	$conec = new conexao;
	$PDO=$conec->conectaPDO();
	if (AUTH_TYPE == "LDAP") {
		$conec->conLDAP(LDAP_HOST, LDAP_DOMAIN, LDAP_DN, LDAP_PASSWORD);
		$conecSec = new conexao; //Para testar no LDAP Labin
		$conecSec->conLDAP(LDAP_HOST, LDAP_DOMAIN_SEC, LDAP_DN, LDAP_PASSWORD);
	
		if ((senha_ldap($_POST['login'],$_POST['password'],'usuarios')=="ok") && ($conec->userLDAP($_POST['login'],$_POST['password']) || $conecSec->userLDAP($_POST['login'],$_POST['password'])))
		{
		        $s_usuario=$_POST['login'];
		        $s_senha=$_POST['password'];
				
				$queryOK = "SELECT u.*, n.*,s.* FROM usuarios u left join sistemas as s on u.AREA = s.sis_id ". 
								"left join nivel as n on n.nivel_cod =u.nivel WHERE u.login = '".$_POST['login']."'";
		
				$resultadoOK = $PDO->query($queryOK) or die('IMPOSS�VEL ACESSAR A BASE DE DADOS DE USU�RIOS: LOGIN.PHP');
				$row = $resultadoOK->fetch(PDO::FETCH_ASSOC);
				$s_nivel = $row['nivel'];
				
				if ($s_nivel<4){ //Verifica se n�o est� desabilitado
					$s_logado=1;	
				}
				
				$s_nivel_desc = $row['nivel_nome'];
				$s_area = $row['AREA'];
				$s_uid = $row['user_id'];
				$s_area_admin =  $row['user_admin'];
				
				/*VERIFICA EM QUAIS �REAS O USU�RIO EST� CADASTRADO*/
				$qryUa = "SELECT * FROM usuarios_areas where uarea_uid=".$s_uid.""; //and uarea_sid=".$s_area."
				$execUa = $PDO->query($qryUa) or die('IMPOSS�VEL ACESSAR A BASE DE USU�RIOS 02: LOGIN.PHP');
				$uAreas = "".$s_area.",";
				while ($rowUa = $execUa->fetch(PDO::FETCH_ASSOC)){
					$uAreas.=$rowUa['uarea_sid'].",";
				}
				$uAreas = substr($uAreas,0,-1);
				$s_uareas = $uAreas;
				
				
				/*CHECA QUAIS OS M�DULOS PODEM SER ACESSADOS PELAS �REAS QUE O USU�RIO PERTENCE*/
				$qry = "SELECT * FROM permissoes where perm_area in (".$uAreas.")";
				$exec = $PDO->query($qry) or die('IMPOSS�VEL ACESSAR A BASE DE PERMISS�ES: LOGIN.PHP');
				
				while($row_perm = $exec->fetch(PDO::FETCH_ASSOC)){
					$s_permissoes[]=$row_perm['perm_modulo'];
				}
				$s_ocomon = 0;
				$s_invmon = 0;
				for ($i=0;$i<count($s_permissoes); $i++){
					if($s_permissoes[$i] == 1) $s_ocomon = 1;
					if($s_permissoes[$i] == 2) $s_invmon = 1;
				}
						
						//echo "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0;URL=../../index.php?".session_id()."\">";
						print "<script>redirect('../../index.php?".session_id()."');</script>";
		} else {
	
			//echo "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0;URL=../../index.php?usu=".$_POST['login']."&inv=1\">";
			print "<script>redirect('../../index.php?usu=".$_POST['login']."&inv=1');</script>";
			$conec->desconLDAP();
			$conecSec->desconLDAP();			
			exit;
		}
		$conec->desconLDAP();
		$conecSec->desconLDAP();
	
	} else {
		print_r(senha_system($_POST['login'],$_POST['password'],'usuarios'));
		if (senha_system($_POST['login'],$_POST['password'],'usuarios')=="ok")
		{
		        
		        $s_usuario=$_POST['login'];
		        $s_senha=$_POST['password'];
				
				$queryOK = "SELECT u.*, n.*,s.* FROM usuarios u left join sistemas as s on u.AREA = s.sis_id ". 
								"left join nivel as n on n.nivel_cod =u.nivel WHERE u.login = '".$_POST['login']."'";
		
				$resultadoOK = $PDO->query($queryOK) or die('IMPOSS�VEL ACESSAR A BASE DE DADOS DE USU�RIOS: LOGIN.PHP');
				$row = $resultadoOK->fetch(PDO::FETCH_ASSOC);
				$s_nivel = $row['nivel'];
				
				if ($s_nivel<4){ //Verifica se n�o est� desabilitado
					$s_logado=1;	
				}
				
				$s_nivel_desc = $row['nivel_nome'];
				$s_area = $row['AREA'];
				$s_uid = $row['user_id'];
				$s_area_admin =  $row['user_admin'];
				
				
				/*VERIFICA EM QUAIS �REAS O USU�RIO EST� CADASTRADO*/
				$qryUa = "SELECT * FROM usuarios_areas where uarea_uid=".$s_uid.""; //and uarea_sid=".$s_area."
				$execUa = $PDO->query($qryUa) or die('IMPOSS�VEL ACESSAR A BASE DE USU�RIOS 02: LOGIN.PHP');
				$uAreas = "".$s_area.",";
				while ($rowUa = $execUa->fetch(PDO::FETCH_ASSOC)){
					$uAreas.=$rowUa['uarea_sid'].",";
				}
				$uAreas = substr($uAreas,0,-1);
				$s_uareas = $uAreas;
				
				/*CHECA QUAIS OS M�DULOS PODEM SER ACESSADOS PELAS �REAS QUE O USU�RIO PERTENCE*/
				$qry = "SELECT * FROM permissoes where perm_area in (".$uAreas.")";
				$exec = $PDO->query($qry) or die('IMPOSS�VEL ACESSAR A BASE DE PERMISS�ES: LOGIN.PHP');
				
				
				while($row_perm = $exec->fetch(PDO::FETCH_ASSOC)){
					$s_permissoes[]=$row_perm['perm_modulo'];
				}
				$s_ocomon = 0;
				$s_invmon = 0;
				for ($i=0;$i<count($s_permissoes); $i++){
					if($s_permissoes[$i] == 1) $s_ocomon = 1;
					if($s_permissoes[$i] == 2) $s_invmon = 1;
				}
						
						//echo "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0;URL=../../index.php?".session_id()."\">";
						print "<script>redirect('../../index.php?".session_id()."');</script>";
		}
		else
		{
				//echo "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0;URL=../../index.php?usu=".$_POST['login']."&inv=1\">";
				print "<script>redirect('../../index.php?usu=".$_POST['login']."&inv=1');</script>";
				exit;
		}
	}

	
	
?>

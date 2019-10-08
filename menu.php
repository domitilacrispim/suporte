<?php
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
	
	include ("PATHS.php");
	include ("".$includesPath."var_sessao.php");
    include ("includes/functions/funcoes.inc");
    include ("".$includesPath."config.inc.php");
    include ("includes/classes/conecta.class.php");
    include ("".$includesPath."versao.php");

    if ($s_logado==1)
    {
        $conec = new conexao;
        $PDO = $conec->conectaPDO();

        $query = "SELECT * FROM usuarios WHERE user_id = $s_uid LIMIT 1";
        $resultado = $PDO->query($query) or die ('ERRO AO TENTAR RECUPERAR AS INFORMA��ES DE USU�RIO! '.$query);
        $linha = $resultado->fetch(PDO::FETCH_ASSOC);

        $partes = explode(' ', $linha['nome']);
        $nome = array_shift($partes)." ".array_pop($partes);
        $email =  $linha['email'];

    }


	?>
	<!--
		@import url('includes/menu/phplm320/layerstreemenu-hidden.css');
	//-->
	<style type='text/css'>
	</style>

    <!-- Bootstrap core CSS -->
    <link href="includes/css/bootstrap.css" rel="stylesheet">
    <link href="includes/css/menu.css" rel="stylesheet">



	<script language='JavaScript' type='text/javascript'>
        <?php require_once $phplmDirPath.'libjs/layersmenu-browser_detection.js'?>
	</script>
	<script language='JavaScript' type='text/javascript' src='includes/menu/phplm320/libjs/layerstreemenu-cookies.js'></script>
	<?php
	require_once $phplmDirPath.'lib/PHPLIB.php';
	require_once $phplmDirPath.'lib/layersmenu-common.inc.php';
	require_once $phplmDirPath.'lib/treemenu.inc.php';
	$mid = new TreeMenu();

	//$mid->setDirroot($myDirPath);
	$mid->setLibjsdir($phplmDirPath.'libjs/');
	$mid->setImgdir($phplmDirPath.'menuimages/');
	$mid->setImgwww($phplmDirPath.'menuimages/');
	$mid->setIcondir($phplmDirPath.'menuicons/');
	$mid->setIconwww($phplmDirPath.'menuicons/');
	
	$menuInvmon = ".|Iniciar|".$invDirPath."abertura.php|Tela inicial do Sistema|".$iconsPath."gohome.png|centro
.|Cadastrar
..|Equipamento|".$invDirPath."incluir_computador.php||".$iconsPath."computador.png|centro
..|Documento|".$invDirPath."incluir_material.php||".$iconsPath."contents.png|centro
..|Item de estoque|".$invDirPath."estoque.php||".$iconsPath."mouse.png|centro
.|Visualizar
..|Equipamentos|".$invDirPath."mostra_consulta_comp.php||".$iconsPath."computador.png|centro
..|Documentos|".$invDirPath."materiais.php||".$iconsPath."contents.png|centro
..|Estoque|".$invDirPath."estoque.php||".$iconsPath."mouse.png|centro
.|Consultar
..|Consulta r�pida|".$invDirPath."consulta_inv.php||".$iconsPath."search.png|centro
..|Consulta Especial|".$invDirPath."consulta_comp.php||".$iconsPath."consulta.png|centro
..|Hist�rico|
...|Por Etiqueta|".$invDirPath."consulta_hist_inv.php||".$iconsPath."tag.png|centro
...|Localiza��o anterior|".$invDirPath."consulta_hist_local.php|||centro
.|Estat�ticas e Relat�rios|".$invDirPath."relatorios.php||".$iconsPath."reports.png|centro
.|Senha|".$invDirPath."altera_senha.php||".$iconsPath."password.png|centro";
	
	
	
	$mid->setMenuStructureString($menuInvmon);
	$mid->setIconsize(16, 16);
	$mid->parseStructureForMenu('treemenu1');
	//$mid->setTpldir('../../includes/menu/phplm320/templates/');	
	//$mid->setSelectedItemByUrl('treemenu1', basename(__FILE__));
	
	$menuOcomon =".|Inicio|".$ocoDirPath."abertura.php|Tela inicial do Sistema|".$iconsPath."gohome.png|centro
.|Abrir Chamado|".$ocoDirPath."incluir.php||".$iconsPath."fone.png|centro
.|Consultar|".$ocoDirPath."consultar.php||".$iconsPath."consulta.png|centro
.|Busca R�pida|".$ocoDirPath."alterar.php||".$iconsPath."search.png|centro
.|Solu��es|".$ocoDirPath."consulta_solucoes.php||".$iconsPath."solucoes2.png|centro
.|Empr�stimos|".$ocoDirPath."emprestimos.php||".$iconsPath."emprestimos.png|centro
.|Mural|".$ocoDirPath."avisos.php||".$iconsPath."mural.png|centro
.|Senha|".$invDirPath."altera_senha.php||".$iconsPath."password.png|centro
.|Relat�rios ADM|novosite/area.php||".$iconsPath."reports.png|centro
.|Relat�rios|".$ocoDirPath."relatorios.php|||centro
..|SLAs|".$ocoDirPath."relatorio_slas_2.php||".$iconsPath."sla.png|centro";
	
	$mid->setMenuStructureString($menuOcomon);
	$mid->parseStructureForMenu('treemenu2');
	//$mid->setTreeMenuTheme('kde_');
	
	$menuAdmin ="


";
	
	//$mid->setMenuStructureFile('admin-menu.txt');
	$mid->setMenuStructureString($menuAdmin);
	$mid->parseStructureForMenu('treemenu3');
	

// ADICIONADO PARA USUARIO SOMENTE CONSULTAS E ABERTURA DE OCORRENCIA	

	
	$menuSimples =".|Inicio|".$ocoDirPath."abertura_user.php|Tela inicial do Sistema|".$iconsPath."gohome.png|centro
.|Abrir Chamado|".$ocoDirPath."incluir.php|Clique aqui para abrir um chamado|".$iconsPath."fone.png|centro
.|Meus chamados|".$ocoDirPath."abertura_user.php?action=listall|Lista todos o meus chamados|".$iconsPath."search.png|centro
.|Senha|".$invDirPath."altera_senha.php||".$iconsPath."password.png|centro";

	$mid->setMenuStructureString($menuSimples);
	$mid->parseStructureForMenu('treemenu4');
	//$mid->setTreeMenuTheme('kde_');

	$menuHome =".|Inicio|home.php|Tela inicial|".$iconsPath."gohome.png|centro
.|Abertos por mim|".$ocoDirPath."abertura_user.php?action=listall|Lista todos o meus chamados|".$iconsPath."search.png|centro
.|Abertos total|".$ocoDirPath."relatorio_chamados_aberto.php|Chamados em aberto|".$iconsPath."reports.png|centro";

	$mid->setMenuStructureString($menuHome);
	$mid->parseStructureForMenu('treemenu5');
	
	

// FIM DA INCLUSAO	PARA USUARIO SOMENTE CONSULTAS E ABERTURA DE OCORRENCIA	
	
	
	
print "<html>";
print "<title>Ocomon&Invmon</title>";
print "</head><body style={background-image:url('MENU_IMG.png'); background-repeat:no-repeat;}>"; //background-position:center;  background-attachment: fixed;
	
	if (isset($s_page_simples)) $simplesHome = $s_page_simples; else $simplesHome = "abertura_user.php";
	if (isset($s_page_invmon)) $invHome = $s_page_invmon; else $invHome = "abertura.php";
	if (isset($s_page_home)) $homeHome = $s_page_home; else $homeHome = "home.php";
	if (isset($s_page_ocomon)) $ocoHome = $s_page_ocomon; else $ocoHome = "abertura.php";
	if (isset($s_page_admin)) $admHome = $s_page_admin; else $admHome = "sistemas.php";
	


if ($sis=="o"){
	$where = "<a class='menu' href='".$ocoDirPath."abertura.php' target='centro'>ocorrências</a>";
	print "<script>window.parent.frames['centro'].location = '".$ocoDirPath."".$ocoHome."'</script>";
	$menu = "treemenu2";	
} else
if ($sis=="i"){
	$where = "<a class='menu' href='".$invDirPath."abertura.php' target='centro'>Invent�rio</a>";
	print "<script>window.parent.frames['centro'].location = '".$invDirPath."".$invHome."'</script>";

	$menu="treemenu1";
} else
if ($sis=="a"){
	$where = "Administra��o";//<img src='".$phplmDirPath."menuicons/".$iconsPath."sysadmin.png'>
	$menu="treemenu3";	
	print "<script>window.parent.frames['centro'].location = '".$admDirPath."".$admHome."'</script>";
 // para usuarios simples
} else
if ($sis=="s"){
	$where = "<a class='menu' href='".$ocoDirPath."abertura_user.php' target='centro'>ocorrências</a>";
	print "<script>window.parent.frames['centro'].location = '".$ocoDirPath."".$simplesHome."'</script>";
	$menu = "treemenu4";	
// fim da inclusao para usuario simples

} else
if ($sis=="h"){
	//$where = "HOME";//<img src='".$phplmDirPath."menuicons/".$iconsPath."sysadmin.png'>
	$where = "<a class='menu' href='home.php' target='centro'>HOME</a>";
	if ($_SESSION['s_nivel'] > 2 ){
		$menu = "treemenu4";
		print "<script>window.parent.frames['centro'].location = '".$ocoDirPath."".$simplesHome."'</script>";
	} else {
		$menu="treemenu5";	
		print "<script>window.parent.frames['centro'].location = '".$homeHome."'</script>";
 	}
 // para usuarios simples
} else {
	$where = "<a class='menu' href='menu.php' target='centro'>Home</a>";
}


//print "<img src=".$logosPath."phpmyadmin.png>";
//print "<table class='menutop' width='100%' border='0'><tr class='menutop'><td><b>".$where."</b></font></td></tr></table>";
?>

    <table class='menu' >
        <div class="sidebar">

            <img src="includes/imgs/user.png" class="img-user" />
            <div class="desc-user">
                <p class="user"><?php echo $nome ?></p>
                <p class="desc"><?php echo $email ?></p>
            </div>
            <div style="clear:both"></div>

            <div id="navbar" class="navbar-collapse">
                <ul class="nav nav-sidebar">
<?php
	if (!empty($menu)){
	    if ($menu == 'treemenu2'){
?>

                    <li ><a target="centro" href="<?php echo $ocoDirPath ?>abertura.php" ><span class="glyphicon glyphicon-home"></span>In�cio</a></li>
                    <li ><a target="centro" href="<?php echo $ocoDirPath ?>incluir.php"><span class="glyphicon glyphicon-phone-alt"></span>Abrir Chamado</a></li>
                    <li ><a target="centro" href="<?php echo $ocoDirPath ?>consultar.php"><span class="glyphicon glyphicon-search"></span>Consultar</a></li>
                    <li ><a target="centro" href="<?php echo $ocoDirPath ?>alterar.php"><span class="glyphicon glyphicon-search"></span>Busca R�pida</a></li>
                    <li ><a target="centro" href="<?php echo $ocoDirPath ?>consulta_solucoes.php"><span class="glyphicon glyphicon-check"></span>Solu��es</a></li>
                    <li ><a target="centro" href="<?php echo $ocoDirPath ?>emprestimos.php"><span class="glyphicon glyphicon-list-alt"></span>Empr�stimos</a></li>
                    <li ><a target="centro" href="<?php echo $ocoDirPath ?>avisos.php"><span class="glyphicon glyphicon-comment"></span>Mural</a></li>
                    <li ><a target="centro" href="<?php echo $invDirPath ?>altera_senha.php"><span class="glyphicon glyphicon-lock"></span>Senha</a></li>
                    <li>
                        <a role="button" data-toggle="collapse" href="#submenu1" aria-controls="submenu1" aria-expanded="false">
                            <span class="glyphicon glyphicon-stats"></span>Relat�rios
                        </a>
                        <ul id="submenu1" class="collapse" role="tabpanel">
                            <li ><a target="centro" href="novosite/area.php">Relat�rios ADM</a></li>
                            <li ><a target="centro" href="<?php echo $ocoDirPath ?>relatorio_slas_2.php">Relat�rios SLAs</a></li>
                            <li ><a target="centro" href="<?php echo $ocoDirPath ?>relatorios.php">Outros</a></li>
                        </ul>
                    </li>
<?php
        }else if($menu == 'treemenu5'){
?>

                    <li ><a target="centro" href="home.php" ><span class="glyphicon glyphicon-home"></span>In�cio</a></li>
                    <li ><a target="centro" href="<?php echo $ocoDirPath ?>abertura_user.php?action=listall"><span class="glyphicon glyphicon-search"></span>Abertos por mim</a></li>
                    <li ><a target="centro" href="<?php echo $ocoDirPath ?>relatorio_chamados_aberto.php"><span class="glyphicon glyphicon-list-alt"></span>Abertos total</a></li>


<?php
        }else if($menu == 'treemenu3'){
?>
            <li>
                <a role="button" data-toggle="collapse" href="#submenu1" aria-controls="submenu1" aria-expanded="false">
                    <span class="glyphicon glyphicon-cog"></span>Configura��es
                </a>
                <ul id="submenu1" class="collapse" role="tabpanel">
                    <li><a target="centro" href="<?php echo $admDirPath ?>configGeral.php">Configura��es gerais</a></li>
                    <li><a target="centro" href="<?php echo $admDirPath ?>configuserscreen.php">Abertura de chamados</a></li>
                    <li><a target="centro" href="<?php echo $admDirPath ?>configmail.php">E-Mail-SMTP</a></li>
                    <li><a target="centro" href="<?php echo $admDirPath ?>configmsgs.php">E-Mail-mensagens</a></li>
                </ul>
            </li>

            <li>
                <a role="button" data-toggle="collapse" href="#submenu2" aria-controls="submenu2" aria-expanded="false" >
                    <span class="glyphicon glyphicon-phone-alt"></span>ocorrências
                </a>
                <ul id="submenu2" class="collapse" role="tabpanel">
                    <li><a target="centro" href="<?php echo $admDirPath ?>sistemas.php">�reas</a></li>
                    <li><a target="centro" href="<?php echo $admDirPath ?>problemas.php">Problemas</a></li>
                    <li><a target="centro" href="<?php echo $admDirPath ?>status.php">Status</a></li>
                    <li><a target="centro" href="<?php echo $admDirPath ?>prioridades.php">Prioridades</a></li>
                    <li><a target="centro" href="<?php echo $admDirPath ?>feriados.php">Feriados</a></li>
                    <li><a target="centro" href="<?php echo $admDirPath ?>ocorrencias.php">ocorrências</a></li>
                </ul>
            </li>

            <li>
                <a role="button" data-toggle="collapse" href="#submenu3" aria-controls="submenu3" aria-expanded="false">
                    <span class="glyphicon glyphicon-inbox"></span>Invent�rio
                </a>
                <ul id="submenu3" class="collapse" role="tabpanel">
                    <li><a target="centro" href="<?php echo $admDirPath ?>tipo_equipamentos.php">Equipamentos</a></li>
                    <li><a role="button" data-toggle="collapse" href="#submenu4" aria-controls="submenu4" aria-expanded="false">Componentes</a>
                        <ul id="submenu4" class="collapse" role="tabpanel">
                            <li><a target="centro" href="<?php echo $admDirPath ?>itens.php?tipo=5">CD-Rom</a></li>
                            <li><a target="centro" href="<?php echo $admDirPath ?>itens.php?tipo=8">DVD</a></li>
                            <li><a target="centro" href="<?php echo $admDirPath ?>itens.php?tipo=9">Gravador</a></li>
                            <li><a target="centro" href="<?php echo $admDirPath ?>itens.php?tipo=1">HD</a></li>
                            <li><a target="centro" href="<?php echo $admDirPath ?>itens.php?tipo=10">Placa m�e</a></li>
                            <li><a target="centro" href="<?php echo $admDirPath ?>itens.php?tipo=7">Mem�ria</a></li>
                            <li><a target="centro" href="<?php echo $admDirPath ?>itens.php?tipo=6">Placa de modem</a></li>
                            <li><a target="centro" href="<?php echo $admDirPath ?>itens.php?tipo=11">Processador</a></li>
                            <li><a target="centro" href="<?php echo $admDirPath ?>itens.php?tipo=3">Placa de rede</a></li>
                            <li><a target="centro" href="<?php echo $admDirPath ?>itens.php?tipo=4">Placa de som</a></li>
                            <li><a target="centro" href="<?php echo $admDirPath ?>itens.php?tipo=2">V�deo</a></li>
                        </ul>
                    </li>
                    <li><a target="centro" href="<?php echo $admDirPath ?>fabricantes.php">Fabricantes</a></li>
                    <li><a target="centro" href="<?php echo $admDirPath ?>fornecedores.php">Fornecedores</a></li>
                    <li><a target="centro" href="<?php echo $admDirPath ?>softwares.php">Softwares</a></li>
                    <li><a target="centro" href="<?php echo $admDirPath ?>estoque.php">Estoque</a></li>
                </ul>
            </li>

            <li ><a target="centro" href="<?php echo $admDirPath ?>usuarios.php"><span class="glyphicon glyphicon-user"></span>Usu�rios</a></li>
            <li ><a target="centro" href="<?php echo $admDirPath ?>locais.php"><span class="glyphicon glyphicon-map-marker"></span>Locais</a></li>
            <li ><a target="centro" href="<?php echo $admDirPath ?>unidades.php"><span class="glyphicon glyphicon-pushpin"></span>Unidades</a></li>
            <li ><a target="centro" href="<?php echo $admDirPath ?>ccustos.php"><span class="glyphicon glyphicon-usd"></span>Centros de Custo</a></li>
            <li ><a target="centro" href="<?php echo $admDirPath ?>permissoes.php"><span class="glyphicon glyphicon-lock"></span>Permiss�es</a></li>
            <li ><a target="centro" href="novosite/area.php"><span class="glyphicon glyphicon-stats"></span>Relat�rios ADM</a></li>
            <li ><a target="centro" href="<?php echo $invDirPath ?>altera_senha.php"><span class="glyphicon glyphicon-lock"></span>Senha</a></li>


	<?php
        }else print $mid->newTreeMenu($menu);
	}
?>
                </ul>
            </div>
        </div>
    </table>


    <div class="text-center font-10">
        <a href='http://ocomonphp.sourceforge.net' target='_blank'>OcoMon</a> - Monitor de ocorrências e Invent�rio de equipamentos de inform�tica.<br>Vers�o: <?php echo VERSAO?> - Licen�a GPL
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="includes/js/jquery-2.1.0.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="includes/js/bootstrap.min.js"></script>

</body>
</html>

<script type="text/javascript">
	 function popup(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'popup','dependent=yes,width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
      	//x.moveTo(100,100);
		x.moveTo(window.parent.screenX+100, window.parent.screenY+100);	  	
		return false
     }
</script>
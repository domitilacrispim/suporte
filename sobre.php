<?php
    print "<html><head><title>Sobre o Ocomon</title>"; 
    
			print "<style type=\"text/css\"><!--";
			print "body.corpo {background-color:#F6F6F6; font-family:helvetica;}";
            print "p{font-size:12px;  margin-left:10%; margin-right: 10%; text-indent: 1cm; text-align:justify;}";
			print "ul{font-size:12px; margin-left:10%; margin-right: 10%; text-indent: 1cm; text-align:justify;}";
            print "table.pop {width:100%; margin-left:auto; margin-right: auto; text-align:left; 
					border: 0px; border-spacing:1 ;background-color:#cde5ff; padding-top:10px; }";
			print "tr.linha {font-family:helvetica; font-size:10px; line-height:1em; }";			
			print "--></STYLE>";			    
    print "</head><body class='corpo'>";
    
	print "<center><b>O ocomon</b></center>";
	print "<p>O Ocomon surgiu em Mar�o de 2002 como projeto pessoal do programador Franque Cust�dio, tendo como caracter�sticas".
			" iniciais o cadastro, acompanhamento, controle e consulta de ocorrências de suporte e tendo como primeiro usu�rio".
			" o Centro Universit�rio La Salle (UNILASALLE) que adotou a ferramenta e desde ent�o tem realizado sua manuten��o interna pelo ".
			"seu pr�prio setor de Helpdesk buscando atender a quest�es de ordem pr�tica e operacional da �rea de suporte. ".
			"Baseado no c�digo do Ocomon, em Maio de 2003 surgiu a primeira vers�o do Invmon, sistema de invent�rio para equipamentos".
			" de inform�tica e a partir da� e todas as informa��es de atendimentos come�aram as estar vinculadas ao respectivo ".
			"equipamento, acrescentando grande praticidade e valor ao sistema de atendimento.".
			" Com a percep��o da necessidade crescente de informa��es mais relacionadas com � quest�o de qualidade no suporte,".
			" no in�cio de 2004 foram adicionadas caracter�sticas de gerenciamento de SLAs no sistema Ocomon, mudando de ".
			"forma sens�vel a maneira como o gerenciamento de chamados vinha acontecendo e obtendo crescente melhoria da qualidade".
			" final de acordo com os indicadores fixados para os servi�os realizados.</p>";
			
	print "<p>Hoje � poss�vel responder quest�es como:</p>";
	print "<p><ul><li>volume de chamados por per�odo;</li>".
			"<li>tempo m�dio de resposta e solu��o para os chamados;</li>".
			"<li>percentual de chamados atendidos e resolvidos dentro do SLA;</li>".
			"<li>tempo dos chamados decomposto em cada status de atendimento;</li>".
			"<li>usu�rios mais ativos;</li>".
			"<li>principais problemas;</li>".
			"<li>reincid�ncia de chamados por equipamento;</li>".
			"<li>estado real do parque de equipamentos;</li>".
			"<li>como e onde est�o distribu�dos os equipamentos;</li>".
			"<li>vencimento das garantias dos equipamentos;</li>".
			"al�m de uma s�rie outras quest�es pertinentes � ger�ncia pr�-ativa do setor de suporte.</ul></p>";
	print "<p>No in�cio de 2005, os dois sistemas: Ocomon e Invmon foram finalmente 100% integrados ganhando um novo ".
			"layout e permancendo com o nome �nico de OCOMON. Tendo ent�o sua utiliza��o baseada em dois ".
			"m�dulos principais: ".
			"<ul><li>M�dulo de ocorrências;</li>".
			"<li>M�dulo de Invent�rio;</li></ul>".
			"</p>";

	print "<p>Principais fun��es do m�dulo de <b>ocorrências:</b></p>";
	print "<ul><li>abertura de chamados de suporte por �rea de compet�ncia;</li>".
			"<li>v�nculo do chamado com a etiqueta de patrim�nio do equipamento;</li>".
			"<li>busca r�pida de informa��es referentes ao equipamento ".
			"(configura��o, localiza��o, hist�rico de chamados, garantia..) no momento da abertura do chamado;</li>".
			"<li>envio autom�tico de e-mail para as �reas de compet�ncia;</li>".
			"<li>acompanhamento do andamento do processo de atendimento das ocorrências;</li>".
			"<li>encerramento das ocorrências;</li>".
			"<li>controle de horas v�lidas;</li>".
			"<li>defini��es de n�veis de prioridades para os setores da empresa;</li>".
			"<li>gerenciamento de tempo de resposta baseado nas defini��es de prioridades dos setores;</li>".
			"<li>gerenciamento de tempo de solu��o baseado nas defini��es de categorias de problemas;</li>".
			"<li>controle de depend�ncias para o andamento do chamado;</li>".
			"<li>base de conhecimento;</li>".
			"<li>consultas personalizadas;</li>".
			"<li>relat�rios gerenciais;</li>".
			"<li>controle de SLAs;</li></ul>";
	
	print "<p>Principais fun��es do <b>m�dulo de invent�rio:</b></p>";
	print "<ul><li>cadastro detalhado das informa��es (configura��o) de hardware do equipamento;</li>".
			"<li>cadastro de informa��es cont�beis do equipamento (valor, centro de custo,localiza��o, reitoria, fornecedor..);</li>".
			"<li>cadastro de modelos de configura��o para carga r�pida de informa��es de novos equipamentos;</li>".
			"<li>cadastro de documenta��es relacionadas aos equipamentos (manuais, termos de garantia, m�dias..);</li>".
			"<li>controle de garantias dos equipamentos;</li>".
			"<li>hist�rico de mudan�as (de localidades) dos equipamentos;</li>".
			"<li>controle de licen�as de softwares;</li>".
			"<li>busca r�pida das informa��es de chamados de suporte para o equipamento;</li>".
			"<li>busca r�pida de informa��es dos equipamentos;</li>".
			"<li>buscas por hist�rico de mudan�as (localiza��o);</li>".
			"<li>consultas personalizadas;</li>".
			"<li>estat�sticas t�cnicas e gerenciais do parque de equipamentos;</li>".
			"<li>relat�rios gerenciais; </li></ul>";

	print "<p><b>Quest�es t�cnicas:</b></p>";
	print "<p>O Ocomon foi concebido sob a vis�o de software opensource sob o modelo GPL de licenciamento, ".
			"utilizando tecnologias e ferramentas livres para o seu desenvolvimento e manuten��o.</p>";
	print "<p>Abaixo listamos as principais quest�es t�cnicas do sistema:</p>";
	print "<ul><li>Linguagem: PHP vers�o:4.3x, HTML, CSS, Javascript;</li>".
			"<li>Banco de dados: MySQL vers�o: 4.1x;</li>".
			"<li>Autentica��o de usu�rios: a autentica��o de usu�rios pode ser feita tanto ".
			"na pr�pria base do sistema quanto atrav�s de uma base LDAP em algum ponto da rede.</li></ul>";
	print "<p>P�gina do projeto:<a href='http://ocomonphp.sourceforge.net' target='_blank'>ocomonphp.sourceforge.net</a></p>";		
	
	print "<p>Esperamos que o Ocomon possa lhe ser t�o �til quanto nos � aqui no ".
			"<a href='http://www.unilasalle.edu.br' target='_blank'>Unilasalle!</a></p>";
	print "<p>Atenciosamente</p><p><a href='mailto:flavio@unilasalle.edu.br'>Fl�vio Ribeiro</a></p>";		
			
    print "</body></html>";

?>
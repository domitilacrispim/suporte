#
# OcoMon - vers�o 1.40
# Data: Mar�o de 2006
# Autor: Fl�vio Ribeiro (flavio@lasalle.tche.br) - Baseado no C�digo Original do Ocomon 0.70 do Franque Cust�dio
#
# Lince�a: GPL
#

Requisitos: 
    * Servidor Web (preferencialmente Apache);
    * Linguagem: PHP vers�o:4.3x, HTML, CSS, Javascript;
    * Banco de dados: MySQL vers�o: 4.1x;
    * Diretiva register_globals=On no arquivo de configura��o do PHP(php.ini). 
    	/*Atualmente o sistema est� sendo reescrito para atender � recomenda��o register_globals=Off*/
  	
Notas importantes:
 
    * Para o sistema funcionar adequadamente � necess�rio que seu navegador permita que sistema rode fun��es 
		javascript e aceite cookies do sistema.
    * Para a visualiza��o dos gr�ficos � necess�rio que o PHP esteja compilado com suporte � biblioteca GD;
    * Para o upload de imagens � necess�rio que essa propriedade esteja habilitada no arquivo de configura��es do PHP (php.ini);

Instala��o
==========

Copiar o diret�rio 'ocomon' para o seu web server (/usr/local/apache2/htdocs/ usualmente no FreeBSD ou var/www/html, em sistemas Linux com Apache).
As permiss�es dos arquivos podem ser as default do seu servidor, apenas o diret�rio /includes/logs deve ter permiss�o de escrita
para todos os usu�rios, pois � o diret�rio onde s�o gravados os arquivos de log do sistema.



Criar um novo banco de dados no MySQL e nome�-lo: 'ocomon' 
Dentro do diret�rio do MYSQL no seu servidor digite:
mysql -u USERNAME -p create database ocomon 

Para a cria��o das tabelas, voc� precisa apenas rodar um �nico arquivo SQL para popular a base do sistema:
o arquivo �: _OCOMON_1.40_FULL.sql (em ocomon/install/1.40/)

Voc� pode executar o script �cima atrav�s do pr�prio mysql (seguindo o mesmo procedimento citado abaixo) ou atrav�s de algum 
gerenciador gr�fico como o phpMyAdmin por exemplo.

Voc� tamb�m pode rodar o script citado da seguinte forma:
Dentro do diret�rio do MYSQL no seu servidor digite:
mysql -uUSERNAME -p DATABASENAME < _OCOMON_1.40_FULL.sql (considerando que o script est� dentro do diret�rio do mysql)

Onde:
	USERNAME=nome do usu�rio "root" do MySQL
	DATABASENAME=nome do banco de dados criado para receber os dados do Ocomon (se voc� escolher um nome 
		     diferente de "ocomon", n�o esque�a de alterar no arquivo includes/config.inc.php
	Voc� dever� digitar a senha de root para iniciar a execu��o dos scripts.
	

IMPORTANTE: 
Ap�s a instala��o, remova a pasta "1.40" de dentro da pasta "install";


Configura��o
============

As principais configura��es necess�rias deverao ser feitas no arquivo config.inc.php, 
voc� n�o conseguir� utilizar o OCOMON at� ter configurado esse arquivo. Para isso � necess�rio criar uma c�pia do arquivo
config.inc.php-dist e renome�-lo para config.inc.php. Quanto � sua configura��o, o arquivo � auto-explicativo. :)

Iniciando o uso do OCOMON:

Passo a passo:

ACESSO
usu�rio: admin
senha: admin (N�o esque�a de alterar esse senha t�o logo tenha acesso ao sistema!!)

Novos usu�rios podem ser criados no menu ADMIN-USU�RIOS


Ainda n�o foi criada a documenta��o do sistema mas � poss�vel entender bem o seu funcionamento ap�s pouco tempo de uso, 
sua estrutura de navega��o � semelhante � uma p�gina web onde � poss�vel chegar a qualquer informa��o apenas clicando nos links. :)


Espero que esse sistema lhe seja �til e lhe ajude no seu gerenciamento de suporte e equipamentos de inform�tica 
da mesma forma que nos ajuda aqui no Unilasalle.

Bom uso!! :)

Fl�vio Ribeiro
flavio@unilasalle.edu.br


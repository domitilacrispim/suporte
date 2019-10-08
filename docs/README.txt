#
# OcoMon - vers�o 1.30
# Data: Novembro de 2005
# Autor: Fl�vio Ribeiro (flavio@lasalle.tche.br) & Franque Cust�dio (franque@procergs.rs.gov.br)
#
# Lince�a: GPL
#

Requisitos: PHP4 e MySQL

Instala��o
==========

Copiar o diret�rio 'ocomon' para o seu web server (/usr/local/apache2/htdocs/ usualmente no FreeBSD ou var/www/html, em sistemas Linux com Apache).
As permiss�es dos arquivos podem ser as default do seu servidor, apenas o diret�rio /includes/logs deve ter permiss�o de escrita
para todos os usu�rios, pois � o diret�rio onde s�o gravados os arquivos de log do sistema.



Criar um novo banco de dados no MySQL e nome�-lo: 'ocomon' 
Dentro do diret�rio do MYSQL no seu servidor digite:
mysql -u USERNAME -p create database ocomon 

Se essa for a primeira vez que voc� est� instalando o Ocomon, voc� precisa apenas rodar um �nico arquivo SQL para popular a base do sistema:
o arquivo �: _OCOMON_1.30_FULL.sql
Os demais arquivos (listados abaixo) s�o necess�rios apenas em caso de atualiza��o de alguma vers�o anterior do sistema.
Voc� pode executar o script �cima atrav�s do pr�prio mysql (seguindo o mesmo procedimento citado abaixo) ou atrav�s de algum 
gerenciador gr�fico como o phpMyAdmin por exemplo.


No diret�rio ocomon/INSTALL existem tr�s arquivos .sql: 
_OCOMON_1.14_FULL_STRUCTURE.sql (cria as tabelas do banco ocomon);
_OCOMON_1.14_DATA_START.sql (popula algumas das principais tabelas do sistema).
_OCOMON_1.14_UPDATE_TO_1.21.sql (script de atualiza��o da vers�o 1.14 para a vers�o 1.21).
_UPDATE_FROM_1.21_to_1.30.sql (Script de atualiza��o da vers�o 1.21 para a vers�o 1.30).

O script _OCOMON_1.14_FULL_STRUCTURE_FULL_STRUCTURE.sql precisa ser executado no MySQL para que as tabelas sejam criadas.
O script _OCOMON_1.14_FULL_STRUCTURE_DATA_START.sql cadastra uma s�rie de �tens padr�o no sistema, facilitando o in�cio do uso do sistema.

Voc� pode rodar os scripts citados da seguinte forma:
Dentro do diret�rio do MYSQL no seu servidor digite:
mysql -uUSERNAME -p DATABASENAME < _OCOMON_1.14_FULL_STRUCTURE.sql (considerando que os scripts est�o dentro do diret�rio do mysql)
mysql -uUSERNAME -p DATABASENAME < _OCOMON_1.14_FULL_STRUCTURE_DATA_START.sql (considerando que os scripts est�o dentro do diret�rio do mysql)
mysql -uUSERNAME -p DATABASENAME < _OCOMON_1.14_UPDATE_TO_1.21.sql
mysql -uUSERNAME -p DATABASENAME < _UPDATE_FROM_1.21_to_1.30.sql

Onde:
	USERNAME=nome do usu�rio "root" do MySQL
	DATABASENAME=nome do banco de dados criado para receber os dados do Ocomon (se voc� escolher um nome 
		     diferente de "ocomon", n�o esque�a de alterar no arquivo includes/config.inc.php
	Voc� dever� digitar a senha de root para iniciar a execu��o dos scripts.
	
Outra forma para criar a base de dados:
Se voc� tem o phpMyAdmin instalado no servidor, basta acessar a base OCOMON e rodar os scripts dentro 
da interface gr�fica do sistema.


Configura��o
============

Todas as configura��es necess�rias deverao ser feitas no arquivo config.inc.php, 
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


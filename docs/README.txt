#
# OcoMon - versão 1.30
# Data: Novembro de 2005
# Autor: Flávio Ribeiro (flavio@lasalle.tche.br) & Franque Custódio (franque@procergs.rs.gov.br)
#
# Linceça: GPL
#

Requisitos: PHP4 e MySQL

Instalação
==========

Copiar o diretório 'ocomon' para o seu web server (/usr/local/apache2/htdocs/ usualmente no FreeBSD ou var/www/html, em sistemas Linux com Apache).
As permissões dos arquivos podem ser as default do seu servidor, apenas o diretório /includes/logs deve ter permissão de escrita
para todos os usuários, pois é o diretório onde são gravados os arquivos de log do sistema.



Criar um novo banco de dados no MySQL e nomeá-lo: 'ocomon' 
Dentro do diretório do MYSQL no seu servidor digite:
mysql -u USERNAME -p create database ocomon 

Se essa for a primeira vez que você está instalando o Ocomon, você precisa apenas rodar um único arquivo SQL para popular a base do sistema:
o arquivo é: _OCOMON_1.30_FULL.sql
Os demais arquivos (listados abaixo) são necessários apenas em caso de atualização de alguma versão anterior do sistema.
Você pode executar o script àcima através do próprio mysql (seguindo o mesmo procedimento citado abaixo) ou através de algum 
gerenciador gráfico como o phpMyAdmin por exemplo.


No diretório ocomon/INSTALL existem três arquivos .sql: 
_OCOMON_1.14_FULL_STRUCTURE.sql (cria as tabelas do banco ocomon);
_OCOMON_1.14_DATA_START.sql (popula algumas das principais tabelas do sistema).
_OCOMON_1.14_UPDATE_TO_1.21.sql (script de atualização da versão 1.14 para a versão 1.21).
_UPDATE_FROM_1.21_to_1.30.sql (Script de atualização da versão 1.21 para a versão 1.30).

O script _OCOMON_1.14_FULL_STRUCTURE_FULL_STRUCTURE.sql precisa ser executado no MySQL para que as tabelas sejam criadas.
O script _OCOMON_1.14_FULL_STRUCTURE_DATA_START.sql cadastra uma série de ítens padrão no sistema, facilitando o início do uso do sistema.

Você pode rodar os scripts citados da seguinte forma:
Dentro do diretório do MYSQL no seu servidor digite:
mysql -uUSERNAME -p DATABASENAME < _OCOMON_1.14_FULL_STRUCTURE.sql (considerando que os scripts estão dentro do diretório do mysql)
mysql -uUSERNAME -p DATABASENAME < _OCOMON_1.14_FULL_STRUCTURE_DATA_START.sql (considerando que os scripts estão dentro do diretório do mysql)
mysql -uUSERNAME -p DATABASENAME < _OCOMON_1.14_UPDATE_TO_1.21.sql
mysql -uUSERNAME -p DATABASENAME < _UPDATE_FROM_1.21_to_1.30.sql

Onde:
	USERNAME=nome do usuário "root" do MySQL
	DATABASENAME=nome do banco de dados criado para receber os dados do Ocomon (se você escolher um nome 
		     diferente de "ocomon", não esqueça de alterar no arquivo includes/config.inc.php
	Você deverá digitar a senha de root para iniciar a execução dos scripts.
	
Outra forma para criar a base de dados:
Se você tem o phpMyAdmin instalado no servidor, basta acessar a base OCOMON e rodar os scripts dentro 
da interface gráfica do sistema.


Configuração
============

Todas as configurações necessárias deverao ser feitas no arquivo config.inc.php, 
você não conseguirá utilizar o OCOMON até ter configurado esse arquivo. Para isso é necessário criar uma cópia do arquivo
config.inc.php-dist e renomeá-lo para config.inc.php. Quanto à sua configuração, o arquivo é auto-explicativo. :)



Iniciando o uso do OCOMON:

Passo a passo:

ACESSO
usuário: admin
senha: admin (Não esqueça de alterar esse senha tão logo tenha acesso ao sistema!!)

Novos usuários podem ser criados no menu ADMIN-USUÁRIOS


Ainda não foi criada a documentação do sistema mas é possível entender bem o seu funcionamento após pouco tempo de uso, 
sua estrutura de navegação é semelhante à uma página web onde é possível chegar a qualquer informação apenas clicando nos links. :)


Espero que esse sistema lhe seja útil e lhe ajude no seu gerenciamento de suporte e equipamentos de informática 
da mesma forma que nos ajuda aqui no Unilasalle.

Bom uso!! :)

Flávio Ribeiro
flavio@unilasalle.edu.br


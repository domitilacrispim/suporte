#
# OcoMon - versão 1.40
# Data: Março de 2006
# Autor: Flávio Ribeiro (flavio@lasalle.tche.br) - Baseado no Código Original do Ocomon 0.70 do Franque Custódio
#
# Linceça: GPL
#

Requisitos: 
    * Servidor Web (preferencialmente Apache);
    * Linguagem: PHP versão:4.3x, HTML, CSS, Javascript;
    * Banco de dados: MySQL versão: 4.1x;
    * Diretiva register_globals=On no arquivo de configuração do PHP(php.ini). 
    	/*Atualmente o sistema está sendo reescrito para atender à recomendação register_globals=Off*/
  	
Notas importantes:
 
    * Para o sistema funcionar adequadamente é necessário que seu navegador permita que sistema rode funções 
		javascript e aceite cookies do sistema.
    * Para a visualização dos gráficos é necessário que o PHP esteja compilado com suporte à biblioteca GD;
    * Para o upload de imagens é necessário que essa propriedade esteja habilitada no arquivo de configurações do PHP (php.ini);

Instalação
==========

Copiar o diretório 'ocomon' para o seu web server (/usr/local/apache2/htdocs/ usualmente no FreeBSD ou var/www/html, em sistemas Linux com Apache).
As permissões dos arquivos podem ser as default do seu servidor, apenas o diretório /includes/logs deve ter permissão de escrita
para todos os usuários, pois é o diretório onde são gravados os arquivos de log do sistema.



Criar um novo banco de dados no MySQL e nomeá-lo: 'ocomon' 
Dentro do diretório do MYSQL no seu servidor digite:
mysql -u USERNAME -p create database ocomon 

Para a criação das tabelas, você precisa apenas rodar um único arquivo SQL para popular a base do sistema:
o arquivo é: _OCOMON_1.40_FULL.sql (em ocomon/install/1.40/)

Você pode executar o script àcima através do próprio mysql (seguindo o mesmo procedimento citado abaixo) ou através de algum 
gerenciador gráfico como o phpMyAdmin por exemplo.

Você também pode rodar o script citado da seguinte forma:
Dentro do diretório do MYSQL no seu servidor digite:
mysql -uUSERNAME -p DATABASENAME < _OCOMON_1.40_FULL.sql (considerando que o script está dentro do diretório do mysql)

Onde:
	USERNAME=nome do usuário "root" do MySQL
	DATABASENAME=nome do banco de dados criado para receber os dados do Ocomon (se você escolher um nome 
		     diferente de "ocomon", não esqueça de alterar no arquivo includes/config.inc.php
	Você deverá digitar a senha de root para iniciar a execução dos scripts.
	

IMPORTANTE: 
Após a instalação, remova a pasta "1.40" de dentro da pasta "install";


Configuração
============

As principais configurações necessárias deverao ser feitas no arquivo config.inc.php, 
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


<?php

// arquivo: config.inc.php

// configurar de acordo com as suas necessidades - PHP4

// Configura��es vitais

//Usuario do banco
define ( "SQL_USER", "ocomon"); //NOME DO USU�RIO PARA ACESSAR A BASE DO OCOMON

//Senha do banco
define ( "SQL_PASSWD", "3c5x9cfg");//AQUI DEVE SER COLOCADA A SENHA DO BASE DO OCOMON
//Servidor do banco
define ( "SQL_SERVER", "172.30.0.72"); //SE O BANCO DE DADOS ESTIVER EM UM SERVIDOR DIFERENTE DO SERVIDOR WEB DEVE-SE ALTERAR O VALOR "localhost"
//Nome do banco
define ( "SQL_DB", "ocomondesenv");//NOME DO BANCO DE DADOS. PADR�O: OCOMON


define ( "DB_CCUSTO","ocomon"); //Base de dados onde s�o buscados os Centros de Custos, o padr�o: ocomon.
define ( "TB_CCUSTO","CCUSTO"); //Tabela de CEntro de custos dentro da base de dados - padr�o: CCUSTO
define ( "CCUSTO_ID","codigo");//Chave prim�ria da tabela de centros de custo
define ( "CCUSTO_DESC","descricao"); //Campo referente � descri��o do Centro de Custo
define ( "CCUSTO_COD","codccusto"); //Campo referente ao c�digo de Centro de Custo


define ( "SYS", "ocomon");
define ( "OCOMON_SITE", "http://ocomon/suporte/consultaChamado.php"); //Configure com o endere�o para acesso ao sistema dentro da sua empresa, esse ser�
																		//o link no rodap� dos e-mails enviados pelo sistema;

define ("INST_TERCEIRA", "-1"); //Define que UNIDADES  n�o devem aparecer na estat�stica geral da tela de abertura - o c�digo deve ser extraido da tabela: INSTITUICOES. 
									//Por padr�o todas as unidades aparecem na estat�stica inicial.

define ( "LOG_PATH", "../../logs/logs.txt"); //Esse diret�rio deve ter permiss�o de escrita pra gravar os logs.
define ( "LOGO_PATH", "../../includes/logos");
define ( "ICONS_PATH", "../../includes/icons/");

define ( "HELP_ICON", "".ICONS_PATH."solucoes2.png");
define ( "HELP_PATH", "../../includes/help/");

define ( "LANGUAGE", "pt_BR.php");



//Define o tipo de autentica��o do sistema , por padr�o a altentica��o � feita na tabela de usu�rios do pr�prio OCOMON, por�m isso pode ser feito atrav�s de um servi�o LDAP.
//INICIALMENTE � NECESS�RIO DEIXAR A AUTENTICA��O LOCAL PARA QUE SEJA POSS�VEL ACESSAR O SISTEMA PELA PRIMEIRA VEZ E CRIAR OS USU�RIOS.
define ( "AUTH_TYPE" , "SYSTEM"); //DEFAULT
#define ( "AUTH_TYPE", "LDAP"); // ALTERNATIVE

/* Vari�veis para conex�o LDAP   DEVEM SER CONFIGURADAS EM CASO DE AUTH_TYPE==LDAP   DE ACORDO COM  AS CONFIGURA��ES DO SEU SERVIDOR LDAP*/
define ( "LDAP_HOST", "localhost"); //IP do servidor LDAP
define ( "LDAP_DOMAIN", "ou=People,dc=yourdomain,dc=edu,dc=br"); 
define ( "LDAP_DOMAIN_SEC", "ou=People,dc=yourdomain1,dc=yourdomain2,dc=edu,dc=br"); //Segundo dom�nio LDAP
define ( "LDAP_DN", "cn=admin,dc=yourdomain,dc=edu,dc=br");
define ( "LDAP_PASSWORD", "");	


		//CARGA HOR�RIA DE CADA AREA DE ATENDIMENTO - OS PAR�METROS S�O: HORA DE INICIO, HORA DE FIM...
		//... FINAL DO INTERVALO, E CARGA DE TRABALHO NO S�BADO!!	
        $H_default = array (7,19,13,4);  //AQUI A JORNADA �: DAS 8:00 �S 22:00 , HORA FINAL DE INTERVALO: 13:00 E S�BADO S�O TRABALHADAS 4 HORAS

		$H_horarios = array (1=>$H_default);//PARA CADA �REA DE ATENDIMENTO QUE TIVER HOR�RIO DE ATENDIMENTO DIVERENTE DO DEFAULT...
											//...DEVE SER CRIADA UMA ENTRADA NO ARRAY H_horarios
											/*EX: 
											
												$H_novaArea = array (10,18,13,0); AQUI O HOR�RIO DE IN�CIO � 10:00 E O FINAL DE EXPEDIENTE � 18:00 
																SEM TRABALHO NO S�BADO (vida boa..)  :)
												
												$H_horarios = array (1=>$H_default, 2=>$H_novaArea);
											*/



?>
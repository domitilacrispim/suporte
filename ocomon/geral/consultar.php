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
         GNU General Public License forconecta more details.
  
         You should have received a copy of the GNU General Public License
         along with Foobar; if not, write to the Free Software
         Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  */

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	$s_page_ocomon = "consultar.php";
	session_register("s_page_ocomon");
    $conec = new conexao;
    $PDO = $conec->conectaPDO();
    $query2 = "SELECT ver_nao_atribuidos FROM usuarios WHERE user_id = ".$_SESSION['s_uid']." LIMIT 1";
    $resultado2 = $PDO->query($query2) or die ('ERRO AO TENTAR RECUPERAR AS INFORMA��ES DE USU�RIO! '.$query2);
    $linha2 = $resultado2->rowCount();

    $nao_atrib = $linha2['ver_nao_atribuidos'];
	
?>
<HTML>
<head><script language="JavaScript" src="../../includes/javascript/calendar1.js"></script></head>
<BODY bgcolor=<?print BODY_COLOR?>>

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?print TAB_COLOR?>>
                        <TR>
                        <?
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=".$cor1." nowrap><b>OcoMon - M�dulo de ocorrências</b></TD><td width='20%' nowrap><p class='parag'><b>".transvars(date ("l d/m/Y H:i"),$TRANS_WEEK)."</b></p></TD>"; 
                        echo menu_usuario();
                        if ($s_nivel==1)
                        {
                                echo menu_admin();
                        }
                        ?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>
<BR>
<B>Consulta de ocorrências:</B>
<BR>

<FORM method="POST"  name="form1" action='mostra_resultado_consulta.php' onSubmit="return valida()">
<TABLE border="0"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>N�mero inicial:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text'  name="numero_inicial" id="idNumeroInicial"></TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>N�mero final:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="numero_final" id="idNumeroFinal"></TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Problema:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <SELECT class='select' name='problema' size=1>
                <?print "<option value=-1 selected>-  Selecione um problema -</option>";
                $query = "SELECT * from problemas order by problema";
                $resultado =$PDO->query($query);
                $linhas = $resultado->rowCount();
                $i=0;

                while ($aux=$resultado->fetch(PDO::FETCH_BOTH))
                {
                       ?>
                       <option value="<?print $aux[0];?>">
                                         <?print $aux[1];?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>
                </TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>�REA RESPONS�VEL:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>

                <SELECT class='select' name='sistema' size=1>
                <?print "<option value=-1 selected>-  �REA RESPONS�VEL -</option>";
                $query = "SELECT * from sistemas where sis_id NOT in (2,10) order by sistema";
                $resultado =$PDO->query($query);
               while ($aux=$resultado->fetch(PDO::FETCH_BOTH))
                {
                       ?>
                       <option value="<?print $aux[0];?>">
                                         <?print $aux[1];?>
                       </option>
                       <?

                }
                ?>
                </SELECT>
                </TD>
        </TR>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Descri��o:</TD>
                <TD colspan='3' width="80%" align="left" bgcolor=<?print BODY_COLOR?>><TEXTAREA class='textarea' name="descricao" id="idDescricao"></textarea></TD>
        </TR>

        <TR>
				<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Unidade:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select' name='instituicao' size=1>
				<?print "<option value=-1 selected>Selecione a Unidade</option>";
                $query2 = "SELECT * from instituicao order by inst_cod";
                $resultado2 =$PDO->query($query2);
                while ($aux=$resultado2->fetch(PDO::FETCH_BOTH))
                {
                    ?>
                    <option value="<?print $aux[0];?>">
                        <?print $aux[1];?>
                    </option>
                    <?

                }
                ?>
                </SELECT>

				</TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Etiqueta</b> do equipamento:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="equipamento" id="idEtiqueta"></TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Contato:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="contato" id="idContato"></TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Ramal:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="telefone" id="idRamal"></TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Local:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>

                <?print "<SELECT class='select' name='local' size=1>";
                print "<option value=-1 selected>-  Selecione um local -</option>";
                $query = "SELECT * from localizacao order by local";
                $resultado = $PDO->query($query);
                while ($aux=$resultado->fetch(PDO::FETCH_BOTH))
                {
                    ?>
                    <option value="<?print $aux[0];?>">
                        <?print $aux[1];?>
                    </option>
                    <?

                }
                ?>
                </SELECT>

                </TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Operador:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>

                <?print "<SELECT class='select' name='operador' size=1>";
                print "<option value=-1 selected>-  Selecione um operador -</option>";
                $query = "SELECT * from usuarios order by nome";
                $resultado = $PDO->query($query);

                while ($rowU = $resultado->fetch(PDO::FETCH_BOTH))
                {
                       ?>
                       <option value="<?print $rowU['user_id'];?>">
                                         <?print $rowU['nome'];?>
                       </option>
                       <?
                }
                ?>
                </SELECT><input type='checkbox' name='opAbertura'  title='Marque essa op��o para buscar sobre o operador que abriu o chamado'>Origem

                </TD>

        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Data Inicial:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print "<INPUT type=text class='data' name='data_inicial' id='idDataInicial' value=\"$hoje\" >";?><a href="javascript:cal1.popup();"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></TD>
                <TD width="10%" align="left" bgcolor=<?print TD_COLOR?>>Data Final:</TD>
                <TD width="10%" align="left" bgcolor=<?print BODY_COLOR?>><?print "<INPUT type=text class='data' name='data_final' id='idDataFinal' value=\"$hoje\" >";?><a href="javascript:cal2.popup();"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></TD>
        <?

		
		
		?>
		
		
		</tr>        
		
		<tr>
				<TD width="10%" align="left" bgcolor=<?print TD_COLOR?>>Data de:</TD>
                <TD width="20%" align="left" bgcolor=<?print BODY_COLOR?>>
                <SELECT class='select' name="tipo_data" size=1>";
                <option value="abertura" selected>Abertura</option>";
                <option value="fechamento">Fechamento</option>
                </SELECT>
                </TD>
                
				<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Ordenar por:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>

                <SELECT class='select' name="ordem" size=1>
                <option value="numero" selected>N�mero</option>
                <option value="problema">Problema</option>
                <option value="area">�rea</option>
                <option value="etiqueta">Equipamento</option>
                <option value="contato">Contato</option>
                <option value="setor">Local</option>
                <option value="nome">Operador</option>
                <option value="data">Data</option>
                </SELECT>

                </TD>
        
		</TR>
<?php if($nao_atrib == 1){ ?>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Status:</TD>
                <TD width="30%" colspan="3" align="left" bgcolor=<?print BODY_COLOR?>>

                <?print "<SELECT class='select' name='status' size=1>";
                print "<option value='Em aberto'>Em aberto</option>";
                $query = "SELECT * from status order by status";
                $resultado =$PDO->query($query);
                while ($aux=$resultado->fetch(PDO::FETCH_BOTH))
                {
                       ?>
                       <option value="<?print $aux[0];?>"<?
					   						if ($aux[0]==15)/*Todos*/ {
                                                print " selected>";                                        
                                            } else print ">"?>
                                         <?print $aux[1];?>
                       </option>
                       <?
                       $i++;
                }
                print "</select>";
				?>
                </td>
        </TR>
    <?php }else{
        ?>
    <input type="hidden" name="status" value='Em aberto' />
    <?php
    }?>
        <TR>
                <BR>
                <TD colspan='2' align="center" width="50%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="    Ok    " name="ok" onclick="ok=sim">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD colspan='2' align="center" width="50%" bgcolor=<?print BODY_COLOR?>><INPUT type="button" value="Cancelar" name="desloca" ONCLICK="javascript:location.href='abertura.php'"></TD>
        </TR>

</TABLE>
</FORM>
			<script language="JavaScript"> 
			 // create calendar object(s) just after form tag closed
				 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
				 // note: you can have as many calendar objects as you need for your application
				var cal1 = new calendar1(document.forms['form1'].elements['data_inicial']);
				cal1.year_scroll = true;
				cal1.time_comp = false;
				var cal2 = new calendar1(document.forms['form1'].elements['data_final']);
				cal2.year_scroll = true;
				cal2.time_comp = false;				
			
			
			function valida(){
				var ok = validaForm('idNumeroInicial','INTEIRO','N�mero inicial',0);
				if (ok) var ok = validaForm('idNumeroFinal','INTEIRO','N�mero final',0);
				if (ok) var ok = validaForm('idEtiqueta','ETIQUETA','Etiqueta',0);
				if (ok) var ok = validaForm('idRamal','INTEIRO','Ramal',0);
				if (ok) var ok = validaForm('idDataInicial','DATA-','Data inicial',0);	
				if (ok) var ok = validaForm('idDataFinal','DATA-','Data final',0);				
				
				return ok;
			
			}					
			
			//-->				
			</script>
</BODY>
</HTML>

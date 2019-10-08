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

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	$cab = new headers;
	$cab->set_title(HTML_TITLE);
    $conec = new conexao;
    $PDO = $conec->conectaPDO();
	$auth = new auth;


		if ($popup) {
			$auth->testa_user_hidden($s_usuario,$s_nivel,$s_nivel_desc,4);
		} else
			$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);
?>
	
		<p align='center'><b>Altera��o de senha</b></p>
		<FORM method="POST" action='<?$PHP_SELF?>' onSubmit="return valida()">
		<center><TABLE border="0"  align="center" width="10%" bgcolor=<?print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Senha atual:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="password" name="passwordAtual" class='logon' id='idSenhaAtual'></TD>
	</tr>
	<tr>      
         		<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Nova senha:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><input type="password" name="password" class='logon' id='idSenha'></TD>
	</TR>
	<tr>        
        		<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Confirmar:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="password" name="password2" class='logon' id='idSenha2'>
		
			</TD>
	</tr>	
	<tr>
        
                <TD align="center" width="25%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="Alterar" name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
			<TD align="left" width="25%" bgcolor=<?print BODY_COLOR?>><INPUT type="button" value="Cancelar" name="desloca" ONCLICK="javascript:history.back()">
                </TD>
                

        </TR>
		
		</form>
		</table></center>
             
<?			 
			    if ($rodou == "sim")
                {
						$erro="n�o";                        
						
						if (($password != $password2)or ($password=='')) {
							$erro="sim";
                            $aviso = "Voc� n�o digitou a mesma senha nas duas vezes!";							    
						}
						
						if ($erro=="n�o") {
							 $password = md5($password);
							 //$passwordAtual = md5($passwordAtual);
							if ($passwordAtual != $s_senha) {
								$erro = "sim";
								$aviso = "Senha atual n�o confere";	
							}
						}
						


                        if (empty($password) or empty($password2))
                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }

                        if ($erro == "n�o")
                        {
                                $query = "UPDATE usuarios SET password='$password' WHERE login = '$s_usuario'";
                                $resultado =$PDO->query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = "ERRO ao alterar senha no sistema.";
                                }
                                else
                                {
                                        $aviso = "OK. Senha alterada com sucesso.";
                                }
                        }
                
				if ($erro == "n�o") {$orig= 'abertura.php';} else $orig = 'altera_senha.php';
				
				$origem = "$orig";
                session_register("aviso");
                session_register("origem");
                        ?>
						<script language="javascript">
						<!--
							mensagem('<?print $aviso;?>');
							history.go(-2)();
						//-->
						</script>   
						<?

			   //echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=abertura.php\">";
                }
        ?>
<script type="text/javascript">
<!--			
	function compPass (){
		var obj = document.getElementById('idSenha');
		var obj2 = document.getElementById('idSenha2');
		if (obj.value != obj2.value) {
			alert('As senhas digitadas n�o conferem!');
			return false; 
		} else
			return true;	
	}
	
	function valida(){
		
	
		var ok = validaForm('idSenhaAtual','','Senha Atual',1);
		if (ok) var ok = validaForm('idSenha','ALFANUM','Senha',1); 
		if (ok) var ok = validaForm('idSenha2','ALFANUM','Senha',1);
		if (ok) var ok = compPass();
		 
		return ok;
	}		
-->	
</script>
		

		</body>
		</html>	
	<?	

?>
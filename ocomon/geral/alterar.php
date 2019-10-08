<!DOCTYPE html>
<HTML>
<head>

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
	$s_page_ocomon = "alterar.php";
	session_register("s_page_ocomon");
$conec = new conexao;
$PDO = $conec->conectaPDO();
?>

</head>
<body>
<div class="container-fluid">

<?php
	$auth = new auth;
	if ($popup) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);
?>

    <div class="panel panel-warning">
        <div class="panel-heading">
            <h4 class="panel-title">Busca de ocorrências: <?php echo $subCallMsg?></h4>
        </div>

        <form method="POST" action="<?$PHP_SELF?>" onSubmit="return valida()" >

            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="idSistema">N�mero(s):</label>
                            <input type="text" class='form-control'  name='numero' id='idEtiqueta' />
                        </div>
                    </div>

                    <input type="hidden" name="rodou" value="sim">

                    <div class="col-sm-4 mt-17">

                        <button type="submit" class="btn btn-success">Enviar</button>

                        <button type="button" class="btn btn-default" name='desloca' ONCLICK="javascript:location.href='abertura.php'">Cancelar</button>

                    </div>
                </div>

<?php
                if ($rodou == "sim"){

                    //$query  = "select o.*, u.* from ocorrencias as o, usuarios as u where o.operador = u.user_id and o.numero in ($numero)";
                    $query = $QRY["ocorrencias_full_ini"]." where numero in (".$numero.") order by numero";
                    $resultado = $PDO->query($query);
                    $linhas = $resultado->rowCount();

                    if ($linhas==0){
                        echo mensagem("Nenhuma ocorrência localizada!",'','danger');
                    }else{

                        $cor=TAB_COLOR;
                        $cor1=TD_COLOR;
?>
                        <table class="table">

                            <tr class='header'>
                                <td>Chamado</td>
                                <td>Problema</td>
                                <td>Contato</td>
                                <td WIDTH=250>Local</td>
                                <td>Abertura</td>
                                <td>Status</td>
                            </tr>
<?php
                            $j = 2;
                            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)){

                                if ($j % 2) $trClass= "lin_par";
                                else $trClass = "lin_impar";

            /*					$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']." or dep_filho=".$row['numero']."";
                                $execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
                                $regSub = mysql_num_rows($execSubCall);
                                if ($regSub > 0) {
                                    $imgSub = "<img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='Chamado com v�nculos'>";
                                } else
                                    $imgSub = "";*/

                                $sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']." or dep_filho=".$row['numero']."";
                                $execSubCall = $PDO->query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
                                $regSub = $execSubCall->rowCount();

                                if ($regSub > 0) {
                                    #� CHAMADO PAI?
                                    $sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']."";
                                    $execSubCall = $PDO->query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
                                    $regSub = $execSubCall->rowCount();
                                    $comDeps = false;

                                    while ($rowSubPai = $execSubCall->fetch(PDO::FETCH_ASSOC)){
                                        $sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=".$rowSubPai['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3) ";
                                        $execStatus = $PDO->query($sqlStatus) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE STATUS DOS CHAMADOS FILHOS<br>'.$sqlStatus);
                                        $regStatus = $execStatus->rowCount();
                                        if ($regStatus > 0) {
                                            $comDeps = true;
                                        }
                                    }

                                    if ($comDeps)
                                        $imgSub = "<img src='".ICONS_PATH."view_tree_red.png' width='16' height='16' title='Chamado com v�nculos pendentes'>";
                                    else
                                        $imgSub =  "<img src='".ICONS_PATH."view_tree_green.png' width='16' height='16' title='Chamado com v�nculos mas sem pend�ncias'>";
                                } else
                                    $imgSub = "";


?>
                                <tr class='<?print $trClass;?>'>


                                <td><a href='mostra_consulta.php?numero=<?print $row['numero'];?>'><?print $row['numero'];?><a><?print $imgSub;?></td>
                                <td><?print $row['problema'];?></td>
                                <td><?print $row['contato'];?></td>
                                <td><?print $row['setor'];?></td>
                                <td><?print datab($row['data_abertura']);?></td>
                                <td><?print $row['chamado_status'];?></td>
                                </tr>
<?php
                                $j++;
                            }
?>
                        </table>
<?php
                        }
                }
?>
            </div>
        </form>

    </div>
</body>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idEtiqueta','ETIQUETA','ocorrências',1);
		//var ok = validaForm('idEtiqueta','ALFANUM','ocorrências',1);
		
		return ok;
	}		
-->	
</script>
</html>

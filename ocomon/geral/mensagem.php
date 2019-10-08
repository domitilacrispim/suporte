<!DOCTYPE html>
<HTML>
<head>
<?php

 /*                        Copyright 2005 Flávio Ribeiro
  
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

?>

</head>
<body>

<div class="container-fluid">



<?php
        $msg = "<a href=".$origem.">Voltar</a>";
        //echo mensagem($aviso,$msg);
?>
    <div class="panel panel-info">

        <div class="panel-heading">
            <h4 class="panel-title">
                Aviso
            </h4>
        </div>
        <div class="panel-body">
            <div class="alert alert-info">
                <?php echo $aviso;?>
            </div>
        </div>
        <div class="form-actions right">
            <a href="<?php echo $origem;?>" class="btn btn-default">Voltar</a>
        </div>

    </div>

</div>
</body>
</HTML>
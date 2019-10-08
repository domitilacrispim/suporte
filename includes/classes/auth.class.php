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


class auth {
	var $saida;
	var $texto;

	
	function testa_user($s_usuario, $s_nivel, $s_nivel_desc, $permissao){
	include ("../../includes/languages/".LANGUAGE."");		
		if ($s_nivel>$permissao) //se o n�vel do usu�rio for maior do que a permiss�o necess�ria para o script..
		{
		        $this->saida= "<script>window.open('../../index.php','_parent','')</script>";
		} else {
			if (is_file( "./.invmon_dir" )) $this->texto = $TRANS["menu_title"]; else 
			if (is_file( "./.admin_dir" )) $this->texto = $TRANS["menu_title_admin"];
			else $this->texto = $TRANS["menu_title_ocomon"]; 
			
			$this->saida = "
			    <div class=\"row\">

                    <div class=\"col-sm-6\">
                        <h1 class=\"aw-page-title\">{$this->texto}</h1>
                    </div>
                    <div class=\"col-sm-6 text-right\">
                        <p>".transvars(date ("l d/m/Y H:i"),$TRANS_WEEK)."</p>
                    </div>";
								
            if ($s_nivel==1)
                $this->saida.= menu_usuario_admin();
            else
                $this->saida.= menu_usuario();
		                        
		    $this->saida.= "</div>";
	
		}
		print $this->saida;

	}

	function testa_user_hidden($s_usuario, $s_nivel, $s_nivel_desc, $permissao){
	
	include ("../../includes/languages/".LANGUAGE."");		
		if ($s_nivel>$permissao)
		{
		        $this->saida= "<script>window.open('../../index.php','_parent','')</script>";
		} else {
			if (is_file( "./.invmon_dir" )) $this->texto = $TRANS["menu_title"]; 
			else $this->texto = $TRANS["menu_title_ocomon"]; 
			$this->saida =  "\n<TABLE class=header>
		        		<tr class=topo>
						<TD>
		                <TABLE class=menu>
		                        <TR class=topo>
		                        <TD><b>".$this->texto."  -  ".$TRANS["usuario"].": 
								<font color='red'><a title='".$TRANS["hint_usuario"]."'>$s_usuario</a></font></b></td><td width=25%>
								<b>".$TRANS["nivel"].": <font color='red'><a title='".$TRANS["hint_nivel"]."'>".$s_nivel_desc."</a></font></b></TD>";
								
		                        
		                        $this->saida.= "</TR>
		                	</TABLE>
		        		</TD>
					</tr>
					</TABLE>";	
	
		}
		print $this->saida;
	}

}
?>
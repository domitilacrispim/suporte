<?php

class form {

	
	var $commit;
	var $linhas;
	var $select;
	var $row;
	
	
	function executa ($query){
		$this->commit=mysql_query($query);
		$this->linhas=mysql_num_rows($this->commit);
	}
	
	# @$query = O SQL que ser� executado;
	# @$name = O nome do SELECT no HTML;
	# @$default = Valor default do SELECT;
	# @$selected = Texto padrao selecionado;
	# @$value = Campo da tabela que ir� conter o valor da sele��o;
	# @$saida = Campo que aparecer� no SELECT;
	# @$class = Nome da classe CSS que o select ter�;
	
	function combo ($query,$name, $default, $selected, $value, $saida, $class){
		$this->executa($query);
		$this->select="<select name=".$name." class=".$class.">";	
		$this->select.="<option value=".$default.">".$selected."</option>";
		while($this->row=mysql_fetch_array($this->commit)){
			$this->select.="<option value=".$this->row[$value].">".$this->row[$saida]."</option>";
			
		} // while	
		$this->select.="</select>";
		print $this->select;
	}


	
	

}

?>
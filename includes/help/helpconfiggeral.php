<?
    print "<html><head><title>Configuração do sistema</title>"; 
    
			print "<style type=\"text/css\"><!--";
			print "body.corpo {background-color:#F6F6F6; font-family:helvetica;}";
            print "p{font-size:12px; text-align:justify; }";
            print "table.pop {width:100%; margin-left:auto; margin-right: auto; text-align:left; 
					border: 0px; border-spacing:1 ;background-color:#f6f6f6; padding-top:10px; }";
			print "tr.linha {font-family:helvetica; font-size:10px; line-height:1em; }";			
			print "--></STYLE>";			    
    print "</head><body class='corpo'>";
    
   

        print "<p><b>Configuração de Upload de imagens nos chamados:</b></p>";
		print "<ul>";
		print "<li><p>TAMANHO MÁXIMO: É o tamanho máximo (em bytes) do arquivo de imagem a ser feito o upload;</p></li>";
		print "<li><p>LARGURA MÁXIMA: É a largura máxima (em pixels) permitida para a imagem a ser feito o upload;</p></li>";		
		print "<li><p>ALTURA MÁXIMA: É a altura máxima (em pixels) permitida para a imagem a ser feito o upload;</p></li>";		
		print "</ul>";

    print "</body></html>";

?>
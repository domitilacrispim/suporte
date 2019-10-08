<?

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

<HTML>
<BODY bgcolor=<?print BODY_COLOR?>>



<?
        $msg = "<a href=".$origem.">Voltar</a>";
        //echo mensagem($aviso,$msg);
?>

<BR>
        <table cellspacing=1 border=1 cellpadding=1 align='center'>
                <Td bgcolor='<?print TD_COLOR;?>'>
                <table bgcolor='<?print TD_COLOR;?>'>
                        <table width=320 border=0 cellpadding=0 cellspacing=0 align='center' bgcolor='<?print TD_COLOR;?>'>
                                <tr>
                                        <TD WIDTH='50%' ALIGN='center' VALIGN='center' bgcolor='<?print TD_COLOR;?>'><? print $aviso;?></TD>
                                </tr>
                                <tr>
                                        <td>
                                        <table width=320 border=0  cellpadding=0 cellspacing=0 align='center' bgcolor='<?print TD_COLOR;?>'>
                                                <tr align='center'>
                                                        <TD WIDTH='50%' ALIGN='center' VALIGN='center' bgcolor='<?print TD_COLOR;?>'><a href='<?print $origem;?>'>Voltar</a></TD>
                                                </tr>
                                        </table>
                                        </td>
                                </tr>
                        </table>
                </table>
                </td>
        </table>

</BODY>
</HTML>

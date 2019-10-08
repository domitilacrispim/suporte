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
         GNU General Public License for more details.
  
         You should have received a copy of the GNU General Public License
         along with Foobar; if not, write to the Free Software
         Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  */

		include ("../../includes/jpgraph/src/jpgraph.php");
		include ("../../includes/jpgraph/src/jpgraph_pie.php");
		include ("../../includes/jpgraph/src/jpgraph_pie3d.php");

		
		//$data = array(40,60,21,33);
		
		$graph = new PieGraph(600,480,"auto");
		$graph->SetShadow();
		//$graph->SetAntiAliasing();
		//$titulo=$titulo.$instituicao;
		
		$graph->title->Set($titulo);
		//$graph->subtitle->Set($instituicao);		
		$graph->title->SetFont(FF_FONT1,FS_BOLD);
		
		$p1 = new PiePlot3D($data);
		$p1->ExplodeAll();
		$p1->SetSize(0.45);
		$p1->SetCenter(0.35);
		
		$p1->SetLegends($legenda);
		
		$graph->Add($p1);
		$graph->Stroke();				


		
?>		
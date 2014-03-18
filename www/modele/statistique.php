<?php

include( "/lib/sparqllib.php" );
include("lib/pChart/class/pData.class.php");
include("lib/pChart/class/pDraw.class.php");
include("lib/pChart/class/pPie.class.php");
include("lib/pChart/class/pImage.class.php");

function getlistepays()
{
	$db = sparql_connect("http://dbpedia.org/sparql");

	if(!$db)
	{ 
		print sparql_errno() . ": " . sparql_error(). "\n"; exit;
	}
 
	$req="
		PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
		PREFIX yago: <http://dbpedia.org/class/yago/>
		PREFIX dbpedia-owl: <http://dbpedia.org/ontology/>
		SELECT ?place WHERE { ?place rdf:type yago:EuropeanCountries }";
	
	$result = sparql_query($req);  
	
	if(!$result)
	{
		print sparql_errno() . ": " . sparql_error(). "\n"; exit;
	}
	
	$i=0;
	$fields = sparql_field_array($result);
	
	while($row = sparql_fetch_array($result))
	{
		$tab[$i] = $row['place'];
		$tab[$i] = substr($tab[$i],28);
		$i++;
	}

	return $tab;
}

function getpopgraph($tableaupays)
{
	/* recupereration des valeurs*/
	
	$MyData = new pData(); 
	
	for($i=1;$i<=count($tableaupays);$i++)
	{
		$db = sparql_connect("http://dbpedia.org/sparql");

		if(!$db)
		{ 
			print sparql_errno() . ": " . sparql_error(). "\n"; exit;
		}
	 
		$req='
			PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
			PREFIX yago: <http://dbpedia.org/class/yago/>
			PREFIX dbpedia-owl: <http://dbpedia.org/ontology/>
			PREFIX prop: <http://dbpedia.org/property/>
			PREFIX foaf: <http://xmlns.com/foaf/0.1/>
			SELECT ?place,?pop WHERE { 
			?place rdf:type yago:EuropeanCountries;
			prop:populationEstimate ?pop;
			foaf:name "'.$tableaupays[$i].'"@en }';
				
		$result = sparql_query($req);  
		
		if(!$result)
		{
			print sparql_errno() . ": " . sparql_error(). "\n"; exit;
		}
		
		$fields = sparql_field_array($result);
	
		while($row = sparql_fetch_array($result))
		{
			$MyData->addPoints(array($row['pop']),"Nombre_habitant");
			$MyData->addPoints(array(substr($row['place'],28)),"Nom_pays"); 
		}
	
	}
	
	$MyData->setSerieDescription("Nombre_habitant","Nombre d'habitant");
	$MyData->setAbscissa("Nom_pays");
	
	$MyData->loadPalette("lib/pChart/palettes/spring.color", TRUE);

	/* Create the pChart object */
	$myPicture = new pImage(300,180,$MyData,TRUE);
		
	/* Set the default font properties */ 
	$myPicture->setFontProperties(array("FontName"=>"lib/pChart/fonts/Forgotte.ttf","FontSize"=>18,"R"=>255,"G"=>255,"B"=>255));
		
	/* Draw a solid background */
	$Settings = array("R"=>0, "G"=>0, "B"=>0,"Alpha"=>50);
	$myPicture->drawFilledRectangle(0,0,300,300,$Settings);

	/* Create the pPie object */ 
	$PieChart = new pPie($myPicture,$MyData);
	
		/* Draw a splitted pie chart */ 
	$PieChart->draw3DPie(105,105,array("WriteValues"=>PIE_VALUE_NATURAL,"Radius"=>100,"DataGapAngle"=>12,"DataGapRadius"=>10,"Border"=>TRUE));

	/* Write the legend box */ 
	$myPicture->setFontProperties(array("FontName"=>"lib/pChart/fonts/Forgotte.ttf","FontSize"=>15,"R"=>255,"G"=>255,"B"=>255));
	$PieChart->drawPieLegend(210,25,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_VERTICAL));

	// add some text
	$TextSettings = array("R"=>255,"G"=>255,"B"=>255);
	$myPicture->drawText(5,20,"Repartition des populations :",$TextSettings);
	
	/* Render the picture (choose the best way) */

	$myPicture->Render("piepop.png");

	return "<p align='center'><img  src='piepop.png' alt='TDC' width='35%' height='35%'/><p>";
}



function getpopdensgraph($tableaupays)
{
	/* recupereration des valeurs*/
	
	$MyData = new pData(); 
	
	for($i=1;$i<=count($tableaupays);$i++)
	{
		$db = sparql_connect("http://dbpedia.org/sparql");

		if(!$db)
		{ 
			print sparql_errno() . ": " . sparql_error(). "\n"; exit;
		}
	 
		$req='
			PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
			PREFIX yago: <http://dbpedia.org/class/yago/>
			PREFIX dbpedia-owl: <http://dbpedia.org/ontology/>
			PREFIX prop: <http://dbpedia.org/property/>
			PREFIX foaf: <http://xmlns.com/foaf/0.1/>
			SELECT ?place,?pop WHERE { 
			?place rdf:type yago:EuropeanCountries;
			dbpedia-owl:populationDensity ?pop;
			foaf:name "'.$tableaupays[$i].'"@en}';
				
		$result = sparql_query($req);  
		
		if(!$result)
		{
			print sparql_errno() . ": " . sparql_error(). "\n"; exit;
		}
		
		$fields = sparql_field_array($result);
		$z=0;
		
		while($row = sparql_fetch_array($result))
		{
			if($z%2==0)
			{
				$MyData->addPoints(array($row['pop']),"Densite");
				$MyData->addPoints(array(substr($row['place'],28)),"Nom_pays"); 
			}
			$z++;
		}
	
	}
	
	$MyData->setSerieDescription("Densite","Densité de la pop");
	$MyData->setAbscissa("Nom_pays");
	
	$MyData->loadPalette("lib/pChart/palettes/spring.color", TRUE);

	/* Create the pChart object */
	$myPicture = new pImage(300,180,$MyData,TRUE);
		
	/* Set the default font properties */ 
	$myPicture->setFontProperties(array("FontName"=>"lib/pChart/fonts/Forgotte.ttf","FontSize"=>18,"R"=>255,"G"=>255,"B"=>255));
		
	/* Draw a solid background */
	$Settings = array("R"=>0, "G"=>0, "B"=>0,"Alpha"=>50);
	$myPicture->drawFilledRectangle(0,0,300,300,$Settings);

	/* Create the pPie object */ 
	$PieChart = new pPie($myPicture,$MyData);
	
		/* Draw a splitted pie chart */ 
	$PieChart->draw3DPie(105,105,array("WriteValues"=>PIE_VALUE_NATURAL,"Radius"=>100,"DataGapAngle"=>12,"DataGapRadius"=>10,"Border"=>TRUE));

	/* Write the legend box */ 
	$myPicture->setFontProperties(array("FontName"=>"lib/pChart/fonts/Forgotte.ttf","FontSize"=>15,"R"=>255,"G"=>255,"B"=>255));
	$PieChart->drawPieLegend(210,25,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_VERTICAL));

	// add some text
	$TextSettings = array("R"=>255,"G"=>255,"B"=>255);
	$myPicture->drawText(5,20,"Densité des populations (hab/km²) :",$TextSettings);
	
	/* Render the picture (choose the best way) */

	$myPicture->Render("piepopdens.png");

	return "<p align='center'><img  src='piepopdens.png' alt='TDC' width='35%' height='35%'/><p>";
}


function getsupergraph($tableaupays)
{
	/* recupereration des valeurs*/
	
	$MyData = new pData(); 
	
	for($i=1;$i<=count($tableaupays);$i++)
	{
		$db = sparql_connect("http://dbpedia.org/sparql");

		if(!$db)
		{ 
			print sparql_errno() . ": " . sparql_error(). "\n"; exit;
		}
	 
		$req='
			PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
			PREFIX yago: <http://dbpedia.org/class/yago/>
			PREFIX dbpedia-owl: <http://dbpedia.org/ontology/>
			PREFIX prop: <http://dbpedia.org/property/>
			PREFIX foaf: <http://xmlns.com/foaf/0.1/>
			SELECT ?place,?area WHERE { 
			?place rdf:type yago:EuropeanCountries;
			dbpprop:areaKm ?area;
			foaf:name "'.$tableaupays[$i].'"@en.}';
				
		$result = sparql_query($req);  
		
		if(!$result)
		{
			print sparql_errno() . ": " . sparql_error(). "\n"; exit;
		}
		
		$fields = sparql_field_array($result);
		$z=0;
		
		while($row = sparql_fetch_array($result))
		{
			if($z%2==0)
			{
				$MyData->addPoints(array($row['area']),"Superficie");
				$MyData->addPoints(array(substr($row['place'],28)),"Nom_pays"); 
			}
			$z++;
		}
	
	}
	
	$MyData->setSerieDescription("Superficie","Superfircie du Pays");
	$MyData->setAbscissa("Nom_pays");
	
	$MyData->loadPalette("lib/pChart/palettes/spring.color", TRUE);

	/* Create the pChart object */
	$myPicture = new pImage(300,180,$MyData,TRUE);
		
	/* Set the default font properties */ 
	$myPicture->setFontProperties(array("FontName"=>"lib/pChart/fonts/Forgotte.ttf","FontSize"=>18,"R"=>255,"G"=>255,"B"=>255));
		
	/* Draw a solid background */
	$Settings = array("R"=>0, "G"=>0, "B"=>0,"Alpha"=>50);
	$myPicture->drawFilledRectangle(0,0,300,300,$Settings);

	/* Create the pPie object */ 
	$PieChart = new pPie($myPicture,$MyData);
	
		/* Draw a splitted pie chart */ 
	$PieChart->draw3DPie(105,105,array("WriteValues"=>PIE_VALUE_NATURAL,"Radius"=>100,"DataGapAngle"=>12,"DataGapRadius"=>10,"Border"=>TRUE));

	/* Write the legend box */ 
	$myPicture->setFontProperties(array("FontName"=>"lib/pChart/fonts/Forgotte.ttf","FontSize"=>15,"R"=>255,"G"=>255,"B"=>255));
	$PieChart->drawPieLegend(210,25,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_VERTICAL));

	// add some text
	$TextSettings = array("R"=>255,"G"=>255,"B"=>255);
	$myPicture->drawText(5,20,"Superficie du Pays (km²) :",$TextSettings);
	
	/* Render the picture (choose the best way) */

	$myPicture->Render("piesuper.png");

	return "<p align='center'><img  src='piesuper.png' alt='TDC' width='35%' height='35%'/><p>";
}





<?php
include( "/lib/sparqllib.php" );

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
		foreach( $fields as $field )
		{
			$tab[$i] = $row[$field];
			$i++;
		}
		
		
	}
	
	
	
	return $tab;
}
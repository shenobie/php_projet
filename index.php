<?php
require_once( "sparqllib.php" );
 
$db = sparql_connect( "http://dbpedia.org/sparql" );
if( !$db ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }

 
$sparql = "PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX yago: <http://dbpedia.org/class/yago/>
PREFIX dbpedia-owl: <http://dbpedia.org/ontology/>
 
SELECT ?place WHERE {
    ?place rdf:type yago:EuropeanCountries
}";

$result = sparql_query( $sparql );  

if( !$result ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; } 
 
$fields = sparql_field_array( $result );
 
print "<p>Number of rows: ".sparql_num_rows( $result )." results.</p>";
echo "<select name='pays'>";

while( $row = sparql_fetch_array( $result ) )
{
	foreach( $fields as $field )
	{
		print "<option>$row[$field]";
	}
}
echo "</select>";
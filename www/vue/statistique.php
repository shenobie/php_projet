<?php

echo"<div class='vue'>";
	echo"<div class='filtre'>";
		echo'
		<form action="sparql.php?section=statistique" method="post">
			<p>
				Nombre de Pays à comparer:<select name="nbpays">';
					
					for($i=1;$i<6;$i++)
					{
						echo"<option value='".$i."'>".$i."</option>";
					}
			
		echo'</select>
			<br><br></p>
			
			<input type="submit" name="choixnbpays" value="Faire mon choix">
		</form>
		';
	echo"</div><br>";
	
	if(!empty($nombrepays))
	{
		echo'<form action="sparql.php?section=statistique" method="post">';
		echo'<p>Type de graphique:
				<select name="typegraph">
					<option value="population">Population (Chiffre)</option>
					<option value="popdensite">Densité de Population (hab/km²)</option>
				</select></br></br>';
		
		echo'<input type="hidden"  name="nombrepays"  value="'.$nombrepays.'">';
		
		for($i=1;$i<=$nombrepays;$i++)
		{
			echo"
				Pays:
				<select name='nompays$i'>";
					for($a=0;$a<count($listepays);$a++)
					{
						echo"<option value='$listepays[$a]'>$listepays[$a]</option>";
					}
				echo"</select>
			</p>
			";	
		}
			echo'<br>
				<input type="submit" name="choixpays" value="Grapher !">
			</form>';
	}
	if(isset($graphique))
	{
		echo"<p align='center'>Graphique généré avec la librairie Pchart si dessous<p>";
		echo"$graphique";
		echo"<p align='center'><i>Des pays peuvent ne pas apparaitre dans les graphiques du à la différence des prédicats de ces derniers par rapport à tout les autres.</i><p>";
	}

echo"</div>";
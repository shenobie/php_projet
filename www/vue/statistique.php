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
		
		for($i=1;$i<=$nombrepays;$i++)
		{
			echo"
			<p>Pays:
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

echo"</div>";
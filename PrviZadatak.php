<?php
	//naziv baze podataka
	$db_name = 'radovi';
	//direktorij u koji će se spremati backup
	$dir = "backup/$db_name";
	//ako direktornij ne postoji stvori ga
	if (!is_dir($dir)) {
        if (!mkdir($dir, 0777, true)) {
            die("<p>Can not create directory.</p></body></html>");
        }
    }
	//trenutno vrijeme
	$time = time();
	// spajanje na lokalnu bazu
	$conn = new mysqli("localhost","root","", $db_name);
	// provjera konekcije
	if ($conn -> connect_error) die("<p>Can't connect to db</p>");
	//Dohvati sve tablice iz baze podataka
	$result = $conn->query('SHOW TABLES');
	//ako postoji barem jedna tablica nastavi s backupom
	if ($result->num_rows > 0) {
		echo "<p>Backup of '$db_name'</p>";
		//Dohvati ime svake tablice
		while (list($table) = $result->fetch_array(MYSQLI_NUM)) {
			//Dohvati sve podatke iz tablice
			$sql = "SELECT * FROM $table";
			$result2 = $conn->query($sql);
			//Dohvati sve stupce
			$columns = $result2->fetch_fields();
			//Ako postoje podaci nastavi
			if ($result2->num_rows > 0) {
				//otvari tekstualnu datoteku s mogućnošću pisanja
				if ($fp = fopen ("$dir/{$table}_{$time}.txt", 'w')) {
					//dokle god ima podataka nastavi
					while ($row = $result2->fetch_array(MYSQLI_NUM)) {
						//zapisuj podatke u datoteku
						fwrite($fp, "INSERT INTO $db_name (");
						//za svaki stupac upiši njegovo ime (npr id, naziv_rada itd)
						foreach($columns as $column) {
							fwrite($fp, "$column->name");
							//ako podatak nije zadnji odvoji ih zarezom
							if ($column != end($columns)) {
								fwrite($fp, ", ");
							}
						}
						//upisivanje svake pojedine vrijednosti odvojene zarezom
						fwrite($fp, ")\r\nVALUES (");
						foreach ($row as $value) {
							$value = addslashes($value);
							fwrite ($fp, "'$value'");
							if ($value != end($row)) {
								fwrite($fp, ", ");
							} else {
								fwrite($fp, ")\";");
							}
						}
						fwrite ($fp, "\r\n");
					}
					fclose($fp);
					echo "<p>Table $table saved.</p>";
					//otvaranje datoteke
					if ($fp2 = gzopen("$dir/{$table}_{$time}.sql.gz", 'w9')) {
						//upisivanje podataka iz prethodno napravljene tekstualne datoteke
						gzwrite($fp2, file_get_contents("$dir/{$table}_{$time}.txt"));
						gzclose($fp2);
					} else {
						echo "<p>File $dir/{$table}_{$time}.sql.gz can't be open</p>";
						break;						
					}
				} else {
					echo "<p>File $dir/{$table}_{$time}.txt can't be open</p>";
					break;
				}
			}
		}
	} else {
		echo "<p>Db doesn't have any tables.</p>";
	}
	
?>
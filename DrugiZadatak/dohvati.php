<?php
    session_start();
    // ukoliko ne postoji direktorij uploads prikaži poruku
    if (!is_dir("uploads/")) {
        echo "<p>No files for decrypting.</p>";
        die();
    }
    // dohvati sve datoteke unutar uploads foldera
    $files = array_diff(scandir("uploads/"), array('..', '.'));
    // ako je direktorij prazan ispiši poruku, inaće prikaži linkove za preuzimanje
    if (count($files) === 0) {
        echo "<p>No files for decrypting</p>";
    } else {
        echo "<ul>";
        // za svaku datoteku prikaži poveznicu za skidanje
        foreach ($files as $file) {
            //obriši .txt ekstenziju
            $fileName = substr($file, 0, strlen($file) - 4);

            echo "<li> <a href=\"preuzmi.php?file=$fileName\">$fileName</a></li>";
        }
        echo "</ul>";
    }
?>




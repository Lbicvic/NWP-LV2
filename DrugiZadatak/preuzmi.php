<?php
session_start();
// dohvaćanje imena datoteke
$file = $_GET['file'];
// kljuc za dekripciju gdje je potrebno koristiti isti string kao i prilikom kriptiranja podataka
$decryption_key = md5('kljuc za enkripciju');
//odabir cipher metode
$cipher = "AES-128-CTR";
$options = 0;
// dohvacanje inicijalizacijskog vektora
$decryption_iv = $_SESSION['iv'];
// dohvacanje kriptirane datoteke iz uploads direktorija
$contentEncrypted = file_get_contents("uploads/$file.txt");
// dekriptiranje podataka
$contentDecrypted = base64_decode($contentEncrypted);
$data = openssl_decrypt($contentDecrypted, $cipher, $decryption_key, $options, $decryption_iv);
// putanja datoteke
$file = "uploads/$file";
// zapisi dektipritane podatke
file_put_contents($file, $data);

// ako datoteka postoji postavi headere i preuzmi datoteku te ju izbrisi sa servera (unlink)
if(file_exists($file)) {
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    ob_clean();
    readfile($file);
    unlink($file);

    die();
}

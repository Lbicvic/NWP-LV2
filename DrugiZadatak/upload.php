<?php
session_start();

// dohvaćanje imena uploadane datoteke
$filename = $_FILES['file']['name'];
// echo $filename;
///lokacija za spremanje kriptiranih podataka
$location = "uploads/" . $filename;
// dohvaćanje ekstenzije datoteke
$imageFileType = pathinfo($location, PATHINFO_EXTENSION);
// ekstenzije koje će biti prihvaćene
$valid_extensions = array("pdf","jpeg","png","jpg");
// provjeri ekstenziju, odnosno usporedi ekstenziju datoteke s ekstenzijama koje server prihvaća
if (!in_array(strtolower($imageFileType), $valid_extensions)) {
    echo "<p>Invalid format ($imageFileType).</p>";
    die();
}
// dohvaćanje sadržaja
$content = file_get_contents($_FILES['file']['tmp_name']);
// kljuc za enkripciju
$encryption_key = md5('kljuc za enkripciju');
// odabir cipher metode
$cipher = "AES-128-CTR";
//dohvaćanje dužine inicijalizacijskog vektora
$iv_length = openssl_cipher_iv_length($cipher);
$options = 0;

// Non-NULL inicijalizacijski vektor za enkripciju
//Random dužine 16 byte
$encryption_iv = random_bytes($iv_length);

// Kriptiraj podatke sa openssl
$encrypted = openssl_encrypt($content, $cipher, $encryption_key, $options, $encryption_iv);

//Spremi podatke
$encryptedData = base64_encode($encrypted);
$_SESSION['iv'] = $encryption_iv;
// ime datoteke bez ekstenzije
$fileNameWithoutExt = substr($filename, 0, strpos($filename, "."));
// ako nema direktorija uploads onda ga kreiraj
if (!is_dir("uploads/")) {
    if (!mkdir("uploads/", 0777, true)) {
        die("<p>Can not create directory $dir.</p>");
    }
}
// ime datoteke
$fileNameOnServer = "uploads/{$fileNameWithoutExt}.$imageFileType.txt";
// zapiši kriptirane podatke unutar stvorene datoteke
file_put_contents($fileNameOnServer, $encryptedData);

echo "File uploaded successfully";
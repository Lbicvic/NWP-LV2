<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Document</title>
</head>
<body>
<?php
// pretvaranje xml datoteke u objekt
    $xml = simplexml_load_file("LV2.xml");
    // definiranje izgleda za svaki profil
    $content = "<main>
    <div class='profile-wrapper'>
    <ul> ";
    // za svaki element koji ima oznaku 'record' uzmi pojedine podatke i prikaži ih u html-u
    foreach ($xml->record as $profile) {
        // dohvati sve potrebne podatke iz objekta
        $id = $profile->id;
        $firstName = $profile->ime;
        $lastName = $profile->prezime;
        $email = $profile->email;
        $sex = $profile->spol;
        $image = $profile->slika;
        $bio = $profile->zivotopis;
        // prikaz za svaki pojedini objekt
        $content .=
                "<li>
                     <div class='profile-wrapper__details'>
                        <img src=$image alt='Profile picture' id='profilePicture'>
                        <h4>$firstName $lastName</h4>
                        <div class='profile-wrapper__personal-data'>
                            <p><b>Sex</b>: $sex</p>
                            <p><b>Email</b>: $email</p>
                            <p><b>Biography</b>: $bio</p>
                        </div>
                    </div>
                </li>";
    }
    // na kraju je potrebno zatvoriti oznake
    $content .=
            "</ul>
            </div>
            </main>";
    // ispiši sve podatke (sve profile)
    echo $content;
?>
</body>
</html>
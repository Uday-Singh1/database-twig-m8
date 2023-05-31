<?php
require __DIR__ . '/Twig/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

$servername = "localhost";
$username = "root";
$password = "";
$database = "Twig_database_opdracht";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connectie mislukt: " . $connection->connect_error);
}

$sql = "SELECT userID, voornaam, achternaam, geboortedatum FROM persoon";
$result = $connection->query($sql);

$gebruikers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['leeftijd'] = berekenLeeftijd($row['geboortedatum']);
        $gebruikers[] = $row;
    }
}

function berekenLeeftijd($geboortedatum) {
    $geboortedatum = new DateTime($geboortedatum);
    $huidigeDatum = new DateTime();
    $leeftijd = $huidigeDatum->diff($geboortedatum)->y;
    return $leeftijd;
}


echo $twig->render('gebruiker-inheritence.twig', ['gebruikers' => $gebruikers]);
// echo $template->render(['gebruikers' => $gebruikers]);

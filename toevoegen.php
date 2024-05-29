<?php
// Start de sessie
// Deze gaan we gebruiken om de form values en de errors op te slaan
session_start();

// Controleer of het verzoek via POST is gedaan
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Valideer de ingediende gegevens
    $errors = [];
    $formValues = [
        'voornaam' => $_POST['voornaam'] ?? '',
        'achternaam' => $_POST['achternaam'] ?? '',
        'telefoonnummer' => $_POST['telefoonnummer'] ?? '',
        'email' => $_POST['email'] ?? '',
        'opmerkingen' => $_POST['opmerkingen'] ?? '',
    ];

    // Valideer voornaam
    if (empty($_POST['voornaam'])) {
        $errors['voornaam'] = "Voornaam is verplicht.";
    }

    // Valideer achternaam
    if (empty($_POST['achternaam'])) {
        $errors['achternaam'] = "Achternaam is verplicht.";
    }

    // Valideer e-mailadres
    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Ongeldig e-mailadres.";
    }

    // Valideer telefoonnummer (NL-formaat)
    if (!empty($_POST['telefoonnummer']) && !preg_match("/^(\\+31|0|0031)[1-9][0-9]{8}$/", $_POST['telefoonnummer'])) {
        $errors['telefoonnummer'] = "Ongeldig telefoonnummer. Voer een geldig Nederlands telefoonnummer in.";
    }

    // Als er geen validatiefouten zijn, voeg de persoon toe aan de database
    if (empty($errors)) {
        require_once 'db.php';
        require_once 'classes/Persoon.php';

        // Maak een nieuw Persoon object met de ingediende gegevens
        $persoon = new Persoon(
            null,
            $_POST['voornaam'],
            $_POST['achternaam'],
            $_POST['telefoonnummer'],
            $_POST['email'],
            $_POST['opmerkingen']
        );

        // Voeg de persoon toe aan de database
        $persoon->save($db);

    } else {
        // Sla de fouten en formulier waarden op in sessievariabelen
        $_SESSION['errors'] = $errors;
        $_SESSION['formValues'] = $formValues;
    }

    // Stuur de gebruiker terug naar de index.php
    header("Location: index.php");
    exit;

} else {
    header("Location: index.php");
}
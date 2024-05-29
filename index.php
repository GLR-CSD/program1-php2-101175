<?php
session_start();

// Haal de eventuele fouten en formulier waarden op uit de sessie
$errors = $_SESSION['errors'] ?? [];
$formValues = $_SESSION['formValues'] ?? [];

// Verwijder de sessievariabelen
unset($_SESSION['errors']);
unset($_SESSION['formValues']);

require_once 'db.php';
require_once 'classes/Persoon.php';

// Haal alle personen op uit de database
$personen = Persoon::getAll($db);

// Laad de view
include 'views/index_view.php';
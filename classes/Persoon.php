<?php
// Set strict types
declare(strict_types=1);

class Persoon {
    /** @var int|null Het ID van de persoon */
    private ?int $id;

    /** @var string De voornaam van de persoon */
    private string $voornaam;

    /** @var string De achternaam van de persoon */
    private string $achternaam;

    /** @var string|null Het telefoonnummer van de persoon */
    private ?string $telefoonnummer;

    /** @var string|null Het e-mailadres van de persoon */
    private ?string $email;

    /** @var string|null Eventuele opmerkingen over de persoon */
    private ?string $opmerkingen;

    /**
     * Constructor voor het maken van een Persoon object.
     *
     * @param int|null $id Het ID van de persoon.
     * @param string $voornaam De voornaam van de persoon.
     * @param string $achternaam De achternaam van de persoon.
     * @param string|null $telefoonnummer Het telefoonnummer van de persoon (optioneel).
     * @param string|null $email Het e-mailadres van de persoon (optioneel).
     * @param string|null $opmerkingen Eventuele opmerkingen over de persoon (optioneel).
     */
    public function __construct(?int $id, string $voornaam, string $achternaam, ?string $telefoonnummer,
                                ?string $email, ?string $opmerkingen)

    {
        $this->id = $id;
        $this->voornaam = $voornaam;
        $this->achternaam = $achternaam;
        $this->telefoonnummer = $telefoonnummer;
        $this->email = $email;
        $this->opmerkingen = $opmerkingen;
    }

    /**
     * Haalt alle personen op uit de database.
     *
     * @param PDO $db De PDO-databaseverbinding.
     * @return Persoon[] Een array van Persoon-objecten.
     */
    public static function getAll(PDO $db): array
    {
        // Voorbereiden van de query
        $stmt = $db->query("SELECT * FROM persoon");

        // Array om personen op te slaan
        $personen = [];

        // Itereren over de resultaten en personen toevoegen aan de array
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $persoon = new Persoon(
                $row['id'],
                $row['voornaam'],
                $row['achternaam'],
                $row['telefoonnummer'],
                $row['email'],
                $row['opmerkingen']
            );
            $personen[] = $persoon;
        }

        // Retourneer array met personen
        return $personen;
    }

    /**
     * Zoek personen op basis van id.
     *
     * @param PDO $db De PDO-databaseverbinding.
     * @param int $id Het unieke ID van een persoon waarnaar we zoeken.
     * @return Persoon|null Het gevonden Persoon-object of null als er geen overeenkomstige persoon werd gevonden.
     * */
    public static function findById(PDO $db, int $id): ?Persoon
    {
        // Voorbereiden van de query
        $stmt = $db->prepare("SELECT * FROM persoon WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Retourneer een persoon als gevonden, anders null
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Persoon(
                $row['id'],
                $row['voornaam'],
                $row['achternaam'],
                $row['telefoonnummer'],
                $row['email'],
                $row['opmerkingen']
            );
        } else {
            return null;
        }
    }

    /**
     * Zoek personen op basis van achternaam.
     *
     * @param PDO $db De PDO-databaseverbinding.
     * @param string $achternaam De achternaam om op te zoeken.
     * @return array Een array van Persoon objecten die aan de zoekcriteria voldoen.
     */
    public static function findByAchternaam(PDO $db, string $achternaam): array
    {
        //Zet de achternaam eerst om naar lowercase letters
        $achternaam = strtolower($achternaam);

        // Voorbereiden van de query
        $stmt = $db->prepare("SELECT * FROM persoon WHERE LOWER(achternaam) LIKE :achternaam");

        // Voeg wildcard toe aan de achternaam
        $achternaam = "%$achternaam%";

        // Bind de achternaam aan de query en voer deze uit
        $stmt->bindParam(':achternaam', $achternaam);
        $stmt->execute();

        // Array om personen op te slaan
        $personen = [];

        // Itereren over de resultaten en personen toevoegen aan de array
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $personen[] = new Persoon(
                $row['id'],
                $row['voornaam'],
                $row['achternaam'],
                $row['telefoonnummer'],
                $row['email'],
                $row['opmerkingen']
            );
        }

        // Retourneer array met personen
        return $personen;
    }

    // Methode om een nieuwe persoon toe te voegen aan de database
    public function save(PDO $db): void
    {
        // Voorbereiden van de query
        $stmt = $db->prepare("INSERT INTO persoon (voornaam, achternaam, telefoonnummer, email, opmerkingen) VALUES (:voornaam, :achternaam, :telefoonnummer, :email, :opmerkingen)");
        $stmt->bindParam(':voornaam', $this->voornaam);
        $stmt->bindParam(':achternaam', $this->achternaam);
        $stmt->bindParam(':telefoonnummer', $this->telefoonnummer);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':opmerkingen', $this->opmerkingen);
        $stmt->execute();
    }

    // Methode om een bestaande persoon bij te werken op basis van ID
    public function update(PDO $db): void
    {
        // Voorbereiden van de query
        $stmt = $db->prepare("UPDATE persoon SET voornaam = :voornaam, achternaam = :achternaam, telefoonnummer = :telefoonnummer, email = :email, opmerkingen = :opmerkingen WHERE id = :id");
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':voornaam', $this->voornaam);
        $stmt->bindParam(':achternaam', $this->achternaam);
        $stmt->bindParam(':telefoonnummer', $this->telefoonnummer);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':opmerkingen', $this->opmerkingen);
        $stmt->execute();
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVoornaam(): string
    {
        return $this->voornaam;
    }

    public function getAchternaam(): string
    {
        return $this->achternaam;
    }

    public function getTelefoonnummer(): ?string
    {
        return $this->telefoonnummer;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getOpmerkingen(): ?string
    {
        return $this->opmerkingen;
    }

    // Setters
    public function setVoornaam(string $voornaam): void
    {
        $this->voornaam = $voornaam;
    }

    public function setAchternaam(string $achternaam): void
    {
        $this->achternaam = $achternaam;
    }

    public function setTelefoonnummer(string $telefoonnummer): void
    {
        $this->telefoonnummer = $telefoonnummer;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setOpmerkingen(string $opmerkingen): void
    {
        $this->opmerkingen = $opmerkingen;
    }
}

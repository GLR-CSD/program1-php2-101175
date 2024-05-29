<?php

class Album
{
    /** @var int|null Het ID van de persoon */
    private ?int $id;

    /** @var string De naam van het album */
    private string $naam;

    /** @var string De naam van de artiest */
    private string $artiesten;

    /** @var string|null De release datum van het album */
    private ?string $releasedatum;

    /** @var string|null De url naar het album op Spotify of youtube */
    private ?string $url;

    /** @var string|null De afbeelding van het album */
    private ?string $afbeelding;

    /** @var string|null De prijs waar het album voor beschikbaar is */
    private ?string $prijs;

    /**
     * Constructor voor het maken van een Persoon object.
     *
     * @param int|null $id Het ID van de persoon.
     * @param string $naam De naam van het album.
     * @param string $artiesten De naam van de artiest.
     * @param string|null $releasedatum De release datum van het album.
     * @param string|null $url De url naar het album op Spotify of youtube.
     * @param string|null $afbeelding De afbeelding van het album.
     * * @param string|null $prijs De prijs waar het album voor beschikbaar is.
     */

    /**
     * @return int|null
     */
    public function __construct(?int $id, string $naam, string $artiesten, ?string $releasedatum,
                                ?string $url, ?string $afbeelding, ?string $prijs)
    {
        $this->id = $id;
        $this->naam = $naam;
        $this->artiesten = $artiesten;
        $this->releasedatum = $releasedatum;
        $this->url = $url;
        $this->afbeelding = $afbeelding;
        $this->prijs = $prijs;
    }


public function getAll(PDO $db): array
{
    // Voorbereiden van de query
    $stmt = $db->query("SELECT * FROM album");

    // Array om personen op te slaan
    $albums = [];

    // Itereren over de resultaten en personen toevoegen aan de array
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $persoon = new Persoon(
            $row['id'],
            $row['naam'],
            $row['artiesten'],
            $row['releasedatum'],
            $row['url'],
            $row['afbeelding'],
            $row['prijs']
        );
        $albums[] = $persoon;
    }

    // Retourneer array met personen
    return $albums;}

    public function findById(PDO $db, int $id): ?Persoon
    {
        // Voorbereiden van de query
        $stmt = $db->prepare("SELECT * FROM album WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Retourneer een persoon als gevonden, anders null
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Persoon(
                $row['id'],
                $row['naam'],
                $row['artiesten'],
                $row['releasedatum'],
                $row['url'],
                $row['afbeelding'],
                $row['prijs']
            );
        } else {
            return null;}}

        public function findByArtiesten(PDO $db, string $artiesten): array
    {
        //Zet de achternaam eerst om naar lowercase letters
        $artiesten = strtolower($artiesten);

        // Voorbereiden van de query
        $stmt = $db->prepare("SELECT * FROM album WHERE LOWER(Artiesten) LIKE :artiesten");

        // Voeg wildcard toe aan de achternaam
        $artiesten = "%$artiesten%";

        // Bind de achternaam aan de query en voer deze uit
        $stmt->bindParam(':artiesten', $artiesten);
        $stmt->execute();

        // Array om personen op te slaan
        $album = [];

        // Itereren over de resultaten en personen toevoegen aan de array
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $album[] = new Persoon(
                $row['id'],
                $row['naam'],
                $row['artiesten'],
                $row['releasedatum'],
                $row['url'],
                $row['afbeelding'],
                $row['prijs']
            );
        }

        // Retourneer array met personen
        return $album;}

public function save(PDO $db): void
{
    // Voorbereiden van de query
    $stmt = $db->prepare("INSERT INTO album (naam, artiesten, releasedatum, url, afbeelding, prijs) VALUES (:naam, :artiesten, :releasedatum, :url, :afbeelding, :prijs)");
    $stmt->bindParam(':naam', $this->naam);
    $stmt->bindParam(':artiesten', $this->artiesten);
    $stmt->bindParam(':releasedatum', $this->releasedatum);
    $stmt->bindParam(':url', $this->url);
    $stmt->bindParam(':afbeelding', $this->afbeelding);
    $stmt->bindParam(':prijs', $this->prijs);
    $stmt->execute();
}

// Methode om een bestaande persoon bij te werken op basis van ID
public function update(PDO $db): void
{
    // Voorbereiden van de query
    $stmt = $db->prepare("UPDATE album SET naam = :naam, artiesten = :artiesten, releasedatum = :releasedatum, url = :url, afbeelding = :afbeelding, prijs = :prijs WHERE id = :id");
    $stmt->bindParam(':id', $this->id);
    $stmt->bindParam(':naam', $this->naam);
    $stmt->bindParam(':artiesten', $this->artiesten);
    $stmt->bindParam(':releasedatum', $this->releasedatum);
    $stmt->bindParam(':url', $this->url);
    $stmt->bindParam(':afbeelding', $this->afbeelding);
    $stmt->bindParam(':prijs', $this->prijs);
    $stmt->execute();
}

// Getters
public function getId(): ?int
{
    return $this->id;
}

public function getNaam(): string
{
    return $this->naam;
}

public function getArtiesten(): string
{
    return $this->artiesten;
}

public function getReleasedatum(): ?string
{
    return $this->releasedatum;
}

public function getUrl(): ?string
{
    return $this->url;
}

public function getAfbeelding(): ?string
{
    return $this->afbeelding;
}

    public function getPrijs(): ?string
    {
        return $this->prijs;
    }

// Setters
public function setVoornaam(string $naam): void
{
    $this->voornaam = $naam;
}

public function setAchternaam(string $artiesten): void
{
    $this->achternaam = $artiesten;
}

public function setReleasedatum (string $releasedatum): void
{
    $this->releasedatum = $releasedatum;
}

public function setEmail(string $url): void
{
    $this->url = $url;
}

public function setAfbeelding(string $afbeelding): void
{
    $this->afbeelding = $afbeelding;
}

    public function setPrijs(string $prijs): void
    {
        $this->prijs = $prijs;
    }
}
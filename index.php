<?php
class AttackPokemon {
    public $attackMinimal;
    public $attackMaximal;
    public $specialAttack;
    public $probabilitySpecialAttack;

    public function __construct($attackMinimal, $attackMaximal, $specialAttack, $probabilitySpecialAttack) {
        $this->attackMinimal = $attackMinimal;
        $this->attackMaximal = $attackMaximal;
        $this->specialAttack = $specialAttack;
        $this->probabilitySpecialAttack = $probabilitySpecialAttack;
    }
}

class Pokemon {
    public $name;
    public $url;
    public $hp;
    public $attackPokemon;

    public function __construct($name, $url, $hp, $attackPokemon) {
        $this->name = $name;
        $this->url = $url;
        $this->hp = $hp;
        $this->attackPokemon = $attackPokemon;
    }

    public function isDead() {
        return $this->hp <= 0;
    }

    public function attack($targetPokemon) {
        // Attack logic with a chance of special attack
        $attackPoints = rand($this->attackPokemon->attackMinimal, $this->attackPokemon->attackMaximal);
        if (rand(0, 100) <= $this->attackPokemon->probabilitySpecialAttack) {
            $attackPoints *= $this->attackPokemon->specialAttack;
        }
        $targetPokemon->hp -= $attackPoints;
        return $attackPoints;
    }

    public function whoAmI() {
        return [
            'name' => $this->name,
            'url' => $this->url,
            'hp' => $this->hp,
            'specialAttack' => $this->attackPokemon->specialAttack,
            'attackMinimal' => $this->attackPokemon->attackMinimal,
            'attackMaximal' => $this->attackPokemon->attackMaximal
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulation Combat Pokémon</title>
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        img {
            width: 50px;
        }
    </style>
</head>
<body>
    <h1>Simulation de Combat entre Pokémon</h1>
    <?php
// Initialisation des Pokémon avec des valeurs d'exemple
$attackPikachu = new AttackPokemon(10, 20, 2, 50);
$attackBulbasaur = new AttackPokemon(5, 15, 1.5, 40);

$pikachu = new Pokemon("Pikachu", "https://example.com/pikachu.png", 100, $attackPikachu);
$bulbasaur = new Pokemon("Bulbasaur", "https://example.com/bulbasaur.png", 100, $attackBulbasaur);

// Simulation du combat
$round = 1;
while (!$pikachu->isDead() && !$bulbasaur->isDead()) {
    // Chaque Pokémon attaque
    $attackPikachu = $pikachu->attack($bulbasaur);
    $attackBulbasaur = $bulbasaur->attack($pikachu);

    // Affichage des informations de chaque Pokémon après chaque round
    echo "<h2>Round $round</h2>";
    echo "<div class='row'>
                <div class='col-md-6'>
                    <table class='table table-bordered table-striped table-hover'>
                        <thead class='table-dark'>
                            <tr><th colspan='2' class='text-center'>Pikachu</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Image</td><td><img src='{$pikaInfo['url']}' alt='{$pikaInfo['name']}' width='50'></td></tr>
                            <tr><td>HP Restants</td><td>{$pikaInfo['hp']}</td></tr>
                            <tr><td>Attack Minimal</td><td>{$pikaInfo['attackMinimal']}</td></tr>
                            <tr><td>Attack Maximal</td><td>{$pikaInfo['attackMaximal']}</td></tr>
                            <tr><td>Special Attack</td><td>{$pikaInfo['specialAttack']}</td></tr>
                        </tbody>
                    </table>
                </div>";

    // Affichage de Pikachu
    $pikaInfo = $pikachu->whoAmI();
    echo "<tr>
            <td>{$pikaInfo['name']}</td>
            <td><img src='{$pikaInfo['url']}' alt='{$pikaInfo['name']}' width='50'></td>
            <td>{$pikaInfo['hp']}</td>
            <td>{$pikaInfo['attackMinimal']}</td>
            <td>{$pikaInfo['attackMaximal']}</td>
            <td>{$pikaInfo['specialAttack']}</td>
          </tr>";

    // Affichage de Bulbasaur
    $bulbaInfo = $bulbasaur->whoAmI();
    echo "<div class='col-md-6'>
                    <table class='table table-bordered table-striped table-hover'>
                        <thead class='table-dark'>
                            <tr><th colspan='2' class='text-center'>Bulbasaur</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Image</td><td><img src='{$bulbaInfo['url']}' alt='{$bulbaInfo['name']}' width='50'></td></tr>
                            <tr><td>HP Restants</td><td>{$bulbaInfo['hp']}</td></tr>
                            <tr><td>Attack Minimal</td><td>{$bulbaInfo['attackMinimal']}</td></tr>
                            <tr><td>Attack Maximal</td><td>{$bulbaInfo['attackMaximal']}</td></tr>
                            <tr><td>Special Attack</td><td>{$bulbaInfo['specialAttack']}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>";


    echo "</table>";

    // Incrémenter le round
    $round++;
}
?>


</body>
</html>

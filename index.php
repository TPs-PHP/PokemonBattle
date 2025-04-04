<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulation Combat Pokémon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h1 class="text-center mb-4">3arket el pokemonet</h1>

    <?php
    // If the data is passed via the URL parameters, we collect them here
    if (isset($_GET['name1'])) {
        $name1 = $_GET['name1'];
        $hp1 = $_GET['hp1'];
        $attackMin1 = $_GET['attackMin1'];
        $attackMax1 = $_GET['attackMax1'];
        $specialAttack1 = $_GET['specialAttack1'];
        $probability1 = $_GET['probability1'];
        $type1 = $_GET['type1']; // Added type for the first Pokémon

        $name2 = $_GET['name2'];
        $hp2 = $_GET['hp2'];
        $attackMin2 = $_GET['attackMin2'];
        $attackMax2 = $_GET['attackMax2'];
        $specialAttack2 = $_GET['specialAttack2'];
        $probability2 = $_GET['probability2'];
        $type2 = $_GET['type2']; // Added type for the second Pokémon
    
        // Combat simulation logic
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
            public $type;
    
            public function __construct($name, $url, $hp, $attackPokemon, $type) {
                $this->name = $name;
                $this->url = $url;
                $this->hp = $hp;
                $this->attackPokemon = $attackPokemon;
                $this->type = $type;
            }
    
            public function isDead() {
                return $this->hp <= 0;
            }
    
            public function attack($targetPokemon) {
                $attackPoints = rand($this->attackPokemon->attackMinimal, $this->attackPokemon->attackMaximal);
                
                // Type advantage logic
                $damageMultiplier = $this->getTypeMultiplier($targetPokemon);
                
                // Apply special attack if applicable
                if (rand(0, 100) <= $this->attackPokemon->probabilitySpecialAttack) {
                    $attackPoints *= $this->attackPokemon->specialAttack;
                }
                
                // Apply type multiplier
                $attackPoints *= $damageMultiplier;
                
                $targetPokemon->hp -= $attackPoints;
                $targetPokemon->hp = max(0, $targetPokemon->hp);
                return $attackPoints;
            }
    
            public function whoAmI() {
                return [
                    'name' => $this->name,
                    'url' => $this->url,
                    'hp' => $this->hp,
                    'type'=>$this->type,
                    'specialAttack' => $this->attackPokemon->specialAttack,
                    'attackMinimal' => $this->attackPokemon->attackMinimal,
                    'attackMaximal' => $this->attackPokemon->attackMaximal
                ];
            }

            // Function to calculate type multiplier based on Pokémon type
            public function getTypeMultiplier($targetPokemon) {
                if ($this->type == 'Feu') {
                    if ($targetPokemon->type == 'Plante') {
                        return 2; // Fire is strong against Plant
                    } elseif ($targetPokemon->type == 'Eau') {
                        return 0.5; // Fire is weak against Water
                    } elseif ($targetPokemon->type == 'Feu') {
                        return 0.5; // Fire is weak against Fire
                    }
                } elseif ($this->type == 'Eau') {
                    if ($targetPokemon->type == 'Feu') {
                        return 2; // Water is strong against Fire
                    } elseif ($targetPokemon->type == 'Plante') {
                        return 0.5; // Water is weak against Plant
                    } elseif ($targetPokemon->type == 'Eau') {
                        return 0.5; // Water is weak against Water
                    }
                } elseif ($this->type == 'Plante') {
                    if ($targetPokemon->type == 'Eau') {
                        return 2; // Plant is strong against Water
                    } elseif ($targetPokemon->type == 'Feu') {
                        return 0.5; // Plant is weak against Fire
                    } elseif ($targetPokemon->type == 'Plante') {
                        return 0.5; // Plant is weak against Plant
                    }
                }
                return 1 ; 
            }
        }
    
        // Initialisation des Pokémon
        $attackPokemon1 = new AttackPokemon($attackMin1, $attackMax1, $specialAttack1, $probability1);
        $attackPokemon2 = new AttackPokemon($attackMin2, $attackMax2, $specialAttack2, $probability2);
    
        $m = array(
            "Feu" => "https://miro.medium.com/v2/resize:fit:640/format:webp/1*Hxptm5gIRc3HyYXzw5nfpw.png", 
            "Plante"=> "https://archives.bulbagarden.net/media/upload/f/fb/0001Bulbasaur.png",
            "Eau" => "https://www.pokemon.com/static-assets/content-assets/cms2/img/pokedex/full/458.png"   
        );
        $pokemon1 = new Pokemon($name1, $m[$type1], $hp1, $attackPokemon1, $type1);
        $pokemon2 = new Pokemon($name2, $m[$type2], $hp2, $attackPokemon2, $type2);
    
        // Simulation du combat
        $round = 1;
        while (!$pokemon1->isDead() && !$pokemon2->isDead()) {
            $pokemon1->attack($pokemon2);
            $pokemon2->attack($pokemon1) ;
    
            // Display the current round
            echo "<h2 class='mt-4 text-muted'>rounda $round</h2>";
    
            // Display Pokémon info
            $pokemon1Info = $pokemon1->whoAmI();
            echo "<div class='row'>
                    <div class='col-md-6'>
                        <table class='table table-bordered table-striped table-hover'>
                            <thead class='table-primary'>
                                <tr><th colspan='2' class='text-center'>{$pokemon1Info['name']}</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>Image</td><td><img src='{$pokemon1Info['url']}' alt='{$pokemon1Info['name']}' width='50'></td></tr>
                                <tr><td>HP Restants</td><td>{$pokemon1Info['hp']}</td></tr>
                                <tr><td>Attack Minimal</td><td>{$pokemon1Info['attackMinimal']}</td></tr>
                                <tr><td>Attack Maximal</td><td>{$pokemon1Info['attackMaximal']}</td></tr>
                                <tr><td>Special Attack</td><td>{$pokemon1Info['specialAttack']}</td></tr>
                                <tr><td>Type</td><td>{$pokemon1Info['type']}</td></tr>
                            </tbody>
                        </table>
                    </div>";
    
            $pokemon2Info = $pokemon2->whoAmI();
            echo "<div class='col-md-6'>
                        <table class='table table-bordered table-striped table-hover'>
                            <thead class='table-danger'>
                                <tr><th colspan='2' class='text-center'>{$pokemon2Info['name']}</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>Image</td><td><img src='{$pokemon2Info['url']}' alt='{$pokemon2Info['name']}' width='50'></td></tr>
                                <tr><td>HP Restants</td><td>{$pokemon2Info['hp']}</td></tr>
                                <tr><td>Attack Minimal</td><td>{$pokemon2Info['attackMinimal']}</td></tr>
                                <tr><td>Attack Maximal</td><td>{$pokemon2Info['attackMaximal']}</td></tr>
                                <tr><td>Special Attack</td><td>{$pokemon2Info['specialAttack']}</td></tr>
                                <tr><td>Type</td><td>{$pokemon2Info['type']}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>";
    
            $round++;
        }
    
        // Display result of the battle
        if ($pokemon1->isDead() && $pokemon2->isDead()) {
            echo "<div class='alert alert-warning mt-4' role='alert'>match nul : les deux pokemons sont KO!</div>";
        } elseif ($pokemon1->isDead()) {
            echo "<div class='alert alert-success mt-4' role='alert'> {$pokemon2->name} est le gagnant! (kessa7 frr) </div>";
        } else {
            echo "<div class='alert alert-success mt-4' role='alert'> {$pokemon1->name} remporte la victoire ! (mabrouk laaziz)</div>";
        }
    } else {
        echo "
        <script>
            let name1 = prompt('Enter the name of the first Pokémon:');
            let hp1 = prompt('Enter the HP of the first Pokémon:');
            let attackMin1 = prompt('Enter the minimum attack value of the first Pokémon:');
            let attackMax1 = prompt('Enter the maximum attack value of the first Pokémon:');
            let specialAttack1 = prompt('Enter the special attack multiplier of the first Pokémon:');
            let probability1 = prompt('Enter the special attack probability (0-100) of the first Pokémon:');
            let type1 = prompt('Enter the type of the first Pokémon (Feu, Eau, Plante):');
    
            let name2 = prompt('Enter the name of the second Pokémon:');
            let hp2 = prompt('Enter the HP of the second Pokémon:');
            let attackMin2 = prompt('Enter the minimum attack value of the second Pokémon:');
            let attackMax2 = prompt('Enter the maximum attack value of the second Pokémon:');
            let specialAttack2 = prompt('Enter the special attack multiplier of the second Pokémon:');
            let probability2 = prompt('Enter the special attack probability (0-100) of the second Pokémon:');
            let type2 = prompt('Enter the type of the second Pokémon (Feu, Eau, Plante):');
    
            window.location.href = 'index.php?name1=' + name1 +
                '&hp1=' + hp1 +
                '&attackMin1=' + attackMin1 +
                '&attackMax1=' + attackMax1 +
                '&specialAttack1=' + specialAttack1 +
                '&probability1=' + probability1 +
                '&type1=' + type1 +
                '&name2=' + name2 +
                '&hp2=' + hp2 +
                '&attackMin2=' + attackMin2 +
                '&attackMax2=' + attackMax2 +
                '&specialAttack2=' + specialAttack2 +
                '&probability2=' + probability2 +
                '&type2=' + type2;
        </script>";
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

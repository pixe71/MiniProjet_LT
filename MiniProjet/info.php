<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations Système</title>
    <style>
/* General body and container styling */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    min-height: 100vh;
}

.container {
    width: 80%;
    max-width: 960px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
}

/* Navigation bar styling */
nav {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px 0;
    width: 100%;
    margin-bottom: 20px;
}

nav a {
    color: white;
    text-decoration: none;
    padding: 10px 20px;
}

/* Section headers styling */
.partie {
    font-size: 18px;
    margin-top: 20px;
    margin-bottom: 10px;
}

/* Information blocks styling */
.info {
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 10px;
}


    </style>
</head>
<body>
    <div class="container"> <!-- Ajout d'un conteneur principal -->
    <?php
    // Inclusion du fichier de configuration
    require_once 'db.php';

    echo "<div class='partie'>Partie 1</div>";

    function detecter_navigateur() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $navigateur = "Inconnu";

        // Prioriser les correspondances plus spécifiques
        if (strpos($user_agent, 'Edg') !== false) {
            $navigateur = 'Edge';
        } elseif (strpos($user_agent, 'Chrome') !== false && strpos($user_agent, 'Edg') === false) {
            $navigateur = 'Chrome'; // Chrome, éviter de confondre avec Edge
        } elseif (strpos($user_agent, 'Safari') !== false && strpos($user_agent, 'Chrome') === false) {
            $navigateur = 'Safari'; // Safari, éviter de confondre avec Chrome
        } elseif (strpos($user_agent, 'Firefox') !== false) {
            $navigateur = 'Firefox';
        } elseif (strpos($user_agent, 'Opera') !== false || strpos($user_agent, 'OPR') !== false) {
            $navigateur = 'Opera';  // Opera (OPR est utilisé dans l'User-Agent d'Opera)
        } elseif (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Trident') !== false) {
            $navigateur = 'Internet Explorer'; // Internet Explorer (ancienne version)
        }
        return htmlspecialchars($navigateur, ENT_QUOTES, 'UTF-8'); // Protéger la sortie
    }
    echo "<div class='info'>Navigateur : " . detecter_navigateur() . "</div>";
    echo "<br>";

    /**
     * Fonction pour récupérer l'adresse IP
     */
    function obtenir_ip() {
        $ip_local = gethostbyname(gethostname());
        $ip_publique = @file_get_contents('https://api.ipify.org'); // Use @ to suppress warnings

        if ($ip_publique === FALSE) {
          return "Impossible de récupérer l'adresse IP publique.";
        }
        return "Adresse IP locale du serveur : " . $ip_local . "<br>Adresse IP publique du serveur : " . $ip_publique;

    }

    $adresse_ip = obtenir_ip(); // Call the function and store the result
    echo "<div class='info'> " . $adresse_ip . "</div>"; // Display the result
    echo "<br>";

    // Fonction pour détecter le système d'exploitation
    function detecter_os() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $os_platform  = "Inconnu";
        $os_array     = array(
            '/windows nt 11/i'      =>  'Windows 11',
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'      =>  'Windows 8.1',
            '/windows nt 6.2/i'      =>  'Windows 8',
            '/windows nt 6.1/i'      =>  'Windows 7',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'         =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/webos/i'              =>  'Mobile'
        );
        foreach ($os_array as $regex => $valeur) {
            if (preg_match($regex, $user_agent)) {
                $os_platform = $valeur;
                break; // Sortir de la boucle dès qu'une correspondance est trouvée
            }
        }
        return htmlspecialchars($os_platform, ENT_QUOTES, 'UTF-8'); // Protéger la sortie
    }
    echo "<div class='info'>Système d'exploitation : " . detecter_os() . "</div>";
    echo "<br>";
    ?>

    <div class='partie'>Partie 2</div>
    <?php
    echo "<div class='info'>Agent utilisateur : " . htmlspecialchars($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8') . "</div>\n\n";

    try {
       // $navigateur = get_browser(null, true); // Nécessite une configuration correcte de php.ini
       // echo "<pre>";
       // print_r($navigateur);
       // echo "</pre>";
       // echo "<br>";
       // echo "Nom du navigateur : " . $navigateur->browser;
       // echo "<br>";
    } catch (Exception $e) {
        echo "get_browser() n'est pas correctement configurée sur ce serveur.";
    }
    ?>

    <div class='partie'>Partie 3</div>
    <?php
    echo "<div class='info'>php_uname() : " . php_uname() . "</div>";
    echo "<br>";
    echo "<div class='info'>PHP_OS : " . PHP_OS . "</div>";
    echo "<br>";
    ?>

    <div class='partie'>Partie 4</div>
    <?php
    echo "<div class='info'>S : " . php_uname("s") . "</div>";
    echo "<br>";
    echo "<div class='info'>N : " . php_uname("n") . "</div>";
    echo "<br>";
    echo "<div class='info'>R : " . php_uname("r") . "</div>";
    echo "<br>";
    echo "<div class='info'>V : " . php_uname("v") . "</div>";
    echo "<br>";
    echo "<div class='info'>M : " . php_uname("m") . "</div>";
    echo "<br>";
    ?>

    <div class='partie'>Partie 5</div>
    <?php
    $dir = "/";
    echo "<div class='info'>Espace libre : " . disk_free_space($dir) . "</div>";
    echo "<br>";
    echo "<div class='info'>Espace total : " . disk_total_space($dir) . "</div>";
    echo "<br>";
    ?>
    </div> <!-- Fermeture du conteneur principal -->
</body>
</html>
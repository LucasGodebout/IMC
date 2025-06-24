<?php
session_start();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcul IMC</title>
    <link rel="icon" href="https://getbootstrap.com/docs/4.0/assets/img/favicons/favicon.ico">


    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/album/">

    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="album.css" rel="stylesheet">
    <style>
body{
    font-family: "roboto";
    background-color: #BDEDE0;
    font-size: 24px;
    margin: 0;
    min-height: 100vh;
}
nav{
    background-color: #6F58C9;
    height:42px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #B6B8D6;
}
#menu{
    width: 70%;
    font-size: 30px;
    display: flex;
    justify-content: center;
    gap: 10%;
    margin-right: 48px;
}
p{
    padding: 0 25%;
    text-align: center;
}
#box{
    width: 65%;
    display: flex;
    border: solid #7E78D2;
    border-radius: 10px;
}
#form{
    background-color:#7E78D2;
    width: 100%;
    display: flex;
    flex-direction: column;
    padding: 2%
}
#form p{
    text-align: left;
    padding: 0;
    margin: 20px 0;
    font-size: 30px;
}
#form input{
    background-color: white;
    width: 90%;
    font-size: 24px;
    color: black;
}
#connexion{
    margin-bottom: 152px;
}
#form button{
    color: #6F58C9;
    font-size: 24px;
    margin-top: 20px;
}
#connexion{
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}
form img{
    max-width: 29px;
}
#historique{
    background-color: white;
    padding: 20px;
    border-radius: 10px;
}
    </style>
</head>
<body>
<nav>
    <h1>Lucas Godebout</h1>
</nav>
<main>
    <div class="content" id="start">
        <p>Calculez votre IMC</p>
    </div>
    <div id="connexion">
    <?php
            // Vérification de l'existance du fichier
            if (!file_exists('imc.txt')) {
                $fichier = fopen('imc.txt', 'a');
                fclose($fichier);
            }

            // Vérification de la soumission du formulaire
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                //Récupération des données
                $taille = $_REQUEST['taille'];
                $poids = $_REQUEST['poids'];
                $errors = 0;

                //Vérification des données

                if($taille <= 0 || empty($taille) || !is_numeric($taille)){
                    echo '<div class="alert alert-danger" role="alert">La taille doit être supérieure à 0 !</div>';
                    $errors++;
                }

                if($poids <=0 || empty($poids) || !is_numeric($poids)){
                    echo '<div class="alert alert-danger" role="alert">Le poids doit être supérieur à 0 !</div>';
                    $errors++;
                }
        

            //Calcul IMC
            if($errors === 0){
                $imc = $poids / (($taille / 100) * ($taille / 100));
                echo '<div class="alert alert-success" role="alert">Votre IMC est de : '.round($imc, 1).'</div>';
                $fichier = fopen('imc.txt', 'a');
                if($imc < 16.5)
                    fwrite($fichier, 'Votre IMC est de : '.round($imc, 1).' : Famine '.date('d/m/Y H:i:s').PHP_EOL);
                else if($imc >= 16.5 && $imc < 18.5)
                    fwrite($fichier, 'Votre IMC est de : '.round($imc, 1).' : Maigreur '.date('d/m/Y H:i:s').PHP_EOL);
                else if($imc >= 18.5 && $imc < 25)
                    fwrite($fichier, 'Votre IMC est de : '.round($imc, 1).' : Corpulence normale '.date('d/m/Y H:i:s').PHP_EOL);
                else if($imc >= 25 && $imc < 30)
                    fwrite($fichier, 'Votre IMC est de : '.round($imc, 1).' : Surpoids '.date('d/m/Y H:i:s').PHP_EOL);
                else if($imc >= 30 && $imc < 35)
                    fwrite($fichier, 'Votre IMC est de : '.round($imc, 1).' : Obésité modérée '.date('d/m/Y H:i:s').PHP_EOL);
                else if($imc >= 35 && $imc < 40)
                    fwrite($fichier, 'Votre IMC est de : '.round($imc, 1).' : Obésité sévère '.date('d/m/Y H:i:s').PHP_EOL);
                else
                    fwrite($fichier, 'Votre IMC est de : '.round($imc, 1).' : Obésité massive '.date('d/m/Y H:i:s').PHP_EOL);
                fclose($fichier);
                $result = file_get_contents('imc.txt');
                $list = explode("\n", $result);
                unset($_SESSION['taille']);
                unset($_SESSION['poids']);
            }

        }

            ?>
        <div id="box">
            <div id="form">
                <form action="lucas_godebout.php" method="post">
                    <p>Votre taille (en cm)</p>
                    <div class="taille">
                        <input class="" type="number" name="taille" placeholder="Entrer votre taille...">
                   </div>
                    <p>Votre poids (en kg)</p>
                    <div class="poids">
                        <input class="" type="number" name="poids" placeholder="Entrer votre poids...">
                    </div>
                    <div id="button-submit">
                        <button type="submit">Envoyer</button>
                        <a href="lucas_godebout.php?delete=1"><button id="delete" type="button">Supprimer</button></a>
                        <?php
                        if(isset($_GET['delete']) && $_GET['delete'] == 1){
                            unlink('imc.txt');
                        }
                        ?>
                    </div>
                </form>
                <div id="historique">
                    <p>Historique des IMC :</p>
                <?php 
                if(file_exists('imc.txt')){
                    $result = file_get_contents('imc.txt');
                    $list = explode("\n", $result);           
                    foreach($list as $value){
                        echo $value.'<br>';
                    }
                }
                ?>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
<?php session_start(); ?>

<HTML>

<HEAD>
    <meta charset="UTF-8" />
    <TITLE>
        EAC2 . Esborrar usuari
    </TITLE>
</HEAD>

<BODY>

    <?php



    if (isset($_POST['user'])) { //si tenemos sesion con usuario y venimos de gest Usuari

        $user = $_POST['user'];
        $dataBase = new SQLite3("tasques_todo.db");
        $dataBase->exec('BEGIN');

        if ($user == 'admin') {

            echo "User admin no es pot esborrar<br>";  //al menos tener siempre un usuario admin
            header('refresh:5;gestUsuaris.php'); //Tornem a llistat

        } else {

            //Elimamos usuario y tascas asociadas
            if (($dataBase->exec("DELETE FROM TODO WHERE creator = '$user'")) & ($dataBase->exec("DELETE FROM users WHERE nom = '$user'"))) {
                //S'ha pogut obrir la base de dades. Borramos usuario y ademas borramos tascas asociadas

                echo "S'ha esborrat l'usuari amb èxit.</br>";
            } else {

                echo "L'usuari no s'ha pogut esborrar.</br>";
            }

            $dataBase->exec('COMMIT');
            $dataBase->close();


            header('refresh:5;gestUsuaris.php'); //Tornem a llistat

        }
    } else { //si no tenemos sesion con usuario


        echo "<h3>ERROR: dades errònies. Sense usuari o usuari per esborrar.</h3>";
        header('refresh:5;login.php'); //Tornem a login

    }

    ?>

</BODY>

</HTML>
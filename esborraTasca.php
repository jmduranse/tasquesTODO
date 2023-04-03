<?php session_start(); ?>

<HTML>

<HEAD>
    <meta charset="UTF-8" />
    <TITLE>
        EAC2 . Modifica Tasca
    </TITLE>
</HEAD>

<BODY>

    <?php

    if (isset($_SESSION['user']) & (isset($_POST['id']))) { //si tenemos sesion con usuario y venimos llistat

        /*recibimos la id de la tasca por POST. */
        //checkear id?
        /*

        if (isset($_SESSION['user'])) { 
        $id = isset($_POST['id']) ? $_POST['id'] : null; // validate and sanitize the input
        if (!$id) {
        echo "Error: Invalid input.";
        exit;
        }*/


        $id = $_POST['id'];
        $dataBase = new SQLite3("tasques_todo.db");
        $dataBase->exec('BEGIN');



        //Eliminem tasca x id
        if ($dataBase->exec("DELETE FROM TODO WHERE id = '$id'")) { //S'ha pogut obrir la base de dades

            echo "S'ha esborrat la tasca amb èxit.</br>";
        } else {

            echo "La tasca no s'ha pogut esborrar.</br>";
        }

        $dataBase->exec('COMMIT');
        $dataBase->close();



        header('refresh:5;llistat.php'); //Tornem a login

    } else { //si no tenemos sesion con usuario


        echo "<h3>ERROR: dades errònies. Sense usuari o id de tasca.</h3>";
        header('refresh:5;login.php'); //Tornem a login

    }

    ?>

</BODY>

</HTML>
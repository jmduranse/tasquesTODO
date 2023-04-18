<?php


if ($_POST['action'] == 'login') {

    //logic to read db and move into session
    $user = $_POST["user"];
    $password = $_POST["password"];


    $dataBase = new SQLite3("tasques_todo.db");

    //Fem la consulta. A la variable $resultat, guardarem un objecte de la classe SQLite3Result
    $query = $dataBase->query("SELECT password,admin FROM users WHERE nom = '$user';");

    $arrayquery = $query->fetchArray(); //debe ser unico array

    $hash = $arrayquery["password"];


    if ($arrayquery != null & (password_verify($password, $hash))) { //user existe y el hash del password dado coincide con el almacenado
        //porque no puedo poner arrayquery directamente?

        session_start();

        $_SESSION['user'] = $user;
        $_SESSION['rol'] = $arrayquery["admin"];

        $dataBase->close();


        header("Location: llistat.php");
    } else { //L'autenticació no és correcta 

        echo "<h3>Autenticació incorrecta</h3>";
        header('refresh:5;login.php'); //Tornem a l'inici
    }
} else if ($_POST['action'] == 'logout') {
    session_start();  //comprobar
    session_destroy();
    unset($_SESSION);

    echo "<h3>Sortint...</h3>";
    header('refresh:3;login.php'); //Tornem a l'inici



} else {  //entramos a la pagina sin redirección de otra


?>
    <!DOCTYPE html>

    <html>


    <head>
        <title>Autenticació</title>
        <meta charset="UTF-8" />

        <style>
            h1 {
                text-align: center;
                color: red;
                font-size: 40px;
            }


            html,
            body {
                height: 100%;
                width: 100%;
                margin: 0;
            }

            body {
                display: flex;
            }

            form {
                margin: auto;

            }
        </style>


    </head>

    <body>


        <form action="login.php" method="post">
            <h1 style="text-align: center"> Tasques TO-DO </h1>
            <p>Usuari: <input required type="text" name="user" /></p>
            <p>Contrasenya: <input required type="password" name="password" /></p>
            <p><input type="submit" value="Accedir" /></p>
            <input type="hidden" name="action" value="login">
        </form>

    </body>

    </html>

<?php
}

?>
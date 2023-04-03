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

    <html ANG='ES'>

    <head>
        <title>Autenticació</title>
        <meta charset="UTF-8" />
    </head>

    <body>
        <form action="login.php" method="post">
            <p>Usuari: <input type="text" name="user" /></p>
            <p>Contrasenya: <input type="password" name="password" /></p>
            <p><input type="submit" value="Accedir" /></p>
            <input type="hidden" name="action" value="login">
        </form>
    </body>

    </html>

<?php
}

?>
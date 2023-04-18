<?php session_start();


if ($_POST['action'] == 'update') {  //llegamos tras haber apretado boton en este mismo formulario

    $user = $_POST['user'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    $check = 'ok'; // indicador para comprobar entrada de datos


    //no vamos a cambiar nombre de usuario , solo password o admin status
    if ((strlen($password1)) < 6 || (strlen($password1)) > 12) { //password debe ser  de 6 a 12

        $check = 'error';
        echo "Mida de  password no permesa<BR>";
    } else if ($password1 != $password2) {  //passwords deben coincidir

        $check = 'error';
        echo "Passwords no coincideixen<BR>";
    } else if ($user == 'admin' & $_POST['admin'] == '0') {  //evitamos que podamos quitar status de admin a admin
        $check = 'error';
        echo "User Admin ha de tenir admin status<BR>";
    }


    if ($check == 'ok') {  // si se cumplen condiciones de las entradas

        $hashedPassword = password_hash($password1, PASSWORD_DEFAULT);

        if ($_POST['admin'] == '1') {
            $admin = '1';
        } else {
            $admin = '0';
        }

        $dataBase = new SQLite3("tasques_todo.db");
        $dataBase->exec('BEGIN');

        //Insertamos los valores. 
        if ($dataBase->exec("UPDATE users SET password='$hashedPassword',admin='$admin' WHERE nom = '$user';")) {
            //insercion exitosa
            echo "S'ha modificat l'usuari amb èxit.</br>";
        } else { //fallo
            echo "No s'ha pogut modificar usuari.</br>";
        }

        $dataBase->exec('COMMIT');
        $dataBase->close(); //cerramos base de datos

        header('refresh:5;gestUsuaris.php'); //Tornem a l'inici

    } else {
        echo "Errors d'entrada.</br>";
        header('refresh:5;gestUsuaris.php');
    }
} else {  //en otro caso llegamos desde el listado de usuarios

    $user = $_POST['user'];

?>

    <HTML>
    <!--generamos ahora un formulario de introduccion de datos, pero en este caso solo muestro nombre de usuario y hueco para cambiar password y opcion de admin -->

    <HEAD>
        <meta charset="UTF-8" />
        <TITLE>
            EAC2 . Modifica Usuari
        </TITLE>
    </HEAD>

    <BODY>
        <h2>TODO LIST - Modificar Usuari</h2>
        <p> Nom d'usuari i password han de tenir entre 6 i 12 caracters</p>
        <FORM method="POST" action=modificaUsuari.php>
            <table>
                <tr>
                    <td>Nom del user:</td>
                    <td><?php echo $user; ?> </td>
                </tr>
                <tr>
                    <td>Password: </td>
                    <td><INPUT required type="password" name="password1"></td>
                </tr>
                <tr>
                    <td>Repeteix password: </td>
                    <td><INPUT required type="password" name="password2"></td>
                </tr>
                <tr>
                    <td><label>Admin</label></td>
                    <td><input type="checkbox" name="admin" value="1"></input></td>

                </tr>
                <tr>
                    <td><INPUT type=submit value="Modificar"><br></td>
                    <INPUT type="hidden" name="user" value="<?php echo $user; ?>">
                    <INPUT type="hidden" name="action" value="update">

                </tr>
            </table>
        </FORM>

        <form method="POST" action="gestUsuaris.php">
            <input type="submit" value="Tornar a llista de usuaris" /> <!--boton para ver listado-->
        </form>
    </BODY>

    </HTML>

<?php
}
?>
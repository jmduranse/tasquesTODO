<?php

session_start();


if (isset($_SESSION['user']) & ($_SESSION['rol']) == 1) {  //si tenemos sesion con usuario y es admin

    if (!isset($_POST['nom'])) {  //si no recibimos post hemos llegado a traves de boton y enseñamos formulario

?>

        <HTML>
        <!--generamos ahora un formulario de introduccion de datos-->

        <HEAD>
            <meta charset="UTF-8" />
            <TITLE>
                EAC2 . Crear Usuari
            </TITLE>
        </HEAD>

        <BODY>
            <h2>TODO LIST - Crear Usuari</h2>
            <p> Nom d'usuari i password han de tenir entre 6 i 12 caracters</p>
            <FORM method="POST" action=creaUsuari.php>
                <table>
                    <tr>
                        <td>Nom Usuari:</td>
                        <td><INPUT required type="text" name="nom"></td>
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

                </table>
                <input type="submit" value="Crear usuari" /> <!--boton para crear usuari-->
            </FORM><BR>
            <!--formulario oculto para volver a llista de usuaris -->
            <FORM method="POST" action=gestUsuaris.php>
                <INPUT type="submit" value="Tornar a llistat de usuaris">

        </BODY>

        </HTML>




<?php

    } else {  //si recibimos post, hacemos la insercion en base de datos. Venimos de esta misma pagina apretando botón


        $user = $_POST['nom'];
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];

        $check = 'ok'; // indicador para comprobar entrada de datos

        $dataBase = new SQLite3("tasques_todo.db");
        $prevuser = $dataBase->query("select * FROM users WHERE nom = '$user'");  //comprobamos que no existen usuarios previos llamados igual


        if ($prevuser->fetchArray()) {  //si hay mas usuarios con ese nombre

            $check = 'error';
            echo "Usuari ja existeix<BR>";
        } else if ((strlen($user)) < 6 || (strlen($user)) > 12) { //nombre debe ser  de 6 a 12

            $check = 'error';
            echo "Mida de nom no permesa<BR>";
        } else if ((strlen($password1)) < 6 || (strlen($password1)) > 12) { //password debe ser  de 6 a 12

            $check = 'error';
            echo "Mida de  password no permesa<BR>";
        } else if ($password1 != $password2) {  //passwords deben coincidir

            $check = 'error';
            echo "Passwords no coincideixen<BR>";
        }

        if ($check == 'ok') {  // si se cumplen condiciones de las entradas



            $hashedPassword = password_hash($password1, PASSWORD_DEFAULT);

            if ($_POST['admin'] == '1') {
                $admin = '1';
            } else {
                $admin = '0';
            }



            $dataBase->exec('BEGIN');


            //Insertamos los valores. 
            if ($dataBase->exec("INSERT INTO users (nom,password,admin) VALUES ('$user','$hashedPassword','$admin')")) {
                //inserccion exitosa
                echo "S'ha creat l'usuari amb èxit.</br>";
            } else { //fallo
                echo "No s'ha pogut crear usuari.</br>";
            }

            $dataBase->exec('COMMIT');
            $dataBase->close(); //cerramos basde de datos

            header('refresh:5;gestUsuaris.php'); //Tornem a l'inici

        } else {

            $dataBase->close();   //cerramos basde de datos

            echo "Errors d'entrada.</br>";

            header('refresh:5;creaUsuari.php');
        }
    }
} else { //si no tenemos sesión con usuario o  no es admin


    echo "<h3>ERROR: no hi ha usuari o no es admin</h3>";
    header('refresh:5;login.php'); //Tornem a login



}

?>
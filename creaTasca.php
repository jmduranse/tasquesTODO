<?php

session_start();


if (isset($_SESSION['user'])) {  //si tenemos sesion con usuario

    if (!isset($_POST['nom'])) {  //si no recibimos post hemos llegado a traves de boton y enseñamos formulario

?>

        <HTML>
        <!--generamos ahora un formulario de introduccion de datos-->

        <HEAD>
            <meta charset="UTF-8" />
            <TITLE>
                EAC2 . Inserir Tasca
            </TITLE>
        </HEAD>

        <BODY>
            <h2>TODO LIST - Inserir Tasca</h2>
            <FORM method="POST" action=creaTasca.php>
                <table>
                    <tr>
                        <td>Nom de la tasca:</td>
                        <td><INPUT required type="text" name="nom"></td>
                    </tr>
                    <tr>
                        <td>Descripci&oacute; de la tasca: </td>
                        <td><INPUT required type="text" name="descripcio"></td>
                    </tr>
                    <tr>

                        <td><label>Selecciona estat de la tasca</label></td>
                        <td><select name="estat">
                                <option value=""></option>
                                <option value="pendent">Pendent</option>
                                <option value="en_progres">En progr&eacute;s</option>
                                <option value="acabada">Acabada</option>
                            </select></td>

                    </tr>
                    <tr>
                        <td>Comentaris:</td>
                        <td><textarea name="comentaris" rows="5" cols="21"></textarea></td>
                    </tr>
                    <tr>
                        <td><INPUT type=submit value="Insertar"><br></td>
                        <td></td>
                    </tr>
                </table>
            </FORM>

            <form method="POST" action="llistat.php">
                <input type="submit" value="Consultar llista de tasques" /> <!--boton para volver a listado-->
            </form>
        </BODY>

        </HTML>

<?php

    } else {  //si recibimos post, hacemos la insercion en base de datos. Venimos de esta misma pagina apretando botón

        //escribimos en base de datos 

        //recupero variables desde $_POST y $_SESSION


        $creator = $_SESSION['user'];
        $nom = $_POST['nom'];
        $descripcio = $_POST['descripcio'];
        $estat = $_POST['estat'];
        $comentaris = $_POST['comentaris'];


        $dataBase = new SQLite3("tasques_todo.db");
        $dataBase->exec('BEGIN');


        //Insertamos los valores. id es automatico y se autoincrementa
        if ($dataBase->exec("INSERT INTO  TODO (creator,nom,descripcio,estat,comentaris) VALUES ('$creator','$nom','$descripcio','$estat','$comentaris')")) {

            echo "S'ha inserit la tasca amb èxit.</br>";
        } else { //S'ha pogut obrir la base de dades

            echo "No s'ha pogut insertar la tasca.</br>";
        }

        $dataBase->exec('COMMIT');
        $dataBase->close();

        header('refresh:3;llistat.php'); //Tornem a l'inici



    }
} else { //si no tenemos sesion con usuario


    echo "<h3>ERROR: no hi ha usuari.</h3>";
    header('refresh:5;login.php'); //Tornem a login



}

?>
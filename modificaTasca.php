<?php session_start();


if (isset($_POST['nom'])) {  //llegamos tras haber apretado boton en este mismo formulario

    //escribimos en base de datos 



    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $descripcio = $_POST['descripcio'];
    $estat = $_POST['estat'];
    $comentaris = $_POST['comentaris'];


    $dataBase = new SQLite3("tasques_todo.db");
    $dataBase->exec('BEGIN');

    //Modificamos los valores del registro

    //sanitizamos entrada primero

    $query = $dataBase->prepare("UPDATE TODO SET nom = ?, descripcio = ?, estat = ?, comentaris = ? WHERE id = ? and creator = ?");
    $query->bindValue(1, $nom);
    $query->bindValue(2, $descripcio);
    $query->bindValue(3, $estat);
    $query->bindValue(4, $comentaris);
    $query->bindValue(5, $id);
    $query->bindValue(6, $_SESSION['user']);



    if ($query->execute()) {

        echo "S'ha modificat la tasca amb èxit.</br>";
    } else { //S'ha pogut obrir la base de dades

        echo "No s'ha pogut modificar la tasca.</br>";
    }

    $dataBase->exec('COMMIT');
    $dataBase->close();

    header('refresh:5;llistat.php'); //Tornem a l'inici

} else {  //si solo tenemos id hemos llegado desde el formulario de lista



    $id = $_POST['id'];

    $dataBase = new SQLite3("tasques_todo.db");
    $dataBase->exec('BEGIN');

    //Recupero valores basados en id
    //sanitizo entrada ya que la id me viene a traves de post y formulario HTML

    $query = $dataBase->prepare("SELECT id,nom,descripcio,estat,comentaris FROM TODO WHERE id = ? and creator = ?;");
    $query->bindValue(1, $id);
    $query->bindValue(2, $_SESSION['user']);
    $result = $query->execute();

    $arrayquery = $result->fetchArray(); //solo deberia haber uno (id único)


    $dataBase->exec('COMMIT');
    $dataBase->close();

?>

    <HTML>
    <!--generamos ahora un formulario de introduccion de datos, pero en este caso prerellanado con los datos
que hemos obtenido antes -->

    <HEAD>
        <meta charset="UTF-8" />
        <TITLE>
            EAC2 . Modifica Tasca
        </TITLE>
    </HEAD>

    <BODY>
        <h2>TODO LIST - Modificar Tasca</h2>
        <FORM method="POST" action=modificaTasca.php>
            <table>
                <tr>
                    <td>Nom de la tasca:</td>
                    <td><INPUT required type="text" name="nom" value="<?php echo $arrayquery['nom']; ?>"> </td>
                </tr>
                <tr>
                    <td>Descripci&oacute; de la tasca: </td>
                    <td><INPUT required type="text" name="descripcio" value="<?php echo $arrayquery['descripcio']; ?>"></td>
                </tr>
                <tr>

                    <td><label>Selecciona estat de la tasca</label></td>
                    <td><select name="estat">
                            <option value="<?php echo $arrayquery['estat']; ?>"><?php echo $arrayquery['estat']; ?></option>
                            <option value="pendent">Pendent</option>
                            <option value="en_progres">En progr&eacute;s</option>
                            <option value="acabada">Acabada</option>
                        </select></td>

                </tr>
                <tr>
                    <td>Comentaris:</td>
                    <td><textarea name="comentaris" rows="5" cols="21"> <?php echo $arrayquery['comentaris']; ?></textarea></td>
                </tr>
                <tr>
                    <td><INPUT type=submit value="Insertar"><br></td>
                    <INPUT type="hidden" name="id" value="<?php echo $arrayquery["id"]; ?>">

                </tr>
            </table>
        </FORM>

        <form method="POST" action="llistat.php">
            <input type="submit" value="Consultar llista de tasques" /> <!--boton para ver listado-->
        </form>
    </BODY>

    </HTML>

<?php
}
?>
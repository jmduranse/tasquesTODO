<?php session_start();


if (isset($_POST['nom'])) {  //llegamos tras haber apretado boton en este mismo formulario

    //escribimos en base de datos 

    print_r($_POST);

    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $descripcio = $_POST['descripcio'];
    $estat = $_POST['estat'];
    $comentaris = $_POST['comentaris'];

    echo "id  $id<BR>";
    echo "nom  $nom<BR>";
    echo "estat  $estat<BR>";
    echo "comentaris $comentaris<BR>";

    $dataBase = new SQLite3("tasques_todo.db");
    $dataBase->exec('BEGIN');

    //Modificamos los valores del regustro
    if ($dataBase->exec("UPDATE TODO SET nom = '$nom',descripcio = '$descipcio', estat = '$estat', comentaris='$comentaris' WHERE id='$id';")) {

        echo "S'ha modificat la tasca amb èxit.</br>";
    } else { //S'ha pogut obrir la base de dades

        echo "No s'ha pogut modificar la tasca.</br>";
    }

    $dataBase->exec('COMMIT');
    $dataBase->close();
} else {  //si solo tenemos id hemos llegado desde el formulario de lista

    //if (isset($_POST['id']) & (!isset($_POST['name'])))
    $id = $_POST['id'];

    echo "id $id";

    $dataBase = new SQLite3("tasques_todo.db");
    $dataBase->exec('BEGIN');

    //Recupero valores basados en id
    $query = $dataBase->query("SELECT id,nom,descripcio,estat,comentaris FROM TODO WHERE id = '$id';");

    $arrayquery = $query->fetchArray(); //solo deberia haber uno (id único)


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
                    <td><textarea name="comentaris" <?php echo $arrayquery['comentaris']; ?> rows="5" cols="21"></textarea></td>
                </tr>
                <tr>
                    <td><INPUT type=submit value="Insertar"><br></td>
                    <td></td>
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
<?php session_start();



if (isset($_SESSION['user'])) {  //entramos estando logueados



?>
    <HTML>

    <HEAD>
        <meta charset="UTF-8" />
        <TITLE>
            TODO LIST - llista de tasques
        </TITLE>
        <style>
            table {
                border-width: 2px;
                border-style: solid;
            }

            td,
            th,
            tr {
                border-width: 2px;
                border-style: solid;
                padding: 2px;
            }
        </style>
    </HEAD>

    <BODY>

        <?php

        //generamos banner con nombre usario y rol
        if ($_SESSION['rol'] == '1') {
            $rol = 'admin';
        } else {
            $rol = 'user';
        }

        $user = $_SESSION['user'];

        echo "<h2>Usuari $user ($rol)</h2>"; ?>


        <!--tabla con lista de tasques-->
        <table>
            <thead>
                <th>Nom Tasca</th>
                <th>Descripció Tasca</th>
                <th>Estat Tasca</th>
                <th>Comentaris</th>
            </thead>

            <?php
            //obtenemos informacion de la lista de tareas del usuario que este logueado usando una query

            $user = $_SESSION["user"];
            $dataBase = new SQLite3("tasques_todo.db");
            $query = $dataBase->query("SELECT id,nom,descripcio,estat,comentaris FROM TODO WHERE creator = '$user';");
            //recuperamos id para identificar la tasca de forma unica

            while ($arrayquery = $query->fetchArray()) {

            ?>
                <tr>
                    <td><?php echo $arrayquery["nom"]; ?></td>
                    <td><?php echo $arrayquery["descripcio"]; ?></td>
                    <td><?php echo $arrayquery["estat"]; ?></td>
                    <td><?php echo $arrayquery["comentaris"]; ?></td>
                    <td> <!--en esta posición de la tabla ponemos un boton llama al formulario para modificar, pasando como post la id de la tasca-->
                        <FORM method="POST" action=modificaTasca.php>
                            <INPUT type="submit" value="Modificar"> <!--boton para enviar-->
                            <INPUT type="hidden" name="id" value="<?php echo $arrayquery["id"]; ?>"> <!--formulario oculto que envia la id de tasca-->
                        </FORM>
                    </td>
                    <td> <!--en esta posición de la tabla ponemos un boton llama al formulario para borrar, pasando como post la id de la tasca-->
                        <FORM method="POST" action=esborraTasca.php>
                            <INPUT type="submit" value="Esborrar"> <!--boton para enviar-->
                            <INPUT type="hidden" name="id" value="<?php echo $arrayquery["id"]; ?>"> <!--formulario oculto que envia la id de tasca-->
                        </FORM>
                    </td>
                </tr>



            <?php

            }
            $dataBase->close(); //  Cerramos base de datos
            ?>



        </table>
        <BR>
        <HR>
        <TABLE>
            <TR>
                <TD>
                    <!--formulario para crear tasca-->
                    <FORM method="POST" action=creaTasca.php>
                        <INPUT type="submit" value="Crear nova tasca">

                    </FORM>
                </TD>

                <TD>
                    <!--formulario oculto para cerrar sesion-->
                    <FORM method="POST" action=login.php>
                        <INPUT type="submit" value="Sortir">
                        <INPUT type="hidden" name="action" value="logout">
                    </FORM>
                </TD>
            </TR>
        </TABLE>


        <HR>

    <?php

    if ($_SESSION['rol'] == 1) { //generamos boton de gestion de usuarios solo si estamos logueados como admin

        echo '<!--ESTA PARTE COMO ADMIN SOLO-->
            <!--formulario oculto para gestion de usuarios-->
            <p>Gestió usuaris admin</p>
            <FORM method="POST" action=gestUsuaris.php>
                <INPUT type="submit" value="Gestió de usuaris">

            </FORM></BODY></HTML>';
    } else {

        echo '</BODY></HTML>';
    }
} else { //no estamos logueados

    echo "<h3>ERROR: no hi ha usuari.</h3>";
    header('refresh:5;login.php'); //Tornem a login


}
    ?>
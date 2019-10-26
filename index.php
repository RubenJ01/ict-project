<?php
/**
 * @file index.php
 *
 * @brief Dit is de homepagina, hier worden de fietsen getoond.
 *
 * Gebruikers kunnen hier zoeken naar fietsen.
 */
if (!isset($_SESSION)) {
    session_start();
}
require 'utils/database_connection.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Home</title>
    <meta charset="UTF-8">
</head>
<body class="hoofdpagina">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(function(){
        $(".fiets_blok").slice(0, 3).show(); // pakt de eerste 3 fietsen
        $("#buttonLaadMeer").click(function(e){
            e.preventDefault();
            if($(".fiets_blok:hidden").length == 0){ // kijkt of er nog fietsen zijn
                alert("Er zijn geen fietsen meer");
            }
            $(".fiets_blok:hidden").slice(0, 6).show(); // Pak 6 nieuwe fietsen
        });
    });
    function laadMeer() {
        document.getElementById("tekstHoofdpagina").style.display = "none";
        document.getElementById("filter").style.display = "block";
        document.getElementById("bodyFietsen").style.width = "80%";
    }


</script>

<div><?php include 'menu.php'; ?></div>

<div id="tekstHoofdpagina">
    <h1>Welkom bij leenfiets</h1>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam nec ex convallis, ultricies enim non, vestibulum dui. Fusce nec dui ac leo pharetra eleifend. Praesent lacus ante, gravida vitae purus id, dignissim egestas odio. Nulla in aliquet ex. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris sagittis mattis risus, a congue nulla aliquam ac. Donec ac ante fringilla, sollicitudin lorem non, interdum arcu. Vivamus tempor eget libero blandit fermentum. Proin consequat viverra felis sit amet dapibus. Sed at erat lacinia, dictum diam nec, pulvinar diam. Vestibulum pharetra volutpat rhoncus.</p>
</div>
<div id= "filter"><h1 style="">Filter</h1>
<form method="post" id="fietsentoevoegen" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <b>Merk</b><br> <select style="width: 95%" id = "mySelect" name="merk_naam" multiple><?php
            $sql = "SELECT merk_naam, id FROM merk_fiets order by merk_naam asc";
            $result = $mysqli->query($sql);
        ?><option selected  value="0"> Alle soorten </option><?php
            while($row = $result->fetch_assoc())
            {
                ?>
                <option value =<?php echo($row['id'])?><?php if(isset($_POST['merk_naam'])){ if($_POST['merk_naam']==($row['id'])){echo ' selected';}} ?>><?php echo($row['merk_naam']) ?></option><?php
            }
            ?>
        </select><br>
    <b>Soort fiets</b><br><select style="width: 95%" name="soort_fiets">
            <?php
            $sql = "SELECT soort_fiets, id FROM soort_fiets order by soort_fiets asc";
            $result = $mysqli->query($sql);
            ?><option selected  value="0"> Alle soorten </option><?php
            while($row = $result->fetch_assoc()) {
                ?>

                <option value = <?php echo($row['id'])?><?php if(isset($_POST['soort_fiets'])){ if($_POST['soort_fiets']==($row['id'])){echo ' selected';}} ?>><?php echo($row['soort_fiets'])?></option><?php
            }
            ?>
        </select>
    <b>Geslacht fiets</b><br>
    <select name="geslacht_fiets" style="width: 95%">
        <option selected  value="0"> Alle soorten </option>
        <option  value="Man" <?php if(isset($_POST['geslacht_fiets'])){ if($_POST['geslacht_fiets'] == 'Man'){echo ' selected';}} ?>>Mannen fiets</option>
        <option  value="Vrouw" <?php if(isset($_POST['geslacht_fiets'])){ if($_POST['geslacht_fiets'] == 'Vrouw'){echo ' selected';}} ?>>Vrouwen fiets </option>
        <option  value="Onzijdig" <?php if(isset($_POST['geslacht_fiets'])){ if($_POST['geslacht_fiets'] == 'Onzijdig'){echo ' selected';}} ?>>Onzijdige fiets</option>
    </select>
    <br><b>Kleur fiets</b><br><select style="width: 95%" name="kleur" multiple>
            <option selected  value="0"> Alle soorten </option>
            <option value="Geel" <?php if(isset($_POST['kleur'])){ if($_POST['kleur'] == 'Geel'){echo ' selected';}} ?>>Geel</option>
            <option value="Oranje" <?php if(isset($_POST['kleur'])){ if($_POST['kleur'] == 'Oranje'){echo ' selected';}} ?>>Oranje</option>
            <option value="Zwart" <?php if(isset($_POST['kleur'])){ if($_POST['kleur'] == 'Zwart'){echo ' selected';}} ?>>Zwart</option>
            <option value="Blauw" <?php if(isset($_POST['kleur'])){ if($_POST['kleur'] == 'Blauw'){echo ' selected';}} ?>>Blauw</option>
            <option value="Grijs" <?php if(isset($_POST['kleur'])){ if($_POST['kleur'] == 'Grijs'){echo ' selected';}} ?>>Grijs</option>
            <option value="Wit" <?php if(isset($_POST['kleur'])){ if($_POST['kleur'] == 'Wit'){echo ' selected';}} ?>>Wit</option>
            <option value="Roze" <?php if(isset($_POST['kleur'])){ if($_POST['kleur'] == 'Roze'){echo ' selected';}} ?>>Roze</option>
        </select><br>
        <b>Versnellingen</b><br>
        Van <input style="width: 25%;" min="0" type="number" name="min_versnelling" value="<?php echo (isset($_POST['min_versnelling']) ? $_POST['min_versnelling'] : ''); ?>">
        tot <input style="width: 25%;" min="0" type="number" name="max_versnelling" value="<?php echo (isset($_POST['max_versnelling']) ? $_POST['max_versnelling'] : ''); ?>">
        <br><b>Prijs</b><br>
        Van <input style="width: 25%;" min="0" type="number" name="min_prijs" value="<?php echo (isset($_POST['min_prijs']) ? $_POST['min_prijs'] : ''); ?>">
        tot <input style="width: 25%;" min="0" type="number" name="max_prijs" value="<?php echo (isset($_POST['max_prijs']) ? $_POST['max_prijs'] : ''); ?>">
        <br><b>Plaats</b><br>
        <input type="text" style="width: 95%" name="plaats" value="<?php echo (isset($_POST['plaats']) ? $_POST['plaats'] : ''); ?>"><br><br>
    <input type="reset" name="reset" value="Reset">
        <input type="submit" name="filter" value="Filter">
</form>
    <p>WORK IN PROGRESS</p>

</div>

<div id= "bodyFietsen">
    <?php
    if(isset($_POST['filter'])){
        echo "<script> laadMeer(); </script>";
        $sql = "SELECT fietsen.borg, fietsen.prijs, fietsen.versnellingen, fietsen.id, fietsen.plaats, fietsen.kleur_fiets, fietsen.model, fietsen.geslacht_fiets, fietsen.adres, fietsen.foto, soort_fiets.soort_fiets, merk_fiets.merk_naam 
                from fietsen, merk_fiets, soort_fiets   
                WHERE fietsen.id_soort_fiets = soort_fiets.id 
                AND fietsen.id_merk_fiets = merk_fiets.id";
        if(!empty($_POST['merk_naam'])){
            $sql .= " AND fietsen.id_merk_fiets = " .$_POST['merk_naam'];
        }
        if(!empty($_POST['soort_fiets'])){
            $sql .= " AND fietsen.id_soort_fiets = " .$_POST['soort_fiets'];
        }
        if(!empty($_POST['geslacht_fiets'])){
            $sql .= " AND fietsen.geslacht_fiets = '" .$_POST['geslacht_fiets'] ."'";
        }
        if(!empty($_POST['kleur'])){
            $sql .= " AND fietsen.kleur_fiets = '" .$_POST['kleur'] ."'";
        }
        if(!empty($_POST['min_versnelling'])){
            $sql .= " AND fietsen.versnellingen >= " .$_POST['min_versnelling'];
        }
        if(!empty($_POST['max_versnelling'])){
            $sql .= " AND fietsen.versnellingen <= " .$_POST['max_versnelling'];
        }
        if(!empty($_POST['min_prijs'])){
            $sql .= " AND fietsen.prijs >= " .$_POST['min_prijs'];
        }
        if(!empty($_POST['max_prijs'])){
            $sql .= " AND fietsen.prijs <= " .$_POST['max_prijs'];
        }
        if(!empty($_POST['plaats'])) {
            $sql .= " AND fietsen.plaats LIKE '" . $_POST['plaats'] . "'";
        }
    }
    else{
    $sql = "SELECT fietsen.borg, fietsen.prijs, fietsen.versnellingen, fietsen.id, fietsen.plaats, fietsen.kleur_fiets, fietsen.model, fietsen.geslacht_fiets, fietsen.adres, fietsen.foto, soort_fiets.soort_fiets, merk_fiets.merk_naam 
                from fietsen, merk_fiets, soort_fiets   
                WHERE fietsen.id_soort_fiets = soort_fiets.id 
                AND fietsen.id_merk_fiets = merk_fiets.id ";
    }
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        //maakt blokjes van sql
        while($row = $result->fetch_assoc()) {
            $afbeelding = $row['foto'];
            if (empty($afbeelding)){
                $afbeelding = "fiets_afbeeldingen/default.png";
            }
            echo   "<div class='fiets_blok'>
                    <h1>$row[merk_naam] $row[model]</h1>
                    <h4>€$row[prijs] per dag<br/></h4>
                    <ul>
                    <li>Plaats: $row[plaats]</li>
                    <li>Soort fiets: $row[soort_fiets]</li>
                    <li>Aantal versnellingen $row[versnellingen]</li>
                    <li>Geslacht fiets: $row[geslacht_fiets]</li>
                    </ul><div class='afbeeldingDiv'>
                    <img src = $afbeelding?t=time()></div>
                    <a href=fiets.php?fiets_id=$row[id]  >Fiets bekijken</a>
                    </div>";
        }
    }
    else { echo "Er zijn momenteel geen fietsen beschikbaar"; }
    ?>
</div>
<button id="buttonLaadMeer" onclick="laadMeer()">Laad meer</button>
</body>
</html>



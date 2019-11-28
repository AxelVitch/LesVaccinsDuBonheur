<?php

$errors = array();
$success = false;
if (!empty($_SESSION['login']['role']=== 'Admin')){
if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    // request
    $sql = "SELECT * FROM vaccins
          WHERE id = $id";
    $query = $pdo->prepare($sql);
    $query->execute();
    $vaccins = $query->fetch();
    //debug($vaccins);
    if (!empty($vaccins)) {

        if (!empty($_POST['submitted'])) {
            // Faille XSS
            //debug($_POST);
            $nom = clean($_POST['nom']);
            $created_at = clean($_POST['created_at']);
            $rappel1 = clean($_POST['rappel1']);
            $rappel2 = clean($_POST['rappel2']);

            // no error
            if (count($errors) == 0) {
                // insert into
                $success = true;
                // UPDATE SQL
                $sql = "UPDATE vaccins
          SET nom = :nom, created_at = :created_at, rappel1 = :rappel1, rappel2 = :rappel2
          WHERE ID = $id";
                $query = $pdo->prepare($sql);
                $query->bindValue(':nom', $nom, PDO::PARAM_STR);
                $query->bindValue(':created_at', $created_at, PDO::PARAM_STR);
                $query->bindValue(':rappel1', $rappel1, PDO::PARAM_STR);
                $query->bindValue(':rappel2', $rappel2, PDO::PARAM_INT);
                $query->execute();

            }
        }

    } else {
        die('404');
    }
}

?>

<?php if ($success) { ?>
    <p>Bravo ma biche</p>
<?php } else { ?>
    <form action="" method="post">
        <label for="nom">Nom du Vaccin*</label>
        <input type="text" id="nom" name="nom" value="<?php if (!empty($_POST['nom'])) {
            echo $_POST['nom'];
        } else {
            echo $vaccins['nom'];
        } ?>">
        <span class="error"><?php if (!empty($errors['nom'])) {
                echo $errors['nom'];
            } ?></span>

        <label for="created_at">Modifier le*</label>
        <input type="text" id="created_at" name="crated_at" value="<?php if (!empty($_GET['created_at'])) {
            echo $_GET['created_at'];
        } else {
            echo $vaccins['Created_at'];
        } ?>">
        <span class="error"><?php if (!empty($errors['created_at'])) {
                echo $errors['created_at'];
            } ?></span>

        <label for="rappel1">Premier rappel le*</label>
        <input type="text" id="rappel1" name="rappel1" value="<?php if (!empty($_GET['rappel1'])) {
            echo $_GET['rappel1'];
        } else {
            echo $vaccins['rappel1'];
        } ?>">
        <span class="error"><?php if (!empty($errors['rappel1'])) {
                echo $errors['rappel1'];
            } ?></span>

        <label for="population">Second rappel le*</label>
        <input type="text" id="rappel2" name="rappel2" value="<?php if (!empty($_GET['rappel2'])) {
            echo $_GET['rappel2'];
        } else {
            echo $vaccins['rappel2'];
        } ?>">
        <span class="error"><?php if (!empty($errors['rappel2'])) {
                echo $errors['rappel2'];
            } ?></span>

        <input type="submit" name="submitted" value="Envoyer">

    </form>
<?php } }else{
    echo "Erreur 403, vous n'avez pas accès a cette fonctionnalité";
}?>

<?php
    include('inc/header.php');
    require('inc/bibliotheque.class.php');
    $rechercheLivre = new Bibliotheque;
    $livreRechercher = $rechercheLivre->recupererInfosLivre($_GET['isbn']);
    if(!empty($_POST['dateRetrait'])){
        var_dump($_POST['dateRetrait']);
        $demandeEmprunt = new Utilisateur;
        $ajouterLivreEmprunts = $demandeEmprunt->ajouterLivreListeEmprunts($_GET['isbn'], $_SESSION['id'], $_POST['dateRetrait']);
        if($ajouterLivreEmprunts){
            echo "Nous avons enregistré votre demande d'emprunt. Vous pouvez venir retirer votre livre!";
            header("refresh:2;url=livres.php");
        }else {
            echo "Une erreur est survenue!";
        }
    }
 ?>
<section>

    <p>
        Vous désirez le livre suivant :
        <?php
            echo $livreRechercher['Titre'];
            echo '<br />';
            echo $livreRechercher['Auteur'];
            echo "<br />";
            echo $livreRechercher["Editeur"];
         ?>
         <h4>Confirmer ?</h4>
         <br />
         <form action="#" method="POST">
             <label for="dateRetrait">Date de Retrait : </label>
             <input type="date" id="dateRetrait" name="dateRetrait" />
             <input type="submit" value="Réserver" id="submitReserver"/>
         </form>
    </p>
</section>
<script>

var aujourdhui = new Date();
var current = new Date();
aujourdhui.setDate(current.getDate() - 1);
current.setDate(current.getDate() + 2);

var submitBtn = document.getElementById('submitReserver');
submitBtn.onclick = function(e){

var retraitDate = new Date(dateRetrait.value);
if(retraitDate > current || retraitDate < aujourdhui){
    e.preventDefault();
    alert('Si vous désirez ce livre, vous devez le retirer dans les 48 heures.');
}
}

</script>


 <?php
     include('inc/footer.php');
  ?>

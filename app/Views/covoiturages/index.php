<?php
echo '<pre>';
var_dump('page: ecoride\app\Views\covoiturages\index.php');
echo '</pre>';
?>

<section class="presentation-section">
    <!-- Contenu Textuel -->
    <div class="presentation-content">
        <h1>Bienvenue sur EcoRide, le covoiturage qui prend soin de la planète !</h1>
        <p>
            Lancée en France, EcoRide est née de l'ambition de <strong>réduire l'empreinte carbone</strong> des trajets quotidiens et occasionnels.
            Nous connectons les conducteurs ayant des places libres avec des passagers soucieux de l'environnement, offrant ainsi une solution de mobilité économique, conviviale et, surtout, écologique.
            Rejoignez notre communauté et faites de chaque kilomètre un pas vers un avenir plus vert.
        </p>
        <p><strong>Moins de voitures, moins de CO2, plus de partage. C'est ça, l'esprit EcoRide.</strong></p>
    </div>

    <!-- Galerie d'images -->
    <div class="presentation-images-gallery">
        <img src="https://placehold.co/200x200/4CAF50/ffffff?text=Covoiturage"
            alt="Illustration covoiturage" class="presentation-image-item">

        <img src="https://placehold.co/200x200/4CAF50/ffffff?text=Economie"
            alt="Illustration économie" class="presentation-image-item">

        <img src="https://placehold.co/200x200/4CAF50/ffffff?text=Ecologie"
            alt="Illustration écologie" class="presentation-image-item">

        <img src="https://placehold.co/200x200/4CAF50/ffffff?text=Communaute"
            alt="Illustration communauté" class="presentation-image-item">
    </div>

</section>



<div class="ecoride-trip-container">

    <h2 class="section-title">Section voyages</h2>

    <h3 class="section-soustitle">Recherche</h3>
    <form class="search-form" method="post" action="index.php?p=journey">
        <input type="text" name="lieu_depart" placeholder="Lieu de départ">
        <input type="text" name="lieu_arrivee" placeholder="Destination">
        <input type="date" name="date">
        <button type="submit">Rechercher</button>
    </form>

</div>
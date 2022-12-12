<!--
This file contains the main application
The app is composed of:
    - a post selector to add a new devis checker tab, with reset and print buttons
    - a tab system to navigate between each devis checker
Note: Some blocks must not be display with navigator but show when printing. Those blocks have classes: `d-none d-print-block`
-->

    <!-- Logo for print -->
    <div class="d-none d-print-block">
        <div class="row" style="height: 60px;">
            <div class="col-4" style="height: 60px;">
                <img src="data/<?php echo $profil ?>/logo-pdf-gauche.png" class="rounded" alt="logo partenaire 1" height="100%">
            </div>
            <div class="col-4 text-center" style="height: 60px;">
                <img src="data/<?php echo $profil ?>/logo-profil.png" class="ms-auto" alt="logo du profil <?php echo $profil ?>" height="100%">
            </div>
            <div class="col-4 text-end" style="height: 60px;">
                <img src="data/<?php echo $profil ?>/logo-pdf-droite.png" class="rounded" alt="logo partenaire 2" height="100%">
            </div>
        </div>
        <div class="row justify-content-md-center">
            <h1>Vérification de devis</h1>
        </div>
    </div>

    <!-- List de validation -->
    <div class="row d-print-none py-2 ps-0">
        <div class="col-auto py-1">
            <select class="form-select text-primary border-primary bg-primary bg-opacity-10"  id="postSelector" onChange="createNewDevis()">
                <option>Ajouter un poste de travaux</option>
            </select>
        </div>
        <div class="col-auto pe-0 py-1">
            <a class="btn btn-primary" role="button" onClick="resetForm()" aria-controls="reset">reset</a>
        </div>
        <div class="col-auto pe-0 py-1">
            <a class="btn btn-primary d-none d-sm-block" role="button" onClick="printReport();" aria-controls="imprimer"><i class="bi bi-printer"></i></a>
        </div>
    </div>
    <div class="row d-print-block">
        <div class="col">
            <ul class="nav nav-tabs d-print-none" id="myDevis" role="tablist">
                <li class="nav-item" role="presentation"><a class="nav-link active" id="general-tab" type="button" role="tab" data-bs-toggle="tab"
                    data-bs-target="#general" href="general" aria-controls="general" aria-selected="false" tabindex="-1"
                    onCick=selectTab(this)>Général</a></li>
            </ul>
            <div class="tab-content" id="myDevisContent">
                <div class="tab-pane fade show active ms-sm-4" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <?php
                        $textBefore = file_get_contents("data/onglet_général_avant.md");
                        echo $Parsedown->text($textBefore);
                    ?>
                    <div class="mt-5 p-3 border border-info">
                        <h4 class="d-print-none text-info">Partie réservée aux conseiller·ère Énergie : Informations générales</h4>
                        <form>
                            <div class="row p-1 ms-sm-4">
                                <label for="current-date"  class="col-3 col-form-label">Date</label>
                                <div class="col-8"><input class="commentaire col form-control" id="current-date" disabled></input></div>
                            </div>
                            <div class="row p-1 ms-sm-4">
                                <label for="particulier"  class="col-sm-3 col-form-label">Nom prénom du particulier</label>
                                <div class="col-sm-8"><input class="form-control commentaire" style="text-transform: uppercase" id="particulier" autocomplete="off" placeholder="">
                                </div>
                            </div>
                            <div class="row p-1 ms-sm-4">
                                <label for="conseiller"  class="col-sm-3 col-form-label">Nom/prénom du·de la conseiller·ère</label>
                                <div class="col-sm-8"><input class="form-control commentaire" style="text-transform: uppercase" id="conseiller" placeholder=""></div>
                            </div>
                            <div class="row p-1 ms-sm-4">
                                <label for="commentaires"  class="col-sm-3 col-form-label">Commentaires</label>
                                <div class="col-sm-11"><textarea class="form-control commentaire" id="commentaires" rows="3"
                                        autocomplete="off" oninput=auto_grow(this)></textarea></div>
                            </div>
                        </form>
                    </div>
                    <div class="row my-5 py-5 d-none d-print-block">
                        <h3>Sommaire</h3>
                        <ol class="ms-5 ps-5" id="sommaire">
                        </ol>
                    </div>
                    <div class="row mt-5 text-muted text-center">
                        <?php
                            $textAfter = file_get_contents("data/onglet_général_après.md");
                            echo $Parsedown->text($textAfter);
                        ?>
                    </div>
                    <div class="row my-3 text-muted text-center d-none d-print-block">
                        <em><b>Note :</b> les [XX] à la fin de certaines contraintes font références à des notes explicatives, regroupées dans les annexes, à la fin du rapport.</em>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-none d-print-block">
        <h2 class="post-title">Annexes</h2>
        <div id="annexes">
        </div>
    </div>

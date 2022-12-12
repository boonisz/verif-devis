<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="data/favicon.png" />
    <title>Vérification de devis - AGEDEN - ALEC - 38</title>
    <meta name="description" content="Un outil pour vérifier ses devis pour la demande d'aide (Ma Prime Rénov', les Certificats d'Économies d'Énergie ou CEE, ou encore les aides locales)">

    <!-- Add bootstrap lib -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">

    <!-- Add JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Add lib to load YAML config file -->
    <script  type="text/javascript"  src="js/js-yaml.min.js"></script>

    <!-- Custom style -->
    <link href="styles.css" rel="stylesheet" />

    <!-- Select profil ------------------------------------------------------------------------------------------------>
    <?php
        include 'php/params.php';

        if (!in_array($profil, $profils)) {
            ?>
                <div class="m-2 alert alert-danger alert-dismissible fade show" role="alert">
                    Le profil "<?php echo $profil ?>" n'existe pas.<br/>
                    Selection du profil par défaut : "<?php echo $default_profil ?>"
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php
            $profil = $default_profil;
        }
    ?>
    <link href="data/<?php echo $profil ?>/styles.css" rel="stylesheet" />
</head>
    <!-- Prepare parser for markdown (used by "Mode d'emploi" (ex documentation) and actus section) ---------------------------------------->
    <?php
        require 'php/Parsedown.php';
        $Parsedown = new Parsedown();
    ?>

<body class="d-flex flex-column bg-light d-print-block min-vh-100">
    <!-- Menu --------------------------------------------------------------------------------------------------------->
    <nav class="menu container navbar navbar-expand-lg d-print-none bg-app-color">
        <div class="container-fluid">
            <span class="navbar-brand me-5">
                Vérification de devis
            </span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="nav navbar-nav" id="myTab" role="tablist">
                    <li class="nav-item ms-3" role="presentation">
                        <a class="menu nav-link active" id="doc-tab" href="#doc" data-bs-toggle="tab" data-bs-target="#doc-tab-pane" type="button" role="tab" aria-controls="doc-tab-pane" aria-selected="true">Mode d'emploi</a>
                    </li>
                    <li class="nav-item ms-3" role="presentation">
                        <a class="menu nav-link" id="app-tab" href="#app" data-bs-toggle="tab" data-bs-target="#app-tab-pane" type="button" role="tab" aria-controls="app-tab-pane" aria-selected="false">Vérif' Devis</a>
                    </li>
                    <li class="nav-item ms-3" role="presentation">
                        <a class="menu nav-link" id="contact-tab" href="#contact" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Contact</a>
                    </li>
                    <li class="nav-item ms-3" role="presentation">
                        <a class="menu nav-link" id="actus-tab" href="#actus" data-bs-toggle="tab" data-bs-target="#actus-tab-pane" type="button" role="tab" aria-controls="actus-tab-pane" aria-selected="false">Actus</a>
                    </li>
					<li class="nav-item ms-3" role="presentation">
                        <a class="menu nav-link" id="faq-tab" href="#faq" data-bs-toggle="tab" data-bs-target="#faq-tab-pane" type="button" role="tab" aria-controls="faq-tab-pane" aria-selected="false">FAQ</a>
                    </li>
                </ul>

                <?php
                if ($show_profils_selector) { ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="data/<?php echo $profil ?>/logo-profil.png" alt="logo du profil <?php echo $profil ?>" height="80">
                            </a>

                            <ul class="dropdown-menu">
                                <li>Choix du profil</li>
                                <?php
                                    foreach ($profils as $available_profil) {
                                        echo '<li><a class="dropdown-item';
                                        if ($profil == $available_profil) {
                                            echo " active";
                                        }
                                        echo '" href="?profil='.$available_profil.'"';
                                        echo '>'.$available_profil.'</a></li>';
                                    }
                                    ?>
                            </ul>
                        </li>
                    </ul>
                <?php
                }
                else { ?>
                    <img src="data/<?php echo $profil ?>/logo-profil.png" alt="logo du profil <?php echo $profil ?>" class="navbar-nav ms-auto"  height="80">
                <?php
                    } ?>


            </div>
         </div>
    </nav>

    <!-- Pages (Mode d'emploi, Vérif' Devis, contact, actus) ---------------------------------------------------------->
    <div class="container tab-content px-sm-4 bg-white" id="myTabContent">
        <!-- Mode d'emploi (ex Documentation) ----------------------------------------------->
        <div class="d-print-none tab-pane fade show active" id="doc-tab-pane" role="tabpanel" aria-labelledby="doc-tab" tabindex="0">
            <div class="d-print-none py-3 doc">
                <?php
                $data = file_get_contents("data/doc.md");
                echo $Parsedown->text($data);
                ?>
            </div>
        </div>
        <!-- Vérif' Devis (ex "Application") ------------------------------------------------->
        <div class="tab-pane fade" id="app-tab-pane" role="tabpanel" aria-labelledby="app-tab" tabindex="0">
            <?php include 'php/app.php'; ?>
        </div>

        <!-- Contact ----------------------------------------------------->
        <div class="d-print-none tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
            <?php include 'php/contact.php'; ?>
        </div>

        <!-- Actus ------------------------------------------------------->
        <div class="tab-pane fade" id="actus-tab-pane" role="tabpanel" aria-labelledby="actus-tab" tabindex="0">
            <div class="d-print-none py-3 doc">
                <?php
                $data = file_get_contents("data/actus.md");
                echo $Parsedown->text($data);
                ?>
            </div>
        </div>

        <!-- FAQ ------------------------------------------------------->
        <div class="tab-pane fade" id="faq-tab-pane" role="tabpanel" aria-labelledby="faq-tab" tabindex="0">
            <div class="d-print-none py-3 doc">
                <?php
                $data = file_get_contents("data/faq.md");
                echo $Parsedown->text($data);
                ?>
            </div>
        </div>		
    </div>


    <!-- Footer and scripts ------------------------------------------------------------------------------------------->
    <?php include 'php/footer.php'; ?>

    <script>
            // Load data from config files
            <?php
                $data_posts = file_get_contents('data/'.$profil.'/postes.yaml');
                $data_commun = file_get_contents('data/commun.yaml');
            ?>
            var dataPostsYAML = <?php echo json_encode($data_posts); ?>;
            var dataCommunYAML = <?php echo json_encode($data_commun); ?>;
    </script>
    <script src="./index.js"></script>

    <script>
        addEventToStats("visite");
        addEventToStats("visite_<?php echo $profil; ?>");
    </script>
</body>
</html>

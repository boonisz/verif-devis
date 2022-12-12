<!DOCTYPE HTML>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="data/favicon.png" />
    <title>Admin outil devis</title>

    <!-- Add bootstrap lib -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">

    <!-- Add JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Add highlight lib: code rendering-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.0/styles/default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.0/highlight.min.js"></script>

    <!-- Ajoute de la librairie ACE : pour afficher un jolie éditeur de fichier -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.7.1/ace.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.7.1/ext-modelist.js"></script>

    <!-- Custom style -->
    <link href="styles.css" rel="stylesheet" />
</head>

<?php
include 'php/params.php';

$current_file = $config_dir.(isset($_GET['file']) ? $_GET['file'] : "commun.yaml");

create_data_zip($config_dir, $zip_file);


// =================================================================================
//              Zip all data files, to download it
// =================================================================================
function create_data_zip($config_dir, $zip_file) {
    // Get real path for our folder
    $rootPath = realpath($config_dir);

    // Initialize archive object
    $zip = new ZipArchive();
    $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    // Create recursive directory iterator
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

foreach ($files as $name => $file)
    {
        // Skip directories (they would be added automatically)
        if (!$file->isDir())
        {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
        }
    }

    // Zip archive will be created only after closing object
    $zip->close();
}


// =================================================================================
//              List all editables files, include the profils' files
// =================================================================================
function get_editable_files($dir) {
    return array_merge(glob($dir.'*.yaml'), glob($dir.'*.md'), glob($dir.'*.css'));
}

function populate_file_selector($config_dir, $current_file) {
    $files = get_editable_files($config_dir);
    foreach ($files as $file) {
        echo '<option value="'.$file.'" ';
        echo 'class="page_selector" ';
        if ($file == $current_file) {
            echo ' selected';
        }
        echo '>'.str_replace($config_dir, "", $file).'</option>';
    }

    $profils = glob($config_dir.'*',  GLOB_ONLYDIR);
    foreach ($profils as $profil) {
        $profil_dir = $profil."/";
        $files = get_editable_files($profil_dir);
        foreach ($files as $file) {
            echo '<option value="'.$file.'" ';
            echo 'class="page_selector" ';
            if ($file == $current_file) {
                echo ' selected';
            }
            echo '>'.str_replace($config_dir, "", $file).'</option>';
        }
    }
}

// =================================================================================
//              Modification du fichier de configuration
// =================================================================================
if (isset($_POST['newd'])) {
    $newdata = $_POST['newd'];
    file_put_contents($current_file, $newdata);
    // Update page
    echo '<script>location = location</script> ';
}
// =================================================================================
?>


<body class="d-flex flex-column bg-light d-print-block min-vh-100">
    <!-- Menu --------------------------------------------------------------------------------------------------------->
    <nav class="menu container navbar navbar-expand-lg bg-app-color">
        <div class="container-fluid">
            <span class="navbar-brand me-5">Admin</span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="nav navbar-nav" id="myTab" role="tablist">
                    <li class="nav-item ms-3" role="presentation">
                        <a class="menu nav-link" id="doc-tab" href="#doc" data-bs-toggle="tab" data-bs-target="#doc-tab-pane" type="button" role="tab" aria-controls="doc-tab-pane" aria-selected="true">Documentation</a>
                    </li>
                    <li class="nav-item ms-3" role="presentation">
                        <a class="menu nav-link" id="edit-tab" href="#edit" data-bs-toggle="tab" data-bs-target="#edit-tab-pane" type="button" role="tab" aria-controls="edit-tab-pane" aria-selected="true">Éditeur</a>
                    </li>
                    <li class="nav-item ms-3" role="presentation">
                        <a class="menu nav-link" id="stats-tab" href="#stats" data-bs-toggle="tab" data-bs-target="#stats-tab-pane" type="button" role="tab" aria-controls="stats-tab-pane" aria-selected="true">Monitoring</a>
                    </li>
                    <li class="nav-item dropdown ms-3">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Retour à l'outil
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                                foreach ($profils as $available_profil) {
                                    echo '<li><a class="dropdown-item';
                                    if ($profil == $available_profil) {
                                        echo " active";
                                    }
                                    echo '" href="index.php?profil='.$available_profil.'" target="_blank">';
                                    echo 'Profil '.$available_profil;
                                    echo ' <img src="data/'.$available_profil.'/logo-profil.png" alt="logo du profil '.$available_profil.'" height="20">';
                                    echo '</a></li>';
                                }
                            ?>
                        </ul>
                    </li>
                </ul>
                <img src="data/<?php echo $profil ?>/logo-profil.png" class="navbar-nav ms-auto" alt="logo du profil <?php echo $profil ?>" height="80">
            </div>
        </div>
    </nav>

    <!-- Pages (documentation, applications, contact, actus) ---------------------------------------------------------->
    <div class="container tab-content px-sm-4 bg-white" id="myTabContent">
        <!-- Éditeur ----------------------------------------------------->
        <div class="tab-pane fade" id="edit-tab-pane" role="tabpanel" aria-labelledby="edit-tab" tabindex="0">
            <form action="" method="post" onsubmit="save_file();">
                <div class="row mt-2">
                    <div class="col-auto px-1">
                        <select class="form-select" style="width:auto;">
                            <?php populate_file_selector($config_dir, $current_file); ?>
                        </select>
                    </div>
                    <div class="col-auto px-1">
                        <input class="btn btn-primary mb-3" type="submit" value="Enregister" id="save">
                    </div>
                    <div class="col-auto px-1">
                        <a class="btn btn-primary mb-3" href="<?php echo $zip_file ?>" download="<?php echo $zip_file_for_download ?>"><i class="bi bi-download"></i></a>
                    </div>
                </div>
                <div class="row">
                    <?php
                        // On test si le fichier existe
                        if (file_exists($current_file)) {
                            $fh = fopen($current_file, "r") or die("Unable to open file!<br>");
                            $data = file_get_contents($current_file);
                            ?>
                                <div id="editor"><?php echo $data?></div>
                                <textarea name="newd" id="newd" style="display:none;"></textarea>
                            <?php
                        }
                        else {
                            ?>
                            <div class="alert alert-danger" role="alert">
                            Attention, le fichier de configuration <code>'<?php echo $current_file?>'</code> est introuvable. <br />
                            Merci de sélectionner un fichier via le menu déroulant ci-dessus.
                            </div>
                            <?php
                        }

                    ?>
                </div>
            </form>
        </div>

        <!-- Documentation ----------------------------------------------->
        <div class="tab-pane fade" id="doc-tab-pane" role="tabpanel" aria-labelledby="doc-tab" tabindex="0">
            <div class="py-3 doc">
                <?php
                require 'php/Parsedown.php';
                $Parsedown = new Parsedown();
                $data = file_get_contents("data/admin_doc.md");

                echo $Parsedown->text($data);
                ?>
            </div>
            <script>hljs.highlightAll();</script>
        </div>

        <!-- Monitoring visits ------------------------------------------->
        <div class="tab-pane fade" id="stats-tab-pane" role="tabpanel" aria-labelledby="stats-tab" tabindex="0">
            <div class="py-3">
                <?php include 'stats.php'; ?>
            </div>
        </div>
    </div>

    <?php include 'php/footer.php'; ?>

</body>
<script>
    // Save file using enregister button ------------------------------------------------------------
    function save_file() {
        addEventToStats("MAJ_config");
        var editor = ace.edit("editor");
        document.getElementById("newd").value = editor.getValue();
        return true;
    }

    // Change file using selector -------------------------------------------------------------------
    function changePage(e) {
        selectedFile = e.target.value.replace(<?php echo '"'.$config_dir.'"'; ?>, "");
        window.location = "admin.php?file=" + selectedFile + "#edit";
    }
    page_selectors = document.getElementsByClassName("page_selector");
    for (var i = 0; i < page_selectors.length; i++) {
        page_selectors[i].addEventListener('click', changePage, false);
    }


    // Formatting textarea with nice ACE YAML colors ------------------------------------------------
    // This next lines permits to show HTML tag: https://github.com/ajaxorg/ace/issues/519
    el = document.getElementById("editor");
    text = el.innerHTML;
    editor = ace.edit(el);
    editor.session.setValue(text);

    // detects file extension
    let aceModeList = ace.require("ace/ext/modelist");
    let mode = aceModeList.getModeForPath("<?php echo $current_file ?>");
    try {
        editor.session.setMode(mode.mode);
    } catch (e) {
        console.log("Ace: No specific mode available for file extension");
    }

    editor.setShowPrintMargin(false); // Not vertical bar at 80 chars
    editor.session.setUseWrapMode(true);

    // ===========================================================================
    // URL management
    // ===========================================================================
    // Add anchor to URL and automatically select tabs (#doc, #app, #contact...)
    $(document).ready(() => {
        // If anchor exists, select it, otherwise use #doc anchor
        let anchor = location.href.split("#")[1] ? location.href.split("#")[1] : "doc"

        // Update URL with anchor
        updateURLWithAnchor("#" + anchor);

        // When select another page, update URL
        $('.doc a, a.menu').on("click", function() {
            const anchor = $(this).attr("href");
            updateURLWithAnchor(anchor);
        });
    });

    function updateURLWithAnchor(anchor) {
        if(anchor.startsWith("http") || anchor.startsWith("index")) {  // if external link do not touch URL
            return;
        }
        console.log(anchor);

        // Select page
        $('#myTab a[href="'+anchor+'"]').tab("show");

        // URL is something like : ROOT?PARAMETERS#ANCHOR   (toto.com/index.php?profil=murmur#doc)
        let urlRootParameters = location.href.split("#")[0];  // Remove anchor

        let parameters = urlRootParameters.split("?")[1];  // Isolate parameters
        let root = urlRootParameters.split("?")[0];  // Remove parameters

        parameters = parameters ? "?" + parameters : "";

        // Update URL
        url = root + parameters + anchor;
        history.replaceState(null, null, url);
    }

    function addEventToStats(name) {
        $.ajax({
            contentType: "application/json",
            type: 'GET',
            dataType:"jsonp",
            url: './stats.php?event=' + name,
            success: function (data, status, xhr) {
                console.log('SUCCESS data: ', data);
            },
            error: function (data, status, xhr) {
                console.log('ERROR data: ', data);
                console.log('ERROR status: ', status);
                console.log('ERROR xhr: ', xhr);
            },
            dataType: "html"
        });
    }
</script>
</html>

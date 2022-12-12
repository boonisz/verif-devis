<div class="d-print-none row justify-content-center my-5">
    <div class="col-12 col-md-8 col-lg-6 pb-5">

        <!---------------------------------------------------------------------------------------
        // Send mail if form already sent
        // ------------------------------------------------------------------------------------->
        <?php
            // Those line must be in global scope of a file
            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;

            if (isset($_POST["name"])) {
                require 'php/PHPMailer/src/PHPMailer.php';
                require 'php/PHPMailer/src/Exception.php';
                require 'php/PHPMailer/src/OAuthTokenProvider.php';
                require 'php/PHPMailer/src/OAuth.php';
                require 'php/PHPMailer/src/SMTP.php';

                $name = htmlspecialchars($_POST["name"])?: "no name";
                $mail = htmlspecialchars($_POST["mail"])?: "no mail";
                $message = htmlspecialchars($_POST["message"])?: "no message";
                $uploaded_file = $_FILES['uploaded_file'];

                $email = new PHPMailer();
                $email->SetFrom($mail, $name); //Name is optional
                $email->Subject   = "[Outil-devis] Demande ".$name;
                $email->Body      = $message;
                $email->AddAddress($mail_for_contact);

                // Add atachment
                if (isset($uploaded_file) && $uploaded_file['error'] == UPLOAD_ERR_OK) {
                    $email->AddAttachment($uploaded_file['tmp_name'], $uploaded_file['name']);
                }

                // Send mail
                $success = $email->send();

                // Popup success or error
                if ($success) {
                    $popup_text = "Le courriel est bien parti.</br>";
                    $popup_text .= "Merci de participer à l'amélioration de l'outil, nous nous efforçons de vous apporter une réponse dans les plus brefs délais. :)<br/><br/>";
                    $popup_type = "alert-success";
                }
                else {
                    $popup_text = "Un erreur s'est produite durant l'envoie du courriel...<br/><br/>";
                    $popup_type = "alert-danger";
                }
                $popup_text .= "<b>Information sur le courriel :</b><br/>";
                $popup_text .= "   Name: ".$name."<br>";
                $popup_text .= "   Email: ".$mail."<br>";
                $popup_text .= "   Fichier: '".$uploaded_file['name']."'<br>";
                $popup_text .= "   Message: ".$message."<br>";

                ?>
                    <div class="alert <?php echo $popup_type; ?> alert-dismissible fade show" role="alert">
                        <?php echo $popup_text; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
            }
        ?>

        <!---------------------------------------------------------------------------------------
        // Display form
        // ------------------------------------------------------------------------------------->
        <form action="#contact" method="post" class="needs-validation"  enctype="multipart/form-data" novalidate>
            <div class="card border-primary">
                <div class="card-header bg-info">
                    <div class="text-center py-2">
                        <h2><i class="bi bi-envelope"></i> Formulaire de contact</h2>
                        <p class="m-0">
                            Vous avez découvert un bug ?<br/>
                            Vous avez eu un blocage lors de l'instruction d'un dossier ?<br />
                            Vous avez des questions sur les points de vérifications ?<br />
                            Faites nous en part de manière à ce que nous améliorions cet outil<br/></p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="name_id"><i class="bi bi-person-fill"></i></span>
                        <input type="text" class="form-control" placeholder="Votre nom" name="name" aria-label="name" aria-describedby="name_id" required>
                        <div class="invalid-feedback">
                            Merci d'indiquer votre nom.
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="mail_id">@</span>
                        <input type="mail" type="text" class="form-control" placeholder="Votre mail" name="mail" aria-label="mail" aria-describedby="mail_id">
                    </div>
                    <label for="basic-url" class="form-label">Votre message pour expliquer les détails (contexte, raison du blocage) :</label>
                    <div class="input-group mb-3">
                        <textarea type="text" class="form-control" name="message" aria-label="message" aria-describedby="basic-addon1" rows="5" required></textarea>
                        <div class="invalid-feedback">
                            Merci de compléter votre message, que nous puissions comprendre le problème.
                        </div>
                    </div>

                    Pour un dossier recalé, merci de nous joindre le devis concerné. Pour tout autre demande, n'hésitez pas à ajouter un fichier si besoin :
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="file_id"><i class="bi bi-cloud-upload"></i></span>
                        <input type="file" class="form-control" name="uploaded_file" multiple>
                    </div>
                    <button type="submit" class="btn btn-info float-end"><i class="bi bi-send"></i> Envoyer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!---------------------------------------------------------------------------------------
// Field checker with error message
// ------------------------------------------------------------------------------------->
    <script>
        (() => {
          'use strict'

          // Fetch all the forms we want to apply custom Bootstrap validation styles to
          const forms = document.querySelectorAll('.needs-validation')

          // Loop over them and prevent submission
          Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
              if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
              }

              form.classList.add('was-validated')
            }, false)
          })
        })()
    </script>

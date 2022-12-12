## Des devis conformes aux exigences des aides financières

Vous êtes arrivés ici, bravo :) et bienvenu !

Que cherchez-vous ?

- [Voir la version de l'EIE 38](https://www.infoenergie38.org/devis/index.php)
- [Avoir plus de détails sur l'application Verif' Devis](#app)
- [Contribuer à ce projet](#contrib), en reportant un bug, proposant une nouvelle fonctionnalité
- [Installer l'application pour ma structure](#install)
- [Regarder un cours sur l'électricité](https://www.youtube.com/watch?v=ienoSbONyhw) 😉


<p style="text-align:center">
<img src="img/application.png"
     alt="Capture d'écran de l'application" width="50%"/>
</p>

L'application Vérif'Devis <a name="app"></a>
============================
C'est une application web qui permet de vérifier rapidement et simplement un devis pour une demande aide financière (Ma Prime Rénov', CEE ou encore d'une aide locale).

Elle peut être utilisée par plusieurs publics :

- des conseillers énergies, pour vérifier le⋅s devis d'un client et générer un rapport PDF comme retour (possibilité de l'utiliser sur un acte SARE A3)
- des pros de la rénovation, pour vérifier leurs modèles de devis et savoir pourquoi une mention est obligatoire
- des particuliers, pour vérifier eux-mêmes leur devis

Différents profils permettent d'avoir des contraintes réglementaires ainsi qu'un design et des logos différents selon le dispositif. Ainsi l'application et le PDF peuvent s'adapter aux différentes chartes graphiques de vos partenaires.

Une interface d'administration est accessible pour pouvoir facilement et simplement mettre à jour les contraintes règlementaires.

Enfin, pour toutes questions, support technique ou encore demande d'amélioration, n'hésitez pas à nous contacter : [verif-devis@caracals.org](mailto:verif-devis@caracals.org).

<p style="text-align:center">
Exemple de rapport PDF :<br/>
<img src="img/rapport.png"
     alt="L'application peut générer un rapport au format PDF" width="50%"/>
</p>

Cette application a été développée par l'AGEDEN38 et la SPL ALEC 38.


Comment contribuer ? <a name="contrib"></a>
====================
Vous avez un bug, une proposition d'amélioration, une erreur, des problèmes d'installation ou n'importe quelle autre demande ?

Soit, vous vous y connaissez en informatique et dans ce cas, ajoutez une issue ou même carrément un Pull Request sur Github.
Sinon, n'hésitez pas à nous contacter directement sur [verif-devis@caracals.org](mailto:verif-devis@caracals.org).


Installer l'application pour ma structure <a name="install"></a>
==============================

### Puis-je l'installer pour ma structure ?

Oui, bien sûr, les sources sont libres, sous la license CC-BY-NC-SA (pour plus d'info sur cette license [CLICK](https://creativecommons.org/licenses/by-nc-sa/2.0/fr/)).

Il faut juste avoir un serveur web avec les paquets zip et sendmail, pas besoin de base de données.

Et si vous l'installez, faites-le-nous savoir pour qu'on puisse vous tenir informer des nouveautés : [verif-devis@caracals.org](mailto:verif-devis@caracals.org)

### Comment adapter le code à sa structure ?
À venir...

### Ça se passe comment pour les mises à jour ?

Cet outil nécessite deux types de mises à jour :

- les mises à jour réglementaires, pour suivre les évolutions des lois et des aides financières
- les mises à jour logicielles (le code de l'application).

Les mises à jour réglementaires sont faites, via une interface administrateur, par le⋅s référent⋅e⋅s de l'outil. Pas besoin d'avoir de grosses connaissances en informatique pour cette partie. Il suffit d'être suffisamment à l'aise avec un ordinateur, rigoureux et au courant des évolutions réglementaires.

Pour les mises à jour logicielles, c'est un peu plus technique. Grâce à un logiciel FTP style FileZila et avant toute chose, il faudra faire une sauvegarde du dossier `data`.
Ensuite pour les mises à jour mineures (version X.a à X.b), il faut simplement remplacer les fichiers (sauf ceux de `data`).
Pour les mises à jour majeures (version X.? à Y.?), il faudra alors bien lire la note de version pour ne pas rater une information importante.


Le coin des développeur·euse·s <a name="install"></a>
==============================

### Comment l'utiliser en local ?

**Sous Linux**, voici un exemple de commandes à lancer pour démarrer l'application localement.

    sudo apt install php-cli php-zip php-sqlite3 php-mbstring sendmail
    sudo php -S 127.0.0.1:80 -t .

Il faudra alors aller sur [127.0.0.1](127.0.0.1).

### Décisions de conception

- Par souci de simplicité, il a été choisi de ne pas avoir de mécanisme de sauvegarde d'une vérification en cours.
- Dans l'export en PDF, il n'y a pas de lien ancre entre les identifiants d'une annexe ([X]) et l'annexe correspondante, pour éviter un bug sur firefox ([Enable automatic URL hyperlinking #3172](https://github.com/mozilla/pdf.js/issues/3172)).

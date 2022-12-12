# Changelog
Tout changement notable dans ce projet sera listé dans ce fichier.

Le format est basé sur [Tenez un  Changelog](https://keepachangelog.com/fr/1.0.0/),
et ce projet suit une [gestion sémantique de version](https://semver.org/lang/fr/).


## [Unreleased]

## [1.2.0] - 2022-12-12
### Changement
- Nettoyage du dépôt pour libération de sources

## [1.1.0] - 2022-11-27
#### Ajout
- Ajout d'un 2e bouton d'impression en bas des formulaires (à droite, après liens utiles)
- Ajout d'une mode simple pour un profil (sans switch, ni commentaire) > ajout d'un fichier `simple` dans le dossier du profil

### Changement
- Mise en page de la section "Partie réservé aux conseiller..." (titre plus petit et cadre)

### Fix
- Résolution dans le module statistique qui ignorait la partie après un "-" d'un nom de profil


## [1.0.0] - 2022-08-26
#### Ajout
- Utilisation d'accordéon pour afficher les sections
- Possibilité de cacher une section (accordéon fermé avec `#` en début de titre)
- Amélioration des différentes docs
- EDITOR - ajout de deux fichiers en modification `onglet_général_après.md` et `onglet_général_avant.md`.


## [0.10.0] - 2022-08-24
#### Ajout
- VISITE – Ajout d'un compteur de visite
- ADMIN - Ajout de liens vers tous les profils dans la barre de navigation


## [0.9.0] - 2022-08-23
#### Ajout
- PROFIL - Ajoute styles.css par profil
- ACTUS - Ajout d'un fichier `data/actus.md` pour les actus de l'application (pas pour vocation d'être une veille technique)
- ACTUS - Ajout doc sur le Markdown
- PDF - Texte pour proposer au particulier de solliciter l’artisan pour faire des modifications + contact Pros de la Réno
- PDF - Ne pas afficher le titre/numéro/entreprise d'un devis si pas complété
- PDF – text par devis qui explique les * + retrait des fichiers YAML
- PDF : texte tapé en bleu et italique

### Changement
- PDF - NOM-PRENOM_Vérification-devis-PROFIL_ANNEE-MOIS-JOUR (Si pas info particulier : nom-particulier-non-renseigné_Vérification-devis-… )

### Suppression
- PROFIL - Suppression liste déroulante pour le choix du profil, garder le profil par défaut et accès aux autres profils que via URL


## [0.8.0] - 2022-08-11
### Ajout
- PDF - un texte sur la première page pour expliquer qu’il y a des annexes 
- PDF - ajouter un sommaire en 1er page
- Mettre une bulle info sur un titre de partie (en ajoutant une contrainte `info : |`  ) 
- SWITCH - par chaque contrainte
- PROFIL – sélection du profil en cliquant sur l'image en haut à droite

### Changement
- CONTACT – envoie à prosdelareno@infoenergie38.org 
- ADMIN - Éditeur en une seule page, pour éviter rechargement quand on va sur la doc 
- ADMIN - Renommage du fichier d'administration : `edit.php` > `admin.php` 
- PDF – mise en page annexe plus condensé
- PDF – cacher les commentaires quand switch off
- Déplacement de la zone de commentaire par devis avant les URLs


## [0.7.0] - 2022-08-08
### Ajout
- PROFIL : première version des profils :
  - Réorganiser architecture (dossier data)
  - 2 profiles pour l'instant : EIE38, MurMur
  - 1 profile = postes.yaml, 3 logos (un de profil et 2 pour le pdf droite/gauche)
- PDF - texte de déresponsabilisation
- ADMIN - bouton télécharger les configs (avec timestamp dans titre archive)
- ADMIN - Possibilité de modifier la doc (la page d'accueil + doc admin)

### Changement
- Index en une seule page pour éviter rechargement quand on va sur la doc
- CONTACT - Renvoie du formulaire sur lui-même avec une bulle d'info (réussi ou échec)
- CONTACT - Modification du texte pour tous les cas (dossiers bloqués MPR/CEE, mais aussi bugs et questions)


## [0.6.0] - 2022-08-03
### Ajout
- Ajout de switch pour par section pour ne pas l'imprimer
- PDF - Titre avec une date + nom du particulier
- Ajouter zone de texte pour chaque devis : numéro du devis, nom de l'entreprise, titre (ITE fibre bois, variante...)
- Message d'avertissement lors du reset  "Attention ça va effacer tous les onglets..."

### Changement
- Blocs d'info plus largeurs
- Nouvelle ligne après choix du devis
- Pas de bouton d'impression en mobile
- Informations générales : nom et prénom + écriture inclusive

### Fix 
- Typo : "InformationS généralES"
- Correction du bug des commentaires tronqués sur la dernière ligne


## [0.5.0] - 2022-07-11
### Ajout
- ADMIN - Ajout espace administration pour modifier les données (lien caché)
- ADMIN - Ajout d'un validateur syntaxique
- ADMIN - Ajout d'URL avec label []()
- PDF - Mise en page de l'impression : 1 page par devis, image, nom, date
- Bouton remise à zéro
- Bouton impression
- Bouton ajout de devis
- Un onglet par devis
- Suppression de devis
- Mobile : ne pas afficher les commentaires de contrainte (les afficher sur tablette)
- Bulle d'info par contraintes

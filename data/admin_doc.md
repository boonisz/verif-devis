
# Configurer l'application de vérification de devis

Depuis cette interface administrateur, il est possible de modifier pas mal de chose sur le site.

La configuration des contraintes se fait via des fichiers `YAML`.
La configuration de la documentation et des actus se fait via des fichiers markdown `.md`.

Pour modifier ces fichiers, un éditeur en ligne est accessible sur l'onglet [Éditeur](admin.php#edit).


<div class="box box-danger center">
⚠️⚠️⚠️ Pensez à faire une sauvegarde avant de modifier un fichier de config, en utilisant l'icône <i class="bi bi-download"></i> ... 😉</b>
</div>

#### Sommaire de cette documentation
 - [Le dossier `data` et les profils (EIE38, MurMur)](#profils)
 - [La syntaxe des fichiers YAML (.yaml)](#syntaxe)
 - [Un example de code YAML](#example)
 - [La syntaxe des fichiers markdown (.md)](#markdown)




## Le dossier `data` et les profils (EIE38, MurMur)<span id="profils"></span>

L'application permet de gérer plusieurs profils différents.
Ces profils permettent de personnaliser les logos et les contraintes par poste, en fonction du territoire, du contrat ou autre.

Voici l'organisation du dossier `data`:
 - `admin_doc.md` : Documentation pour la partie administration (cette doc)
 - `doc.md` : Documentation pour l'utilisateur ([index.php#doc](index.php#doc))
- `onglet_général_avant.md` : Contenu avant le formulaire d'accueil de l'application ([index.php#app](index.php#app))
- `onglet_général_après.md` : Contenu après le formulaire d'accueil de l'application ([index.php#app](index.php#app))
- `commun.yaml` : le fichier contenant les contraintes communes à tous les postes
 - `favicon.png` : l'icône ✅, qui s'affiche dans l'onglet du navigateur
 - `actus.md` : avec les nouveautés de l'outil (onglet [Actus](index.php#actus))
 - À venir ? postes.yaml : un fichier avec les contraintes par poste, communes à tous les profils
 - **chaque dossier correspond à un profil** (`EIE38`, `EIE38-Pros`, `MurMur`) et contient :
   - `postes.yaml` : le fichier contenant les contraintes par poste
   - `logo-profil.png` : le logo principal du profil, affiché sur l'application en haut à droite
   - `logo-pdf-gauche.png` et `logo-pdf-droite.png` : pour affichage dans le PDF (un à gauche et un à droite)
   - `styles.css` : pour pouvoir personnaliser le style par profil


#### Comment ajouter ou modifier un profil ?
Pour ajouter un profil, il faut avoir un accès au serveur de fichier.
Puis dans le dossier `data`, copiez collez le dossier d'un profil existant et remplacez les logos (fichier `.png`) en gardant bien les noms.

Ensuite la modification des contraintes et du style pourra se faire directement via [l'éditeur en ligne](admin.php#edit).

## La syntaxe des fichiers YAML <span id="syntaxe"></span>

#### Rédaction du text
 - L'indentation se fait en utilisant la touche `tab` (ou `maj+tab` pour revenir en arrière).
 - Tout le texte entre `""`.
   - ️⚠️ Si un texte contient un `"`, il faudra le remplacer par un `'` par exemple. Si vous voulez vraiment mettre un `"`, il faudra l'échapper (ajouter un `\` devant) : `\"`
 - **Commentaire :** une ligne qui commence par `#` sera ignorée par l'outil. Cela permet d'ajouter un commentaire pour marquer les différentes parties ou expliquer une ligne en particulier.

#### Mise en forme
Pour les champs de validation et les bulles infos, il est possible d'ajouter de la mise en forme, de deux manières :
 - via des balises spécialement ajoutées à cet outil
  - [Lien hypertexte](https://www.infoenergie38.org/) : `[Lien hypertexte](https://infoenergie38.org/)`. Avec cette syntaxe (`[]` avec le texte affiché, suivi sans espace de `()` avec le lien url, le lien sera cliquable et ouvrira le page dans un nouvel onglet.
 - via des balises HTML
  - _italique_ : `<i>italique</i>`
  - **gras** : `<b>italique</b>`
  - nouvelle ligne : `<br>`
  - d'autres balises HTML peuvent être utilisé (`<h1>`, `<li>`, `<ul>`) mais à prendre avec précaution (surtout celle qui utilise des `"` pour les paramètres).

#### Les accordéons
Chaque section est représentée sous la forme d'un accordéon.
Par défaut, cet accordéon est ouvert. Pour le fermé, il faut ajouter un `#` en début de section.
```YAML
"Poste 1":
    "Section avec accordéon affiché":
        - "On me voit"
    "#Section avec accordéon fermé":
        - "On me voit que en cliquant sur le titre de section"
```


#### Bulle d'info <i class="bi bi-question-circle" style="margin: 10px;"></i>
Il est possible d'ajouter **une bulle d'info** :
 - **sur un titre de section** : en ajoutant une contrainte `info : |` (guillemet non nécessaire) suivie du texte d'information à la ligne.
 - **sur une contrainte** en ajoutant à la fin de la ligne `: |` suivie du texte d'information à la ligne.s

Pour les textes d'informations, ils peuvent être sur plusieurs lignes et contenir des balises HTML.
```YAML
"Isolation toiture rampants":
    "Information spécifique au poste":
        - "Intitulé des travaux : Isolation des rampants de toiture (combles aménagés ou aménageables)": |
            Texte d'info bulle
            Marche sur plusieurs lignes
            Peut <b>intégrer</b> des <i>balises HTML</i>
            Et même des liens hypertextes : https://infoenergie38.org
    "Recommandation":
        - info: |
            Ceci est une info bulle pour un titre de section (utilisation du mot clé 'info')
```

#### Section commentaires
Pour chaque poste, il est possible d'ajouter une section `commentaires`. Les éléments de cette section ne seront pas afficher sous le format de case à cocher. Ce mot clé ne nécessite pas forcément de `"` autour de lui.

## Example de fichier YAML <span id="example"></span>
```YAML
# ==================================
# Isolation toiture rampants
# ==================================
"Isolation toiture rampants":
    "Information spécifique au poste":
        - info: |
            Une bulle d'information pour le titre de section
        - "Intitulé des travaux : Isolation des rampants de toiture (combles aménagés ou aménageables)": |
            Text d'info bulle
            Marche sur plusieurs
            Peut <b>intégrer</b> des <i>balises HTML</i>
            Et même des liens hypertextes : https://infoenergie38.org
    "Recommandation":
        - "Isolation de 100 % de la toiture donnant sur le volume chauffé"
        - "Opter pour des matériaux bio-sourcés : Les isolants doivent être porteurs d’une évaluation technique reconnue de type certification ACERMI ou évaluation technique du CSTB : <br>- Composés à plus de 50 % de la matière de matériaux biosourcés <br>- Résistance thermique assurée au 2/3 par l’isolant biosourcé"
    commentaires:
        - "[ceci est un lien hypertexte](https://infoenergie38.org/wp-content/uploads/CHOIXDEVIS_Toiture_Int.pdf)"

# ==================================
# Isolation combles perdus
# ==================================
"Isolation combles perdus":
    "Information spécifique au poste":
        - "Intitulé des travaux : Isolation des combles perdus"
        - "Marque et référence de l’isolant"
    "Recommandation":
        - "Isolation de 100 % de la toiture donnant sur le volume chauffé"
```

## La syntaxe des fichiers md ou markdown <span id="markdown"></span>

Le markdown est un langage de balisage. Assez facile à lire et à écrire.
Les pages de documentation et d'actus utilisent cette syntaxe.

Voici en exemple différentes syntaxes markdown avec en dessous ce que ça donne une fois compilé.

```markdown
# Titre 1
## Titre 2
Un paragraphe se compose de plusieurs phrases qui peuvent être sur plusieurs lignes.

Pour faire un nouveau paragraphe, on ajoute une ligne entre deux groupes de texte.

Et si on veut ajouter juste un retour à la ligne, sans espace entre les deux lignes, on peut finir ça par deux espace  
et revenir à la ligne. Ainsi le programme comprendra qu'il faut faire juste un retour à la ligne :)

On peut mettre du **gras** ou de *l'italique* ou ***les deux à la fois***. On peut ajouter un format de `code`
pour un nom de fichier par exemple `Y:/par/ici/mon/fichier.pdf`.

On peut aussi ajouter des émojis 🚀🚀 ([click pour trouver des émojis](https://fr.piliapp.com/emoji/list/))


## Titre 2
### Titre 3
- des listes
- à puces...
  - sous liste 1
  - sous liste 2

1. Ou
2. avec
3. des numéros


Un trait horizontal (ne pas oublier de sauter des lignes avant et après)

---

Pour ajouter des images, il faut soit déposer une image sur le serveur, soit utiliser un lien vers une image déjà en ligne
(dans ce cas, vérifiez régulièrement que l'image existe toujours)  
![bonjour](https://c.tenor.com/dnc1gR83UQgAAAAM/hello-bonjour.gif)


Et on peut aussi mettre des balises HTML.

<div class="box box-warning center">
Comme cette boite d'avertissement
</div>	
```

# Titre 1
## Titre 2
Un paragraphe se compose de plusieurs phrases qui peuvent être sur plusieurs lignes.

Pour faire un nouveau paragraphe, on ajoute une ligne entre deux groupes de texte.

Et si on veut ajouter juste un retour à la ligne, sans espace entre les deux lignes, on peut finir ça par deux espace  
et revenir à la ligne. Ainsi le programme comprendra qu'il faut faire juste un retour à la ligne :)

On peut mettre du **gras** ou de *l'italique* ou ***les deux à la fois***. On peut ajouter un format de `code`
pour un nom de fichier par exemple `Y:/par/ici/mon/fichier.pdf`.

On peut aussi ajouter des émojis 🚀🚀 ([click pour trouver des émojis](https://fr.piliapp.com/emoji/list/))


## Titre 2
### Titre 3
- des listes
- à puces...
  - sous liste 1
  - sous liste 2

1. Ou
2. avec
3. des numéros


Un trait horizontal (ne pas oublier de sauter des lignes avant et après)

---

Pour ajouter des images, il faut soit déposer une image sur le serveur, soit utiliser un lien vers une image déjà en ligne
(dans ce cas, vérifiez régulièrement que l'image existe toujours)  
![bonjour](https://c.tenor.com/dnc1gR83UQgAAAAM/hello-bonjour.gif)


Et on peut aussi mettre des balises HTML.

<div class="box box-warning center">
Comme cette boite d'avertissement
</div>	

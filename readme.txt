Les informations concernant le groupes sont présentent dans le footer ainsi que le lien vers le dépôt GIT.


Voici quelque notes permettant de mieux comprendre le fonctionnement de l'application :

- Le bouton de modification d'un item s'affiche sur la page de l'item seulement si l'utilisateur est le createur de la liste où apparait l'item

- Un pseudo est considéré comme étant unique

- Concernant les différents comptes (compte créateur et compte participant) nous avons pas très bien compris pourquoi il y avait une distinction, nous avons donc fait qu'un suel type de compte et fait des méthode de vérification lorsque nous en avions besoin
pour différencier le créateur d'une liste d'un visiteur.

-Les listes ayant pour couleur jaune sont privee et sont bleu lorsqu'elles sont publique

- Les inputs contenant un mot de passe ne sont pas filtrer du fait qu'il sont ensuite crypté.

- Les listes proposée par défaut n'ont pas de token autre que celui proposé par défaut.

- Les listes sont modifiable via un token via leur page, en effet, il faut utilisez le token pour visualiser la page de la liste puis cliquer sur modifier la liste pour la modifier, le token  n'emméne pas directement à la page de modification

- L'ajout d'image via hot linking n'a pas été traité, en effet nous avions pas très bien compris le fonctionnement, cependant, l'ajout/suppression d'images à un item est possible, pour cela nous affichons les images disponible et l'utilisateur coche
les images qu'il souhaite ajouter à l'item, il peut aussi uploader des images.

- Concernant les images des items nous avons différencié l'image principale (nom de l'image dans la table item) et les images secondaire (nom de l'image dans la table Image) d'un item.

- Le token d'une liste est visible sur la page de la liste (avec ces items) seulement si l'internaute est connecté et que la liste lui appartient.

- Le nom des participant ayant réservé un item ne sont pas afficher au créateur avant la date d'expiration de la liste.

- Les messages des réservations sont affichés sur la page de l'item correspondant lorsque la liste à expirée seulement. 

Lorsqu'un utilisateur non connecté crée une liste puis se crée un compte, alors les listes crées son ajoutées a son compte au moment de l'inscription.

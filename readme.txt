Les informations concernant le groupes sont présentent dans le footer ainsi que le lien vers le dépôt GIT.


Voici quelque notes permettant de mieux comprendre le fonctionnement de l'application :

- Le bouton de modification d'un item s'affiche sur la page de l'item seulement si l'utilisateur est le createur de la liste ou apparait l'item
- Un pseudo est considéré comme étant unique
- Concernant les différents comptes (compte créateur et compte participant) nous avons pas très bien compris pourquoi il y avait une distinction, nous avons donc fait qu'un suel type de compte et fait des méthode de vérification lorsque nous en avions besoin
pour différencier le créateur d'une liste d'un visiteur.
-Les listes ayant pour couleur jaune sont privee et sont bleu lorsqu'elles sont publique
- Les inputs contenant un mot de passe ne sont pas filtrer du fait qu'il sont ensuite crypté.
- Les listes proposée par défaut n'ont pas de token.


Lorsqu'un utilisateur non connecté crée une liste puis se crée un compte, alors les listes crées son ajoutées a son compte au moment de l'inscription.

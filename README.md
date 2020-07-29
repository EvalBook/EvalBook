# EvalBook

EvalBook est un **outil libre et totalement gratuit** a destination du monde de l'enseignement, son principal but est de faciliter l'encodage des notes
relatives aux activités réalisées en classe, mais les possibilités ne s'arrêtent pas la.

## Avec cet outil, vous pourrez :
* Créer des écoles
* Créer des implantations ( bâtiments ) et leur attribuer une école
* Créer des classes et leur attribuer une implantation
* Créer des utilisateurs et leur assigner une / des classes, ainsi que des rôles d'utilisation
* Créer des étudiants et leur assigner une / des classes
* Créer des contacts normaux et médicaux pour les assigner aux élèves
* Créer des activités et assigner des notes aux élèves de vos classes, les notes ne sont éditables que par le propriétaire de l'activité, seul les utilisateurs d'une classe peuvent voir les notes attribuées.
* ... beaucoup d'autres fonctionnalités à venir, l'outil est en cours de développement.

## Installation automatique
Pour l'installation automatique, assurez vous d'avoir installé php >= 7.4, ainsi que les extentions ext-xml et ext-mbstring.
* Téléchargez une version d'EvalBook.
* Placez le dossier extrait à l'emplacement voulu sur votre serveur.
* Placez vous dans le répertoire extrait.
* Linux seulement: Installez la derniere version de NPM.
* Lancez la commande: php install.php ( php install.php dev => installation sans suppression de composer et node ).
* Suivez les instructions.

## Installation manuelle
L'installation requiert :
* **NPM** ( https://www.npmjs.com/get-npm )
* **Composer**, ( https://getcomposer.org/download )
* **Une base de données SQL** ( testé sous MySql )
* Un serveur et éventuellement un nom de domaine ( peut être utilisé en local )
* Un serveur mail pour l'envoi des mails aux utilisateurs ( peut être utilisé avec sendmail en local )
* **PHP 7.4** et les librairies de base

### Récupération des sources et dépendances
1) Téléchargez l'archive souhaitée dans la partie releases
2) Décompressez cette archive à un endroit accessible de votre serveur
3) Installez NPM et Composer

### Préparation 
Créez une base de données et renseignez les informations comme suit dans le **fichier .env** du dossier EvalBook, ou `login` 
est le login de votre base de données, `password` est le password de votre base de donnée, `localhost` est votre serveur de base de données,
`port` est le port de connexion, et `evalbook_dev` est le nom de la base de données.

`DATABASE_URL=mysql://login:password@localhost:3306/evalbook_dev?serverVersion=5.7`

Entrez les informations de votre fournisseur email pour les envois de mails aux utilisateurs dans le **fichier .env**, ou `no-reply@evalbook.dev`
est l'adresse email émettrice des emails, `password` est le mot de passe de connexion à cette boite mail et `ssl0.ovh.net:465` sont l'adresse du serveur mail et le port.

`MAILER_DSN=smtp://no-reply@evalbook.dev:password@ssl0.ovh.net:465`

## Finalisation
Il s'agira ici d'installer les dépendances liées à l'outil, il vous faudra entrer deux commandes dans le terminal de votre serveur, veuillez
vous assurer de disposer des droits en écriture sur le dossier EvalBook.

`$ cd EvalBook`

`$ npm install`

`$ composer install`

## Sécurisation
Une fois ces étapes réalisées, connectez vous à EvalBook et changez les informations du super admin, vous pourrez effectuer
cette opération en vous connectant en tant qu'administrateur avec les informations de connection

Login : `admin@evalbook.dev`

Password: 'admin'

Ajoutez vos écoles, vos classes, vos utilisateurs et élèves, et vous êtes parti !

## Mise en place des données de base
Différents types de notes et activités types prédéfinis sont mis à votre disposition, libre à vous de les installer ou de ne pas les utiliser.

## Les rôles
En plus du rôle par défaut attribué, chaque utilisateur peut disposer de un ou plusieurs des rôles suivants : 
* **ROLE_ADMIN**: Le rôle admin peut absolument tout faire, à l'exception de l'édition de ses pairs et de l'ajout de notes et activités pour une classe dont il n'est pas utilisateur.
* **ROLE_USER** : Est le rôle par défaut de tout utilisateur enregistré, il permet de gérer l'ensemble des classes détenues par l'utilisateur ( + activités et notes ).
* **Lister toutes les écoles** : Vous permet de lister l'ensemble des écoles enregistrées dans le système.
* **Editer une école** : Vous permet d'éditer une école en modifiant les informations de nom et de siège principal.
* **Créer une école** : Vous permet de créer une nouvelle école.
* **Supprimer une école** : Vous permet de supprimer une école, attention, l'ensemble des données seront également supprimées.
* **Lister toutes les implantations** : Vous permet de lister l'ensemble des établissements disponibles dans le système.
* **Editer une implantation** : Vous permet d'étider les informations de base d'une implantation, le nom, l'adresse complète et les périodes.
* **Créer une implantation** : Créer une nouvelle implantation et lui assigner les informations de base comme l'adresse, le nom.
* **Supprimer une implantation** : Vous permet de supprimer une implantation, attention toutefois, toutes les données attachées seront supprimées, telles que les différentes périodes et activités, utilisez ce droit avec grande précaution, un retour en arrière n'est pas possible !
* **Lister tous les utilisateurs** : Lister tous les utilisateurs du système en un seul endroit, permet d'accéder à des utilisateurs qui n'ont pas encore de classe attitrée pour éventuellement effectuer des actions si vous en avez les droits.
* **Créer un utilisateur** : Permet de créer de nouveaux utilisateurs dans le système EvalBook.
* **Editer un utilisateur** : Permet de mettre à jour les informations d'un utilisateur à partir de la liste complète ou tout autre endroit du système, il permet également de gérer les rôles que vous souhaitez attribuer / retirer à l'utilisateur sélectionné.
* **Supprimer un utilisateur** : Permet de supprimer un utilisateur, ce droit est à utiliser avec grande précaution !
* **Lister tous les étudiants** : Permet d'afficher la liste complète des étudiants, y compris celles et ceux qui ne sont pas encore repris dans une classe, afin éventuellement d'affectuer des opérations de suppression ou de mise à jour.
* **Créer un étudiant** : Permet de créer un nouvel étudiant pour ensuite l'assigner à une classe si vous en avez le droit. Permet également la création d'un contact étudiant.
* **Editer un étudiant** : Permet de modifier les informations de base d'un étudiant à partie de la liste complète des étudiants ou à partir d'un autre endroit du système. Permet également l'ajout et la modification des contacts étudiants, y compris les contacts médicaux.
* **Supprimer un étudiant** : Permet de complètement supprimer un étudiant, attention, à utiliser avec précaution ! Permet également la suppression d'un contact étudiant. 
* **Lister toutes les classes** : Permet d'afficher le listing complet de toutes les classes de l'implantation cible ou de toutes les implantations.
* **Créer une classe** : Permet la création d'une classe et l'attribution au sein d'un établissement.
* **Editer une classe** : Permet l'édition de toutes les information de base d'une classe à partir de tout emplacement du système.
* **Supprimer une classe** : Permet la suppression pure et simple d'une classe, à utiliser avec précaution.
* **Assigner un élève à une classe** : Ce rôle permet à un utilisateur d'assigner un ou plusieurs élèves à une classe.
* **Assigner un utilisateur à une classe** : Ce rôle permet d'attribuer le droit d'actions à un ou plusieurs utilisateurs sur une classe précise.

## Support
Pour toute demande de support ou pour signaler un bug, envoyer nous une issue via github, un site internet est en cours de construction, mais
n'est actuellement pas encore disponible.

## Sponsors 
JetBrains a généreusement offert une licence Open Source a EvalBook, n'hésitez pas à faire un tour sur leur site !
[www.jetbrains.com](https://www.jetbrains.com/?from=EvalBook)
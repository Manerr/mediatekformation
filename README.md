# Mediatekformation
## Présentation
Ce site, développé avec Symfony 6.4, permet d'accéder aux vidéos d'auto-formation proposées par une chaîne de médiathèques et qui sont aussi accessibles sur YouTube.<br> 
Il y a deux partie: la partie back de gestion et la partie front publique: 

La partie administrateur contient ces fonctionnalités:

### Page "0" : le formulaire de connexion (accessible lorsque l'utilisateur n'est pas connecté)
Cette page permet de se connecter à la partie administrateur: 

![{499F0C68-CD9A-4AD3-AD6C-14A31A6A8612}](https://github.com/user-attachments/assets/3e2a08d8-859f-446b-9205-8440012507e7)


Une fois la connexion effectuée, on a accès au back-office (avec la possibilité de revenir sur la partie publique à tout moment): 
![{D4E18B7F-9A0C-49D0-97E5-2D4AC697F842}](https://github.com/user-attachments/assets/7f70582f-e87d-47ec-ac97-22f4b879125f)

### Pages supplémentaires par rapport à celles accessibles au public:

#### Gestion des catégories: 

![{5A471BCC-7604-49EE-BF70-BAAFA4EE70ED}](https://github.com/user-attachments/assets/ae4c2bc4-1d1d-49c2-9810-8ff50c7aed25)

#### Pages identiques, avec la possiblité de supprimer/modifier certaines données: 

![{8CD91268-CA97-4B7B-AA7D-BC10EF9B43B3}](https://github.com/user-attachments/assets/145f41db-48ae-4684-a512-df55b8eea7c4)
![{B439FA13-54D5-4BD8-A089-A9F9E13882A0}](https://github.com/user-attachments/assets/df9143c5-9f3a-4627-8c1a-dc7e082c1e1c)
![{43683C7E-55E4-4F01-BE12-862108AED922}](https://github.com/user-attachments/assets/768f1bd7-b4cc-43d6-8212-51cd267ccd0f)

![{9C2E3677-E5B5-446C-9298-6FAD3F3930D1}](https://github.com/user-attachments/assets/33d940f8-ec37-4893-9f33-774b3b236a01)
![{EB22C310-37D5-45A9-8213-D4806F5E1069}](https://github.com/user-attachments/assets/898b58d3-7637-46a9-8656-96adc31e845c)


## Partie publique


La partie publique contient les fonctionnalités globales suivantes :<br>
![img1](https://github.com/user-attachments/assets/9c5c503b-738d-40cf-ba53-36ba4c0209e8)



## Les différentes pages de la partie publique
Voici les 5 pages correspondant aux différents cas d’utilisation pour un utilisateur "classique"



### Page 1 : l'accueil
Cette page présente le fonctionnement du site et les 2 dernières vidéos mises en ligne.<br>
La partie du haut contient une bannière (logo, nom et phrase présentant le but du site) et le menu permettant d'accéder aux 3 pages principales (Accueil, Formations, Playlists).<br>
Le centre contient un texte de présentation avec, entre autres, les liens pour accéder aux 2 autres pages principales.<br>
La partie basse contient les 2 dernières formations mises en ligne. Cliquer sur une image permet d'accéder à la page 3 de présentation de la formation.<br>
Le bas de page contient un lien pour accéder à la page des CGU : ce lien est présent en bas de chaque page excepté la page des CGU.<br>
![image](https://github.com/user-attachments/assets/450a3e77-1feb-41c6-9bb5-0d8c7cec9b87)


### Page 2 : les formations
Cette page présente les formations proposées en ligne (accessibles sur YouTube).<br>
La partie haute est identique à la page d'accueil (bannière et menu).<br>
La partie centrale contient un tableau composé de 5 colonnes :<br>
•	La 1ère colonne ("formation") contient le titre de chaque formation.<br>
•	La 2ème colonne ("playlist") contient le nom de la playlist dans laquelle chaque formation se trouve.<br>
•	La 3ème colonne ("catégories") contient la ou les catégories concernées par chaque formation (langage…).<br>
•	La 4ème colonne ("date") contient la date de parution de chaque formation.<br>
•	LA 5ème contient la capture visible sur YouTube, pour chaque formation.<br>
Au niveau des colonnes "formation", "playlist" et "date", 2 boutons permettent de trier les lignes en ordre croissant ("<") ou décroissant (">").<br>
Au niveau des colonnes "formation" et "playlist", il est possible de filtrer les lignes en tapant un texte : seuls les lignes qui contiennent ce texte sont affichées. Si la zone est vide, le fait de cliquer sur "filtrer" permet de retrouver la liste complète.<br> 
Au niveau de la catégorie, la sélection d'une catégorie dans le combo permet d'afficher uniquement les formations qui ont cette catégorie. Le fait de sélectionner la ligne vide du combo permet d'afficher à nouveau toutes les formations.<br>
Par défaut la liste est triée sur la date par ordre décroissant (la formation la plus récente en premier).<br>
Le fait de cliquer sur une miniature permet d'accéder à la troisième page contenant le détail de la formation.<br>
![image](https://github.com/user-attachments/assets/234e75cd-6cbb-4191-a042-a21907f9a0d8)



### Page 3 : détail d'une formation
Cette page n'est pas accessible par le menu mais uniquement en cliquant sur une miniature dans la page "Formations" ou une image dans la page "Accueil".<br>
La partie haute est identique à la page d'accueil (bannière et menu).<br>
La partie centrale est séparée en 2 parties :<br>
•	La partie gauche contient la vidéo qui peut être directement visible dans le site ou sur YouTube.<br>
•	La partie droite contient la date de parution, le titre de la formation, le nom de la playlist, la liste des catégories et sa description détaillée.<br>
![{8E0A5B26-D298-48A2-825B-70A4BADF7B3A}](https://github.com/user-attachments/assets/15d4ee39-3d8b-4496-93e5-9b0d9704883f)

### Page 4 : les playlists
Cette page présente les playlists.<br>
La partie haute est identique à la page d'accueil (bannière et menu).<br>
La partie centrale contient un tableau composé de 3 colonnes :<br>
•	La 1ère colonne ("playlist") contient le nom de chaque playlist.<br>
•	La 2ème colonne ("catégories") contient la ou les catégories concernées par chaque playlist (langage…).<br>
•	La 3ème contient un bouton pour accéder à la page de présentation de la playlist.<br>
Au niveau de la colonne "playlist", 2 boutons permettent de trier les lignes en ordre croissant ("<") ou décroissant (">"). Il est aussi possible de filtrer les lignes en tapant un texte : seuls les lignes qui contiennent ce texte sont affichées. Si la zone est vide, le fait de cliquer sur "filtrer" permet de retrouver la liste complète.<br> 
Au niveau de la catégorie, la sélection d'une catégorie dans le combo permet d'afficher uniquement les playlists qui ont cette catégorie. Le fait de sélectionner la ligne vide du combo permet d'afficher à nouveau toutes les playlists.<br>
Par défaut la liste est triée sur le nom de la playlist.<br>
Cliquer sur le bouton "voir détail" d'une playlist permet d'accéder à la page 5 qui présente le détail de la playlist concernée.<br>
![{4D0109DC-24A5-4B73-BDC0-DF2B6A6AC6F7}](https://github.com/user-attachments/assets/e4ecdf5f-1913-4fc8-b400-f1478bfa031a)

### Page 5 : détail d'une playlist
Cette page n'est pas accessible par le menu mais uniquement en cliquant sur un bouton "voir détail" dans la page "Playlists".<br>
La partie haute est identique à la page d'accueil (bannière et menu).<br>
La partie centrale est séparée en 2 parties :<br>
•	La partie gauche contient les informations de la playlist (titre, liste des catégories, description).<br>
•	La partie droite contient la liste des formations contenues dans la playlist (miniature et titre) avec possibilité de cliquer sur une formation pour aller dans la page de la formation.<br>
![{77F7BDF7-17FB-4478-BC00-6C97BC1D0EB3}](https://github.com/user-attachments/assets/57ced5e9-dfb9-4f10-9410-f1ddbbb6c70e)

## La base de données
La base de données exploitée par le site est au format MySQL.
### Schéma conceptuel de données
Voici le schéma correspondant à la BDD.<br>
![img7](https://github.com/user-attachments/assets/f3eca694-bf96-4f6f-811e-9d11a7925e9e)
<br>video_id contient le code YouTube de la vidéo, qui permet ensuite de lancer la vidéo à l'adresse suivante :<br>
https://www.youtube.com/embed/<<<video_id>>>
### Relations issues du schéma
<code><strong>formation (id, published_at, title, video_id, description, playlist_id)</strong>
id : clé primaire
playlist_id : clé étrangère en ref. à id de playlist
<strong>playlist (id, name, description)</strong>
id : clé primaire
<strong>categorie (id, name)</strong>
id : clé primaire
<strong>formation_categorie (id_formation, id_categorie)</strong>
id_formation, id_categorie : clé primaire
id_formation : clé étrangère en ref. à id de formation
id_categorie : clé étrangère en ref. à id de categorie</code>

Remarques : 
Les clés primaires des entités sont en auto-incrémentation.<br>
Le chemin des images (des 2 tailles) n'est pas mémorisé dans la BDD car il peut être fabriqué de la façon suivante :<br>
"https://i.ytimg.com/vi/" suivi de, soit "/default.jpg" (pour la miniature), soit "/hqdefault.jpg" (pour l'image plus grande de la page d'accueil).
## Test de l'application en local
- Vérifier que Composer, Git et Wamserver (ou équivalent) sont installés sur l'ordinateur.
- Télécharger le code et le dézipper dans www de Wampserver (ou dossier équivalent) puis renommer le dossier en "mediatekformation".<br>
- Ouvrir une fenêtre de commandes en mode admin, se positionner dans le dossier du projet et taper "composer install" pour reconstituer le dossier vendor.<br>
- Dans phpMyAdmin, se connecter à MySQL en root sans mot de passe et créer la BDD 'mediatekformation'.<br>
- Récupérer le fichier mediatekformation.sql en racine du projet et l'utiliser pour remplir la BDD (si vous voulez mettre un login/pwd d'accès, il faut créer un utilisateur, lui donner les droits sur la BDD et il faut le préciser dans le fichier ".env" en racine du projet).<br>
- De préférence, ouvrir l'application dans un IDE professionnel. L'adresse pour la lancer est : http://localhost/mediatekformation/public/index.php<br>

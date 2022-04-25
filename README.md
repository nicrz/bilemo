# Installation

## Contexte
BileMo est une entreprise offrant toute une sélection de téléphones mobiles haut de gamme.

Vous êtes en charge du développement de la vitrine de téléphones mobiles de l’entreprise BileMo. Le business modèle de BileMo n’est pas de vendre directement ses produits sur le site web, mais de fournir à toutes les plateformes qui le souhaitent l’accès au catalogue via une API (Application Programming Interface). Il s’agit donc de vente exclusivement en B2B (business to business).

Il va falloir que vous exposiez un certain nombre d’API pour que les applications des autres plateformes web puissent effectuer des opérations.

Besoin client
Le premier client a enfin signé un contrat de partenariat avec BileMo ! C’est le branle-bas de combat pour répondre aux besoins de ce premier client qui va permettre de mettre en place l’ensemble des API et de les éprouver tout de suite.

 Après une réunion dense avec le client, il a été identifié un certain nombre d’informations. Il doit être possible de :

consulter la liste des produits BileMo ;
consulter les détails d’un produit BileMo ;
consulter la liste des utilisateurs inscrits liés à un client sur le site web ;
consulter le détail d’un utilisateur inscrit lié à un client ;
ajouter un nouvel utilisateur lié à un client ;
supprimer un utilisateur ajouté par un client.
Seuls les clients référencés peuvent accéder aux API. Les clients de l’API doivent être authentifiés via OAuth ou JWT.

Vous avez le choix entre mettre en place un serveur OAuth et y faire appel (en utilisant le FOSOAuthServerBundle), et utiliser Facebook, Google ou LinkedIn. Si vous décidez d’utiliser JWT, il vous faudra vérifier la validité du token ; l’usage d’une librairie est autorisé.

## Identifiants administrateur
- orange@gmail.com
- Test64170

## Configuration nécessaire
- Serveur web local ou en ligne
- Système de gestion de base de données relationnelle MySQL
- Installer Composer sur sa machine
- Logiciel Postman
- OpenSSL

## Instructions d'installation

##### 1/ Récupérez le projet github 
```
git clone https://github.com/nicrz/snowtricks
```
##### 2/ Créez une base de données dans PHPMyAdmin et importez-y le fichier bilemo.sql

##### 3/ Remplacez la ligne suivante de votre fichier .env avec les informations de connexions à la base de données que vous venez de créer.
```
DATABASE_URL=mysql://root:@127.0.0.1:3306/bilemo
```
##### 4/ Récupérez les dépendances du projet grâce à la commande suivante
```
composer install
```
##### 5/ Générez vos clés pour l'utilisation de JWT
```
    $ mkdir -p config/jwt
    $ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    $ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```
##### 5/ Renseignez les données d'accès à vos clés privées dans le fichier .env 
```
###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=VotrePassphrase
###< lexik/jwt-authentication-bundle ###
```
##### 6/ Votre API est prête à être utilisée, vous n'avez plus qu'à suivre les instructions de la documentation suivante pour utiliser votre API :
https://documentationapibilemo.000webhostapp.com/

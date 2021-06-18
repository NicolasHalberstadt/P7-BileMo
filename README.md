# P7-BileMo

Création d'une API permetttant la récupération d'un catalogue de produits et la gestion d'utilisateurs.

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/ce7fdb690e6e4323b213334dd6acb392)](https://www.codacy.com/gh/NicolasHalberstadt/P7-BileMo/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=NicolasHalberstadt/P7-BileMo&amp;utm_campaign=Badge_Grade)

## Installation

1. Clonez le repository GitHub dans le dossier voulu :

```
    git clone https://github.com/NicolasHalberstadt/P7-BileMo.git
```

2. Configurez vos variables d'environnement tel que la connexion à la base de données ou votre passphrase JWT dans le
   fichier `.env.local` qui devra être créé à la racine du projet en réalisant une copie du fichier `.env` :

```
cp .env .env.local
```

3. Téléchargez et installez les dépendances back-end du projet avec [Composer](https://getcomposer.org/download/) :

```
    composer install
```

4. Créez la base de données si elle n'existe pas déjà, tapez la commande ci-dessous en vous plaçant dans le répertoire
   du projet :

```
    php bin/console doctrine:database:create
```

5. Créez les différentes tables de la base de données en appliquant les migrations :

```
    php bin/console doctrine:migrations:migrate
```

6. Installez les 'fixtures' pour avoir un premier jeu de données :

```
php bin/console doctrine:fixtures:load
```

7. Lancez le serveur Symfony pour tester le projet localement :

```
symfony server:start
```

8. Après l'installation des fixtures, utilisez un de ces comptes pour obtenir un token et exploiter les données.

   | username  | password            |
          | --------- | ------------------- |
   | cdiscount | motdepassecdiscount |
   | rakuten   | motdepasserakuten   |
   | fnac      | motdepassefnac      |
   | admin     | motdepasseadmin     |

```
curl --location --request GET 'http://127.0.0.1:8000/login_check' \
--data-raw '{
    "username":"clientusername",
    "password":"clientpassword"
}'
```

9. Félicitations 🎉 le projet est installé correctement, vous pouvez désormais commencer à l'utiliser à votre guise en
   suivant la [Documentation](https://documenter.getpostman.com/view/15136161/TzY6Aa23) afin de consommer les données
   dans le role d'un client, pour utiliser le compte d'administrateur, voici
   la [Documentation](https://documenter.getpostman.com/view/15136161/TzeXm7nF) !👨‍💻
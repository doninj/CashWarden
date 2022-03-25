# webServiceProject
## API
### Technologies requises
- node v14
- php 8.0
- composer
- mariadb

### Initialisation de l'application
1. npm install
2. npm run dev
3. application accessible sur http://localhost:3000

### Initialisation de l'api
1. composer install 
2. composer update
3. php artisan migrate --seed
4. php artisan passport:install
5. php artisan serve

### Génération du swagger

`php artisan l5-swagger:generate`

Disponible sur `/api/documentation`

### Création d'un utilisateur

Faire une requête POST sur l'endpoint : `/api/register`.

#### Paramètres

- firstname : string
- lastname : string
- email : string
- password : string
- password_confirmation : string

### Choisir ça banque

Faire une requête GET sur l'endpoint : `/api/banks`.
Récupérer l'`id` d'une banque depuis la liste.

### Faire une demande de requisition

Faire une requête POST sur l'endpoint : `/api/requisition`.

Récuprer le lien `redirect` générer dans la réponse et autoriser l'application.

#### Paramètres

- bank_id : int

### Mettre à jour l'utilisateur

Récupérer les comptes associé à l'utilisateur
avec une requête GET sur l'endpoint : `/api/nordigen/accounts`.

Celle-ci renvoie un tableau d'`id` de compte. Choisissez en un.

Mettez à jour l'utilisateur avec une requête PUT sur l'endpoint : `/api/user`.

Avec comme paramètre `account: { name: string, id: int }`

Ou name est le nom du compte, mais n'a pas d'importance.
Et id est l'id du compte choisit.

### Récupérer les informations

Vous pouvez maintenant récupérer les informations bancaires de l'utilisateur.

- Liste par mois `api/transactionsPerMonth`
  - date : string pour le mois
  - annee : int pour l'année
- Liste complète avec pagination `api/transactions`

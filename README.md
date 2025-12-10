# Test ALTEN

Le test réalisé est le test pour développeur full-stack

## Partie Back-end

### Stack

- langage: **PHP**
- framework: **Symfony**
- webserver: **Nginx**
- base de données: **MySQL**

### Installation

L'application est designée pour fonctionner avec [docker](https://www.docker.com/).

Pour commencer vous devez build les images avec la commande suivante :

```sh
docker compose build
```

Puis lancer les containers avec la commande suivante:

```sh
docker compose up -d
```

L'application nécessite ensuite l'installation des dépendances via composer

```sh
docker exec -it symfony composer install --no-interaction --optimize-autoloader
```

Enfin une migration doit être executée pour deployer le schema de la base de donnée

```sh
docker exec -it symfony bin/console doctrine:migrations:migrate --no-interaction
```

L'application sera ensuite disponible à l'adresse http://localhost

### Utilisation

L'API est composée de plusieurs routes

#### Authentification

Les routes d'authtentification permettent de gérer la création de comptes et la connexion à ceux-ci

- [POST] /account -> Permet de créer un nouveau compte pour un utilisateur avec les informations fournies par la requête.

| method | route      | description                                                                                                                                                                | Payload et réponse  |
| ------ | ---------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------- |
| POST   | `/account` | Créer un utilisateur en base                                                                                                                                               | [account](#account) |
| POST   | `/token`   | Permet à un utilisateur de se connecter et générant un JWT, Le token doit ensuite être placé dans le header Authorization à chaque requête `Authorization Bearer ${token}` |

#### Gestion des produits

Les routes produits permettent la récupération et la gestion des produits, ces routes ne sont disponibles que pour les utilisateurs authentifiés.

| method | route            | description                       | Payload et réponse            | Admin (admin@admin.com) |
| ------ | ---------------- | --------------------------------- | ----------------------------- | ----- |
| GET    | `/products`      | Récupère la totalité des produits | [products](#products-findall) |       |
| GET    | `/products/{id}` | Récupère le produit d'ID {id}     | [products](#products)         |
| POST   | `/products`      | Créer un produit                  | [products](#products-create)  | x     |
| PATCH  | `/products/{id}` | Met à jour le produit d'ID {id}   | [products](#products-update)  | x     |
| DELETE | `/products/{id}` | Supprime le produit d'ID {id}     |                               | x     |

#### Gestion du panier

Les routes panier permettent la récupération et la gestion du panier de l'utilisateur connecté, ces routes ne sont disponibles que pour les utilisateurs authentifiés.

| method | route                          | description                                                                                      | Payload et réponse                          |
| ------ | ------------------------------ | ------------------------------------------------------------------------------------------------ | ------------------------------------------- |
| GET    | `/shopping-cart`               | Récupère le panier et ses produits                                                               | [ShoppingCart](#shoppingcart)               |
| POST   | `/shopping-cart/products`      | Ajoute un produit et sa quantité au panier                                                       | [ShoppingCart](#shoppingcart-addproduct)    |
| PATCH  | `/shopping-cart/products/{id}` | Met à jour la quantité d'un produit, l'ID correspond à celui de la table pivot et non du produit | [ShoppingCart](#shoppingcart-updateproduct) |
| DELETE | `/shopping-cart/products/{id}` | Supprime un produit du produit, l'ID correspond à celui de la table pivot et non du produit      |                                             |

#### Gestion de la liste de souhait

Les routes panier permettent la récupération et la gestion de la liste de souhait de l'utilisateur connecté, ces routes ne sont disponibles que pour les utilisateurs authentifiés.

| method | route                      | description                              | Payload et réponse               |
| ------ | -------------------------- | ---------------------------------------- | -------------------------------- |
| GET    | `/wish-list`               | Récupère le panier et ses produits       | [WishList](#wishlist)            |
| POST   | `/wish-list/products`      | Ajoute un produit à la liste de souhait  | [WishList](#wishlist-addproduct) |
| DELETE | `/wish-list/products/{id}` | Retire un produit de la liste de souhait |                                  |

### Payload et Réponses

#### Authentification

##### account

Payload:

```typescript
interface {
    username: string;
    email: string;
    firstname: string;
    password: string;
}
```

Réponse:

```typescript
interface {
    id: number;
    username: string;
    email: string;
    firstname: string;
}
```

##### token

payload:

```typescript
interface {
    email: string;
    password: string;
}
```

Réponse:

```typescript
interface {
    token: string;
}
```

#### Products

Le format des products retourné par l'API est toujours le même:

```typescript
interface Product {
  id: number;
  code: string;
  name: string;
  description: string;
  image: string;
  category: string;
  price: number;
  quantity: number;
  internalReference: string;
  shellId: number;
  inventoryStatus: "INSTOCK" | "LOWSTOCK" | "OUTOFSTOCK";
  rating: number;
  createdAt: number;
  updatedAt: number;
}
```

##### Products findAll

```typescript
type Products = Product[];
```

##### Products create

Payload attendu:

```typescript
interface StoreProduct {
  code: string;
  name: string;
  description: string;
  image: string;
  category: string;
  price: number;
  quantity: number;
  internalReference: string;
  shellId: number;
  inventoryStatus: "INSTOCK" | "LOWSTOCK" | "OUTOFSTOCK";
  rating: number;
}
```

[Réponse](#products)

##### Products update

Payload attendu:

```typescript
type UpdateProduct = Partial<StoreProduct>;
```

[Réponse](#products)

#### ShoppingCart

Le format des products retourné par l'API est toujours le même:

```typescript
interface ShoppingCart {
  id: number;
  products: {
    id: number;
    quantity: number;
    product: Product;
  }[];
}
```

##### ShoppingCart-addProduct

Payload attendu:

```typescript
interface {
    productId: number;
    quantity: number;
}
```

[Réponse](#shoppingcart)

##### ShoppingCart-updateProduct

Payload attendu:

```typescript
interface {
    quantity: number;
}
```

[Réponse](#shoppingcart)

#### WishList

Le format des products retourné par l'API est toujours le même:

```typescript
interface WishList {
  id: number;
  products: Product[];
}
```

##### WishList-addProduct

Payload attendu:

```typescript
interface {
    productId: number;
}
```

[Réponse](#wishlist)

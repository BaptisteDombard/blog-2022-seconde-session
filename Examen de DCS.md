# Examen de Développement Côté Serveur

*Juin 2022*

L’examen est constitué de deux tâches. La première est un échauffement réalisable en quelques minutes. La seconde, sans être beaucoup plus compliquée, est plus conséquente. 

## Flux RSS

Idéalement, l’application devrait permettre la diffusion RSS. Ici, il s’agit  *bêtement* d’afficher *tous* les articles dans un template XML conforme à la spécification RSS à chaque requête réalisée avec la *queryString* `?action=index&resource=rss`. 

```rss
<?xml version="1.0"?>
<rss version="2.0">
    <channel>
        <title>My awesome blog</title>
        <link>http://blog.test/</link>
        <description>The Awesome blog of Dominique Vilain</description>
        <language>fr-be</language>
        <item>
            <title>Un article</title>
            <link>http://blog.test/?action=show&amp;resource=post&amp;slug=un-article</link>
            <description>Un excellent articles sur comment écrire du RSS</description>
            <pubDate>Thu, 12 May 22 13:03:40 +0000</pubDate>
        </item>
    </channel>
</rss>
```

Les quatre infos du `channel` peuvent être complétées en dur dans le template. Les `items` correspondent aux articles. Attention, les `&` doivent être encodés `&amp;` et la `pubDate` doit être compatible avec le format RFC822. Carbon propose une méthode qui formate selon ce format. Dans la réponse HTTP précisez le `content-type` comme étant `application/rss+xml`

## Édition du profil de l’utilisateur connecté

L’utilisateur connecté doit pouvoir :

1. changer son adresse email
2. changer son mot de passe
3. changer son avatar

⚠️ *Seul un utilisateur connecté peut accéder aux routes concernées par la mise à jour du profil.* 

Après soumission du formulaire, les données de la requête doivent être *validées* puis traitées (par exemple, l’avatar doit être redimensionné). Une fois la mise à jour réalisée en base de données, l’utilisateur reste connecté, mais ses informations, présentes dans la session suite à sa connection, sont mises à jour dans celle-ci également.


Les validations à faire sont décrites ci-après. Comme nous l’avons fait pour l’ajout d’articles, elles doivent être faites dans un `trait` afin de ne pas compliquer inutilement le contrôleur qui doit seulement se concentrer sur son objectif, la mise à jour du profil.

1. Vérifiez que les **champs attendus sont bien présents**. Si il ne sont pas présents, il faut indiquer un message général : *Merci d’utiliser le formulaire sans le modifier*
2. S’il n’est pas vide, vérifiez que **l’email** est bien un email valide. Pour cela utilisez `filter_var` et créez le message : *Cette adresse email n’est pas valide* 
3. Si un **nouveau mot de passe** a été fourni :
   1. vérifiez que l’ancien mot de passe l’a été aussi. *Vous devez entrer l’ancien mot de passe pour pouvoir le changer*
   2. vérifiez que **l’ancien mot de passe** est bien celui de l’utilisateur connecté : *L’ancien mot de passe fourni ne correspond pas à celui présent dans la base de données*
   3. vérifiez qu’une **répétition** a bien été fournie. *Vous devez répéter le nouveau mot de passe pour pouvoir changer l’actuel*
   4. vérifiez qu’une **répétition correcte** a été fournie. *Vous n’avez pas correctement répété le nouveau mot de passe
   5. vérifiez (`preg_match`) qu’il contient **un chiffre**. *Le mot de passe doit contenir un chiffre*
   6. vérifiez (`preg_match`) qu’il contient **une capitale**. *Le mot de passe doit contenir une lettre capitale*
   7. vérifiez que sa taille est comprise entre 8 et 64. *Le mot de passe n’a pas la bonne taille*

4. Si un **fichier** a été posté : 
   1. vérifiez qu’il a un **type** png ou jpg ou jpeg. *Le type du fichier ne semble pas être celui d’une image*
      1. Si c’est bien une image du bon type, vérifiez que ses **dimensions** sont comprises entre 200 et 2000 pixels. Intervention Image peut vous aider ici. *Les dimensions de l’image ne sont pas comprises entre 200 et 2000 pixels*


Si la validation passe, vous devez naturellement réaliser les actions souhaitées dans le conrôleur adéquat :

1. hasher le nouveau mot de passe le cas échéant
2. redimensionner et  sauvegarder l’image le cas échéant
3. mettre à jour la base de données
4. mettre à jour la session

Que la validation passe ou pas, la suite est de réafficher le formulaire.

Utilisez git pour sauvegarder les évolutions de votre travail sur votre machine, **mais ne pushez rien vers github avant la fin de l’examen**, sous peine d’annulation de ce dernier.

Bon travail !
### Class diagram

@startuml
title Classes - Blog professionnel

class Utilisateur {
    -id : int
    -nom : string
    -prenom : string
    -email : string
    -motDePasse : string
}

class BlogPost {
    -id : int
    -titre : string
    -chapo : string
    -contenu : string
    -dateCreation : date
    -dateModification : date
    -author : Utilisateur
}

class Commentaire {
    -id : int
    -auteur : string
    -contenu : string
    -dateCreation : date
    -valide : bool
}

class BlogController {
    +afficherListeBlogPosts() : void
    +afficherBlogPost(idBlogPost:int) : void
    +ajouterBlogPost(blogPost:BlogPost) : bool
    +modifierBlogPost(idBlogPost:int, blogPost:BlogPost) : bool
    +supprimerBlogPost(idBlogPost:int) : bool
}

class CommentaireController {
    +ajouterCommentaire(idBlogPost:int, commentaire:Commentaire) : bool
    +validerCommentaire(idCommentaire:int) : bool
    +supprimerCommentaire(idCommentaire:int) : bool
}

class UtilisateurController {
    +inscription(utilisateur:Utilisateur) : bool
    +connexion(email:string, motDePasse:string) : bool
    +deconnexion() : bool
}

Utilisateur --> BlogPost
BlogPost --> Commentaire
BlogController --> BlogPost
CommentaireController --> Commentaire
UtilisateurController --> Utilisateur

@enduml

### Seq add comment

@startuml
title Ajout d'un commentaire sur un blog post

actor Utilisateur as U
participant "Page blog post" as BP
participant "Blog Post Controller" as BPC
participant "Commentaire Controller" as CC
database BlogDB as DB

U -> BP : Ouvre la page du blog post
BP -> BPC : Envoie une requête pour obtenir les informations du blog post
BPC -> DB : Effectue une requête pour obtenir les informations du blog post
DB -> BPC : Retourne les informations du blog post
BPC -> BP : Retourne les informations du blog post
U -> BP : Saisit le commentaire
BP -> CC : Envoie une requête pour ajouter le commentaire
CC -> DB : Effectue une requête pour ajouter le commentaire
DB -> CC : Retourne l'état de la requête
CC -> BP : Retourne l'état de la requête
@enduml

### Seq edit post

@startuml
actor Utilisateur
participant "Page d'administration" as PageAdmin
participant "Modification d'un blog post" as ModifBlog

Utilisateur -> PageAdmin : Se connecte à la page d'administration
PageAdmin -> ModifBlog : Accède à la page de modification d'un blog post
Utilisateur -> ModifBlog : Modifie les champs nécessaires
ModifBlog -> ModifBlog : Enregistre les modifications
PageAdmin <- ModifBlog : Affiche un message de confirmation de l'enregistrement des modifications
@enduml

### use case blog

@startuml
left to right direction
actor Utilisateur
actor Administrateur
rectangle Blog {
  Utilisateur --> (Accéder à la page d'accueil)
  Utilisateur --> (Accéder à la liste des blog posts)
  Utilisateur --> (Accéder à un blog post)
  Utilisateur --> (Ajouter un commentaire)
  Administrateur --> (Accéder à la page d'administration)
  Administrateur --> (Ajouter un blog post)
  Administrateur --> (Modifier un blog post)
  Administrateur --> (Supprimer un blog post)
}
@enduml

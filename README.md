La société Nettoyage PROPAR est une société de nettoyage destinée aux pros et particuliers.
Dans l’application, il y aura trois types d’utilisateurs :

- L’expert (Administrateur), il peut ajouter du personnel, prendre 5 opérations à la fois, créer les identifiants du personnel, modifier le rôle du personnel et il peut aussi connaître le chiffre d’affaire de l’entreprise.
- Le senior, il peut prendre 3 opérations à la fois.
- L’apprenti, il ne peut prendre qu’une opération à la fois.

Une opération est gérée par une seule personne (l’un des 3 rôles aléatoire selon la disponibilité de tâches). Un même client peut demander plusieurs opérations à l’entreprise.

L’application est lancée sur une machine dans l’entreprise. En premier affichage, on peut afficher la liste des opérations en cours (assignés à chaque rôles, triées par ordre alphabétique), et la liste des opérations terminées (triées par ordre alphabétique aussi). Le personnel peut aussi se connecter à l’application avec un identifiant et un mot de passe.

Lorsque l’utilisateur s’est connecté, selon son rôle, un menu est proposé :

Connexion par un Expert :
- Créer un identifiant pour un employé
- Ajouter une opération
- Terminer une opération
- Lister ses opérations en cours
- Voir le chiffre d’affaires

Connexion par un Senior : 
- Ajouter une opération
- Terminer une opération
- Lister ses opérations en cours

Connexion par un Apprenti :
- Ajouter une opération
- Terminer une opération
- Lister ses opérations en cours

On peut terminer ou ajouter une opération à la fois. Une opération peut avoir l’un de ces 3 types : 
Grosse (10000€)
Moyenne (2500€) 
Petite manœuvre (1000€)

Lorsque l’utilisateur ajoute une opération, le programme demande : 
Le type de l’opération 
Le nom, prénom et l’adresse du client
Une description de l’opération

Faire un appel api externe vers le site du gouvernement pour la gestion des adresse client (utilisation de la technologie ajax).
https://api.gouv.fr/les-api/base-adresse-nationale

- MCD MLD à fournir
- Diagramme de classe
- Diagramme des appels (https://www.draw.io/)
- Le modèle MVC doit être respecter
- Symfony requested
- l’application doit générer des rapports en fichier PDF et l’envoyer par mail au client. () et doit-etre telechargeable

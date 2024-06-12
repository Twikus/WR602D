# WR602D-WebApp

## Todo for Docker :

- ```docker build . -f ./docker/Dockerfile``` (do it just one time -> don't do it if is already did in microservice)

- Replace app image id by your image in ```compose.yaml``` (in the 2 projects)

- ```docker network create``` (do it just one time -> don't do it if is already did in microservice)

- ```docker compose up -d```

## For start the project :

- ```composer i```

- ```npm run watch```

- ```php bin/console d:m:m```

- ```php bin/console d:f:l```

Enjoy 🎉

## Checklist de fonctionnalités

### Source code
- ✅ GIT
- ✅ GitFlow
- ✅ Conventionnal commits
- ✅ PHPUnit 

### Projets :
#### Routes publiques / sécurisées :

##### Publiques :
- ✅ Homepage : L'utilisateur connecté ou non, doit avoir accès à une page d'accueil présentant le service.
- ✅ Page de création de compte : L'utilisateur doit pouvoir créer un compte
- ✅ Page de connexion : L'utilisateur doit pouvoir se connecter

##### Sécurisées :
- ✅ Gestion des abonnements : L'utilisateur doit pouvoir changer, facilement, d'abonnement
- ✅ Historique : L'utilisateur doit pouvoir consulter l'historique des pdfs qu'il a généré
- ✅ Génération de PDFs
- ✅ Service
- ✅ Controle du nombre de PDFs déjà générés : L'utilisateur doit pouvoir générer autant de PDF que son abonnement lui autorise.

### Frontend TWIG 

### End to end Cypress :
- ✅ Connexion valide
- ✅ Connexion invalide
- ✅ Création de compte valide
- ✅ Création de compte invalide
- ✅ Génération de PDF

### CI / CD Github Actions : 
- ⏳ PSR (Créer mais ne fonctionne pas)
- ⏳ PHPUnit (Créer mais ne fonctionne pas)
- ⏳ Cypress (Créer mais ne fonctionne pas)

### Points bonus : Exercices supplémentaires
- ✅ En plus de la génération de PDF à télécharger, vous pouvez envoyer un mail avec le PDF en pièce jointe
- ✅ Ajouter un watermark aux pdfs générés pour les abonnements gratuits ou inférieurs à 10 générations, une image choisie ou un gros "GRATUIT" transparent par dessus le pdf original
- [ ] Créer une commande  "app:handle-queue" pour gérer la génération par lot : 
- [ ] Désactiver la génération immédiate depuis le controller
- [ ] Ajouter dans une pile, "queue", un nouvel item de tâche à réaliser
- [ ] Créer la commande pour récupérer tous ces derniers éléments ou les 10 derniers, et les générer par lot
- [ ] Exécuter cette tâche. toutes les 30 minutes
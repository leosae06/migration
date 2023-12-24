Feature: Consulter les informations du site en tant que visiteur

  Scenario: Afficher le feed Instagram sur la page d'accueil
    Given Je suis un visiteur
    When Je visite la page d'accueil du site
    Then Je devrais voir le feed Instagram incorporé

  Scenario: Faire défiler la page en tant que visiteur
    Given Je suis un visiteur
    When Je visite le site
    And Je fais défiler la page vers le bas
    Then Je devrais voir toutes les informations disponibles

  Scenario: Redirection en cliquant sur les menus en tant que visiteur
    Given Je suis un visiteur
    When Je clique sur le menu de navigation "Informations"
    Then Je devrais être redirigé vers la section des informations de la page

  Scenario: Accéder à la section des courses en tant que visiteur
    Given Je suis un visiteur
    When Je clique sur le menu de navigation "Courses"
    Then Je devrais voir la liste des cours disponibles
    And Chaque cours devrait afficher le titre, la description, et des liens pertinents

  Scenario: Visiter une page qui n'existe pas
    Given Je suis un visiteur
    When Je visite une page qui n'existe pas
    Then Je devrais recevoir une réponse avec un code d'erreur 404
describe('Formulaire d\'Inscription', () => {
  it('test 1 - inscription valide', () => {
    cy.visit('http://localhost:8080/register');

    // Entrer les informations d'inscription
    cy.get('#registration_form_email').type('test@valide.fr');
    cy.get('#registration_form_firstname').type('Valide');
    cy.get('#registration_form_lastname').type('Valide');
    cy.get('#registration_form_plainPassword').type('password');
    // selectionner le plan d'abonnement Free (5 PDFs)
    cy.get('#registration_form_subscriptionId').select('Free (5 PDFs)').should('have.value', 'free');

    // Soumettre le formulaire
    cy.get('button[type="submit"]').click();

    // Vérifier que l'inscription a réussi
    cy.contains('Mon Compte').should('exist');
  });

  it('test 2 - inscription invalide', () => {
    cy.visit('http://localhost:8080/register');

    // Entrer les informations d'inscription
    cy.get('#registration_form_email').type('john.doe@example.com');
    cy.get('#registration_form_firstname').type('John');
    cy.get('#registration_form_lastname').type('Doe');
    cy.get('#registration_form_plainPassword').type('mauvais password');

    // Soumettre le formulaire
    cy.get('button[type="submit"]').click();

    // Vérifier que l'inscription a échoué
    cy.contains('S\'inscrire').should('exist');
  });
});
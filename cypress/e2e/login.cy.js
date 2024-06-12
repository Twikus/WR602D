describe('Formulaire de Connexion', () => {
  it('test 1 - connexion OK', () => {
    cy.visit('http://localhost:8080/login');

    // Entrer le nom d'utilisateur et le mot de passe
    cy.get('#inputEmail').type('john.doe1@example.com');
    cy.get('#inputPassword').type('password');

    // Soumettre le formulaire
    cy.get('button[type="submit"]').click();

    // Vérifier que l'utilisateur est connecté
    cy.contains('Mon Compte').should('exist');
  });

  it('test 2 - connexion NOT OK', () => {
    cy.visit('http://localhost:8080/login');

    // Entrer le nom d'utilisateur et le mot de passe
    cy.get('#inputEmail').type('john.doe@example.com');
    cy.get('#inputPassword').type('mauvais password');

    // Soumettre le formulaire
    cy.get('button[type="submit"]').click();

    // Vérifier que l'utilisateur n'est pas connecté
    cy.contains('Invalid credentials.').should('exist');
  });
});
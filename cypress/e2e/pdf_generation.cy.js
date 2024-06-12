describe('Génération de PDF par URL', () => {
    it('test 1 - génération réussi', () => {
      cy.visit('http://localhost:8080/login');
  
      cy.get('#inputEmail').type('john.doe1@example.com');
      cy.get('#inputPassword').type('password');

      // Soumettre le formulaire
      cy.get('button[type="submit"]').click();

      // Vérifier que l'utilisateur est connecté
      cy.contains('Mon Compte').should('exist');

      // Générer un PDF
      cy.get('#url_to_pdf').type('https://www.google.com');
      
      // Soumettre le formulaire avec le button qui a l'id 'generate_pdf'
      cy.get('#generate_pdf').click();

      // Vérifier que la génération a réussi
      cy.contains('Mon Compte').should('exist');
    });
});
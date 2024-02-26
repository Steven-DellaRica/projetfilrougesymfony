describe("Like une video en tant qu'utilisateur", () => {

  it('visite la page video', () => {
    cy.visit('http://localhost:8000/videopage');
    cy.get('.login-button').click();
    cy.url('https://localhost:8000/login').should('include', 'login');
    cy.get('#inputUsername').type('steven2');
    cy.get('#inputPassword').type('adrar');
    cy.get('.btn-primary').click();
    cy.url('https://localhost:8000/videopage').should('include', 'videopage');
    cy.get('input.likeButton.off').each(($el) => {
      cy.wrap($el).click();
    });
  })

  it("se connecte en tant qu'utilisateur", () => {
    cy.visit('http://localhost:8000/videopage');
    cy.get('.login-button').click();
    cy.url('https://localhost:8000/login').should('include', 'login');
    cy.get('#inputUsername').type('steven2');
    cy.get('#inputPassword').type('adrar');
    cy.get('.btn-primary').click();
    cy.url('https://localhost:8000/videopage').should('include', 'videopage');
    cy.get('input.likeButton.on').each(($el) => {
      cy.wrap($el).click();
    });
  })
})
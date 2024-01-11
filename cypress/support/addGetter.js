export function addGetter(name, selector) {
	if (typeof selector === 'function') {
		Cypress.Commands.add(
			name,
			(...args) => cy.get(selector(...args)),
		)
	}
	else {
		Cypress.Commands.add(name, () => cy.get(selector))
	}
}


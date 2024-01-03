import { User } from '@nextcloud/cypress'

describe('The app', function() {
	it('is visible in the settings', function() {
		const user = new User('admin', 'admin')
		cy.login(user)
		cy.visit('/settings/user')
		cy.get('#app-content').scrollTo('bottom')
		cy.get('#data-request-export').should('be.visible')
		cy.get('#data-request-deletion').should('be.visible')
	})
})

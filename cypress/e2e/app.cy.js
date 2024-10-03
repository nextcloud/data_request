/**
 * SPDX-FileCopyrightText: 2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
import { User } from '@nextcloud/cypress'

const user = new User('admin', 'admin')

for (const action of ['export', 'deletion']) {
	describe(action, function() {
		it('is visible in the settings', function() {
			cy.login(user)
			cy.visit('/settings/user')
			cy.appContent().scrollTo('bottom')
			cy.getButton(action).should('be.visible')
		})

		it('warns the user about missing admin emails', function() {
			cy.login(user)
			cy.triggerRequest(action)
			cy.getWarning(action).should('contain', 'email')
		})

	})
}

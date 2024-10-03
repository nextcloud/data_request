/**
 * SPDX-FileCopyrightText: 2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
import { addCommands } from '@nextcloud/cypress'
import { addGetter } from './addGetter.js'

addCommands()

addGetter('appContent', '#app-content')
addGetter('getButton', action => `#data-request-${action}`)
addGetter('getWarning', action => `#data-request-${action} ~ .warning`)

Cypress.Commands.add('triggerRequest', function(action) {
	cy.visit('/settings/user')
	cy.appContent().scrollTo('bottom')
	cy.getButton(action).click()
})

/**
 * SPDX-FileCopyrightText: 2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
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


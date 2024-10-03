/**
 * SPDX-FileCopyrightText: 2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
import { defineConfig } from 'cypress'
import { configureNextcloud,  startNextcloud,  stopNextcloud, waitOnNextcloud } from '@nextcloud/cypress/docker'

export default defineConfig({
	e2e: {
		setupNodeEvents(on, config) {
			// Remove container after run
			on('after:run', () => {
				if (!process.env.CI) {
					stopNextcloud()
				}
			})

			// starting Nextcloud testing container with specified server branch
			return startNextcloud(process.env.BRANCH)
				.then((ip) => {
					// Setting container's IP as base Url
					config.baseUrl = `http://${ip}/index.php`
					return ip
				})
				.then(waitOnNextcloud)
				// configure Nextcloud, also enable the app
				.then(() => configureNextcloud(['data_request']))
				.then(() => {
					return config
				})
		},
	},
})

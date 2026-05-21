/**
 * SPDX-FileCopyrightText: 2018 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

/** global: OCA */
/** global: OC */

(function(OC, OCA) {
	'use strict'

	OCA.DataRequest = OCA.DataRequest || {}

	OCA.DataRequest.App = {
		init() {
			document.querySelectorAll('#data-request button').forEach((btn) => {
				btn.addEventListener('click', () => this.request(btn))
			})
		},

		request(btn) {
			if (OC.PasswordConfirmation.requiresPasswordConfirmation()) {
				OC.PasswordConfirmation.requirePasswordConfirmation(() => this._doRequest(btn))
				return
			}
			this._doRequest(btn)
		},

		async _doRequest(btn) {
			btn.disabled = true
			btn.classList.add('loading')

			try {
				const response = await fetch(OC.linkToOCS('apps/data_request/api/v1', 2) + btn.dataset.request, {
					method: 'POST',
					headers: {
						'Accept': 'application/json',
						'requesttoken': OC.requestToken,
					},
				})

				if (!response.ok) {
					const data = await response.json().catch(() => null)
					throw { status: response.status, data }
				}

				btn.append(' ' + t('data_request', 'sent!'))
			} catch (err) {
				const { status, data } = err
				const warning = btn.parentElement.querySelector('span.warning')

				btn.disabled = status === 429

				if (warning) {
					warning.classList.remove('hidden')
					warning.textContent = status === 429
						? t('data_request', 'Already requested, please try again later.')
						: (data?.ocs?.data?.error || t('data_request', 'Request failed'))
				}
			} finally {
				btn.classList.remove('loading')
			}
		},
	}
})(OC, OCA)

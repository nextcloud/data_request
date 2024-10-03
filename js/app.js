/**
 * SPDX-FileCopyrightText: 2018 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

'use strict';

/** global: OCA */
/** global: OC */

(function(OC, OCA) {
	OCA.DataRequest = OCA.DataRequest || {};

	OCA.DataRequest.App = {
		init: function() {
			$('#data-request button').on('click', function() {
				OCA.DataRequest.App.request($(this));
			});
		},

		request: function ($context) {
			if(OC.PasswordConfirmation.requiresPasswordConfirmation()) {
				var self = this;
				OC.PasswordConfirmation.requirePasswordConfirmation(function () {
					self._doRequest($context);
				});
				return;
			}
			this._doRequest($context);
		},

		_doRequest($context) {
			$context.prop('disabled', 'disabled');
			$context.addClass('loading');
			$context.siblings('span.warning').addClass('hidden').html('');

			$.ajax({
				type: 'POST',
				url: OC.linkToOCS('apps/data_request/api/v1', 2) + $context.data('request'),
				dataType: 'json',
				beforeSend: function (request) {
					request.setRequestHeader('Accept', 'application/json');
				},

				success: function () {
					$context.html($context.html() + ' ' + t('data_request', 'sent!'));
					$context.removeClass('loading');
				},
				error: function (response) {
					$context.prop('disabled', '');
					$context.removeClass('loading');
					if(response.responseJSON && response.responseJSON.ocs.data.error) {
						$context.siblings('span.warning')
							.removeClass('hidden')
							.html(response.responseJSON.ocs.data.error);
					}
				}
			});
		}
	};
})(OC, OCA);

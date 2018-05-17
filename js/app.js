/**
 * @author Arthur Schiwon <blizzz@arthur-schiwon.de>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

'use strict';

(function(OCA) {
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
			$.ajax({
				type: 'POST',
				url: OC.linkToOCS('apps/data_request/api/v1', 2) + $context.data('request'),
				success: function () {
					$context.html($context.html() + ' ' + t('data_request', 'sent!'));
				},
				error: function () {
					$context.prop('disabled', '');
				}
			});
		}
	};
})(OCA);

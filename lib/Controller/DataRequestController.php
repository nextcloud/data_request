<?php
/**
 * @copyright Copyright (c) 2018 Arthur Schiwon <blizzz@arthur-schiwon.de>
 *
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

namespace OCA\DataRequest\Controller;

use OCA\DataRequest\Exceptions\HintedRuntime;
use OCA\DataRequest\Services\Request;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;

class DataRequestController extends OCSController {

	/** @var Request */
	private $dataRequest;

	public function __construct(
		$appName,
		IRequest $request,
		$corsMethods = 'PUT, POST, GET, DELETE, PATCH',
		$corsAllowedHeaders = 'Authorization, Content-Type, Accept',
		$corsMaxAge = 1728000,
		Request $dataRequest
	) {
		parent::__construct($appName, $request, $corsMethods, $corsAllowedHeaders, $corsMaxAge);
		$this->dataRequest = $dataRequest;
	}

	/**
	 * @NoAdminRequired
	 * @PasswordConfirmationRequired
	 */
	public function export() {
		try {
			$this->dataRequest->sendExportRequest();
			return new DataResponse();
		} catch(HintedRuntime $e) {
			return new DataResponse(
				['error' => $e->getHint()],
				Http::STATUS_INTERNAL_SERVER_ERROR
			);
		}
	}

	/**
	 * @NoAdminRequired
	 * @PasswordConfirmationRequired
	 */
	public function deletion() {
		try {
			$this->dataRequest->sendDeleteRequest();
			return new DataResponse();
		} catch(HintedRuntime $e) {
			return new DataResponse(
				['error' => $e->getHint()],
				Http::STATUS_INTERNAL_SERVER_ERROR
			);
		}
	}
}

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
	private Request $dataRequest;

	public function __construct(
		string $appName,
		IRequest $request,
		Request $dataRequest,
		string $corsMethods = 'PUT, POST, GET, DELETE, PATCH',
		string $corsAllowedHeaders = 'Authorization, Content-Type, Accept',
		int $corsMaxAge = 1728000
	) {
		parent::__construct($appName, $request, $corsMethods, $corsAllowedHeaders, $corsMaxAge);
		$this->dataRequest = $dataRequest;
	}

	/**
	 * @NoAdminRequired
	 * @PasswordConfirmationRequired
	 */
	public function export(): DataResponse {
		return $this->processRequest(function (): void {
			$this->dataRequest->sendExportRequest();
		});
	}

	/**
	 * @NoAdminRequired
	 * @PasswordConfirmationRequired
	 */
	public function deletion(): DataResponse {
		return $this->processRequest(function (): void {
			$this->dataRequest->sendDeleteRequest();
		});
	}

	protected function processRequest(callable $serviceMethod): DataResponse {
		try {
			$serviceMethod();
			return new DataResponse();
		} catch (HintedRuntime $e) {
			return new DataResponse(
				['error' => $e->getHint()],
				Http::STATUS_INTERNAL_SERVER_ERROR
			);
		}
	}
}

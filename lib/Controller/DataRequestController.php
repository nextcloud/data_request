<?php

/**
 * SPDX-FileCopyrightText: 2018 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
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
		int $corsMaxAge = 1728000,
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

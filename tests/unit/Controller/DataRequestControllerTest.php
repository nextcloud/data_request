<?php

/**
 * SPDX-FileCopyrightText: 2018 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\DataRequest\Tests\unit\Controller;

use OCA\DataRequest\Controller\DataRequestController;
use OCA\DataRequest\Exceptions\HintedRuntime;
use OCA\DataRequest\Services\Request;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;

class DataRequestControllerTest extends \Test\TestCase {

	/** @var Request|\PHPUnit_Framework_MockObject_MockObject */
	protected $requestService;

	/** @var DataRequestController */
	protected $controller;

	protected function setUp(): void {
		parent::setUp();

		$this->requestService = $this->createMock(Request::class);

		/** @var IRequest $request */
		$request = $this->createMock(IRequest::class);

		$this->controller = new DataRequestController(
			'data_request',
			$request,
			$this->requestService,
			'PUT, POST, GET, DELETE, PATCH',
			'Authorization, Content-Type, Accept',
			1728000
		);
	}

	public function processDataProvider() {
		return [
			[
				'sendExportRequest',
				true,
				500
			],
			[
				'sendExportRequest',
				false,
				200
			],
			[
				'sendDeleteRequest',
				true,
				500
			],
			[
				'sendDeleteRequest',
				false,
				200
			]
		];
	}

	/**
	 * @dataProvider processDataProvider
	 * @param string $serviceMethod
	 * @param bool $causeException
	 * @param int $expectStatus
	 */
	public function testRequests($serviceMethod, $causeException, $expectStatus) {
		$mocker = $this->requestService->expects($this->once())
			->method($serviceMethod);

		if ($causeException) {
			/** @var HintedRuntime|\PHPUnit_Framework_MockObject_MockObject $exception */
			$exception = $this->createMock(HintedRuntime::class);
			$exception->expects($this->once())
				->method('getHint')
				->willReturn('Some hint');

			$mocker->willThrowException($exception);
		}

		switch ($serviceMethod) {
			case 'sendExportRequest':
				$response = $this->controller->export();
				break;
			case 'sendDeleteRequest':
				$response = $this->controller->deletion();
				break;
			default:
				throw new \RuntimeException('No valid test case');
		}

		$this->assertInstanceOf(DataResponse::class, $response);
		$this->assertSame($expectStatus, $response->getStatus());
		if ($expectStatus >= 500) {
			$this->assertSame('Some hint', $response->getData()['error']);
		}
	}
}

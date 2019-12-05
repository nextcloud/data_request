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

		if($causeException) {
			/** @var HintedRuntime|\PHPUnit_Framework_MockObject_MockObject $exception */
			$exception = $this->createMock(HintedRuntime::class);
			$exception->expects($this->once())
				->method('getHint')
				->willReturn('Some hint');

			$mocker->willThrowException($exception);
		}

		switch($serviceMethod) {
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
		if($expectStatus >= 500) {
			$this->assertSame('Some hint', $response->getData()['error']);
		}
	}
}

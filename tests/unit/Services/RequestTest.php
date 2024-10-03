<?php

use OCA\DataRequest\Exceptions\HintedRuntime;
use OCA\DataRequest\Services\Request;
use OCP\Defaults;
use OCP\IConfig;
use OCP\IGroup;
use OCP\IGroupManager;
use OCP\IL10N;
use OCP\IUser;
use OCP\IUserSession;
use OCP\L10N\IFactory;
use OCP\Mail\IEMailTemplate;
use OCP\Mail\IMailer;

/**
 * SPDX-FileCopyrightText: 2018 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class RequestTest extends \Test\TestCase {
	/** @var IGroupManager|PHPUnit_Framework_MockObject_MockObject */
	protected $groupManager;
	/** @var IMailer|PHPUnit_Framework_MockObject_MockObject */
	protected $mailer;
	/** @var IFactory|PHPUnit_Framework_MockObject_MockObject */
	protected $l10nFactory;
	/** @var IConfig|PHPUnit_Framework_MockObject_MockObject */
	protected $config;
	/** @var IUserSession|PHPUnit_Framework_MockObject_MockObject */
	protected $session;
	/** @var IL10N|PHPUnit_Framework_MockObject_MockObject */
	protected $l;
	protected Request $service;
	/** @var IUser|PHPUnit_Framework_MockObject_MockObject */
	protected $user;
	/** @var Defaults|PHPUnit_Framework_MockObject_MockObject */
	protected $defaults;

	protected function setUp(): void {
		parent::setUp();

		$this->groupManager = $this->createMock(IGroupManager::class);
		$this->mailer = $this->createMock(IMailer::class);
		$this->l10nFactory = $this->createMock(IFactory::class);
		$this->config = $this->createMock(IConfig::class);

		$this->user = $this->createMock(IUser::class);

		$this->session = $this->createMock(IUserSession::class);
		$this->session->expects($this->any())
			->method('getUser')
			->willReturn($this->user);

		$this->l = $this->createMock(IL10N::class);

		$this->defaults = $this->createMock(Defaults::class);

		$this->service = new Request(
			$this->groupManager,
			$this->mailer,
			$this->l10nFactory,
			$this->config,
			$this->session,
			$this->l,
			$this->defaults
		);
	}

	public function templateProvider() {
		return [
			[
				'getExportTemplate',
				'data_request.Export',
				'Personal data export request'
			],
			[
				'getDeletionTemplate',
				'data_request.Deletion',
				'Account deletion request'
			]
		];
	}

	/**
	 * @dataProvider templateProvider
	 * @param $method
	 * @param $templateId
	 * @param $expectedSubject
	 */
	public function testTemplates($method, $templateId, $expectedSubject) {
		$adminUid = 'elu-thingol';
		$adminName = 'Elu Thingol';
		$adminLang = 'qya';
		$admin = $this->createMock(IUser::class);
		$admin->expects($this->any())
			->method('getUID')
			->willReturn($adminUid);
		$admin->expects($this->any())
			->method('getDisplayName')
			->willReturn($adminName);

		$this->config->expects($this->atLeastOnce())
			->method('getSystemValue')
			->with('default_language')
			->willReturn('tlh');
		$this->config->expects($this->atLeastOnce())
			->method('getUserValue')
			->with($adminUid, 'core', 'lang', 'tlh')
			->willReturn($adminLang);

		$l = $this->createMock(IL10N::class);
		$l->expects($this->atLeast(3))
			->method('t')
			->willReturnArgument(0);

		$this->l10nFactory->expects($this->once())
			->method('get')
			->with('data_request', $adminLang)
			->willReturn($l);

		$template = $this->createMock(IEMailTemplate::class);
		$template->expects($this->once())
			->method('setSubject')
			->with($expectedSubject);
		$template->expects($this->once())
			->method('addHeader');
		$template->expects($this->once())
			->method('addHeading');
		$template->expects($this->once())
			->method('addBodyText');
		$template->expects($this->once())
			->method('addFooter');

		$this->mailer->expects($this->once())
			->method('createEMailTemplate')
			->with($templateId, [])
			->willReturn($template);

		$result = $this->invokePrivate($this->service, $method, [$admin]);

		$this->assertSame($template, $result);
	}


	public function adminProvider() {
		$admin1 = $this->createMock(IUser::class);

		$admin2 = $this->createMock(IUser::class);
		$admin2->expects($this->any())
			->method('getEMailAddress')
			->willReturn('admin2@sindar.gov');

		$admin3 = $this->createMock(IUser::class);
		$admin3->expects($this->any())
			->method('getEMailAddress')
			->willReturn('admin3@sindar.gov');

		$admin4 = $this->createMock(IUser::class);

		$admin5 = $this->createMock(IUser::class);
		$admin5->expects($this->any())
			->method('getEMailAddress')
			->willReturn('admin5@sindar.gov');

		return [
			[
				[ $admin1 ],
				0
			],
			[
				[ $admin2 ],
				1
			],
			[
				[ $admin3, $admin4, $admin5 ], // for whatever reasons, reusing $admin1 and $admin2 would fail on CI
				2
			]
		];
	}

	/**
	 * @dataProvider adminProvider
	 * @param $admins
	 * @param $adminsWithEmail
	 */
	public function testGetAdmins($admins, $adminsWithEmail) {
		$adminGroup = $this->createMock(IGroup::class);
		$adminGroup->expects($this->once())
			->method('searchUsers')
			->with('')
			->willReturn($admins);

		$this->groupManager->expects($this->once())
			->method('get')
			->with('admin')
			->willReturn($adminGroup);

		if ($adminsWithEmail === 0) {
			$this->expectException(HintedRuntime::class);
		}
		$result = $this->invokePrivate($this->service, 'getAdmins');

		$this->assertSame($adminsWithEmail, count($result));
	}

	public function mailerSendProvider() {
		return [
			[
				false, [], true
			],
			[
				false, ['elu-thingol@sindar.gov'], false
			],
			[
				true, [], false
			]
		];
	}

	/**
	 * @dataProvider mailerSendProvider
	 * @param $sendThrowsException
	 * @param $sendResult
	 * @param $expectedResult
	 */
	public function testSendMail($sendThrowsException, $sendResult, $expectedResult) {
		$adminName = 'Elu Thingol';
		$adminMail = 'elu-thingol@sindar.gov';
		$admin = $this->createMock(IUser::class);
		$admin->expects($this->any())
			->method('getEMailAddress')
			->willReturn($adminMail);
		$admin->expects($this->any())
			->method('getDisplayName')
			->willReturn($adminName);

		$template = $this->createMock(IEMailTemplate::class);
		$message = $this->createMock(\OC\Mail\Message::class);
		$message->expects($this->once())
			->method('setTo')
			->with([$adminMail => $adminName]);
		$message->expects($this->once())
			->method('useTemplate')
			->with($template);
		$message->expects($this->once())
			->method('setFrom');

		$this->mailer->expects($this->once())
			->method('createMessage')
			->willReturn($message);

		$sendMocker = $this->mailer->expects($this->once())
			->method('send')
			->with($message);
		if ($sendThrowsException) {
			$sendMocker->willThrowException(new \Exception('Expected Exception'));
		} else {
			$sendMocker->willReturn($sendResult);
		}

		$this->defaults->expects($this->atLeastOnce())
			->method('getName')
			->willReturn('Cloud of Sindar');

		$result = $this->invokePrivate($this->service, 'craftEmailTo', [$admin, $template]);
		$this->assertSame($expectedResult, $result);
	}
}

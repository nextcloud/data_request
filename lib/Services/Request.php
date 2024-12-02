<?php

/**
 * SPDX-FileCopyrightText: 2018 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\DataRequest\Services;

use OCA\DataRequest\Exceptions\HintedRuntime;
use OCP\Defaults;
use OCP\IConfig;
use OCP\IGroupManager;
use OCP\IL10N;
use OCP\IUser;
use OCP\IUserSession;
use OCP\L10N\IFactory;
use OCP\Mail\IEMailTemplate;
use OCP\Mail\IMailer;
use OCP\Util;

class Request {
	protected ?string $defaultLanguage = null;
	private IGroupManager $groupManager;
	private IMailer $mailer;
	private IFactory $l10nFactory;
	private IConfig $config;
	private IUser $requester;
	private IL10N $l;
	private Defaults $defaults;

	public function __construct(
		IGroupManager $groupManager,
		IMailer $mailer,
		IFactory $l10nFactory,
		IConfig $config,
		IUserSession $userSession,
		IL10N $l,
		Defaults $defaults,
	) {
		$this->groupManager = $groupManager;
		$this->mailer = $mailer;
		$this->l10nFactory = $l10nFactory;
		$this->config = $config;
		$this->requester = $userSession->getUser();
		$this->l = $l;
		$this->defaults = $defaults;
	}

	public function sendExportRequest(): void {
		$this->sendRequest(function (IUser $r): IEMailTemplate {
			return $this->getExportTemplate($r);
		});
	}

	public function sendDeleteRequest(): void {
		$this->sendRequest(function (IUser $r): IEMailTemplate {
			return $this->getDeletionTemplate($r);
		});
	}

	protected function sendRequest(callable $templateGenerator): void {
		$admins = $this->getAdmins();

		$oneMailSent = false;
		foreach ($admins as $admin) {
			$template = $templateGenerator($admin);
			if ($this->craftEmailTo($admin, $template) === true) {
				$oneMailSent = true;
			}
		}
		if (!$oneMailSent) {
			throw new HintedRuntime(
				'No mail was sent successfully',
				$this->l->t('No administrator could have been contacted.')
			);
		}
	}

	protected function getDefaultLang(): string {
		if ($this->defaultLanguage === null) {
			$this->defaultLanguage = $this->config->getSystemValue('default_language', 'en');
		}
		return $this->defaultLanguage;
	}

	protected function craftEmailTo(IUser $admin, IEMailTemplate $template): bool {
		$senderAddress = Util::getDefaultEmailAddress('no-reply');
		$senderName = $this->defaults->getName();

		$adminEmail = $admin->getEMailAddress();
		if ($adminEmail === null || $adminEmail === '') {
			return false;
		}

		$message = $this->mailer->createMessage();
		$message->setTo([$adminEmail => $admin->getDisplayName()]);
		$message->useTemplate($template);
		$message->setFrom([$senderAddress => $senderName]);

		try {
			$failedRecipients = $this->mailer->send($message);
			if (count($failedRecipients) > 0) {
				return false;
			}
		} catch (\Exception $e) {
			return false;
		}

		return true;
	}

	protected function getExportTemplate(IUser $admin): IEMailTemplate {
		$l = $this->l10nFactory->get('data_request', $this->config->getUserValue($admin->getUID(), 'core', 'lang', $this->getDefaultLang()));
		$template = $this->mailer->createEMailTemplate('data_request.Export', []);

		$template->setSubject($l->t('Personal data export request'));

		$template->addHeader();
		$template->addHeading($l->t('Hello %s,', [$admin->getDisplayName()]));
		$template->addBodyText($l->t('The user %s, identified by user id "%s", has requested an export of their personal data. Please take action accordingly.', [$this->requester->getDisplayName(), $this->requester->getUID()]));

		$template->addFooter();

		return $template;
	}

	protected function getDeletionTemplate(IUser $admin): IEMailTemplate {
		$l = $this->l10nFactory->get('data_request', $this->config->getUserValue($admin->getUID(), 'core', 'lang', $this->getDefaultLang()));
		$template = $this->mailer->createEMailTemplate('data_request.Deletion', []);

		$template->setSubject($l->t('Account deletion request'));

		$template->addHeader();
		$template->addHeading($l->t('Hello %s,', [$admin->getDisplayName()]));
		$template->addBodyText($l->t('The user %s, identified by user id "%s", has requested to delete their account. Please take action accordingly.', [$this->requester->getDisplayName(), $this->requester->getUID()]));

		$template->addFooter();

		return $template;
	}

	protected function getAdmins(): array {
		$admins = $this->groupManager->get('admin')?->searchUsers('') ?? [];
		$admins = array_filter($admins, function (IUser $admin) {
			return $admin->getEMailAddress() !== null;
		});
		if (empty($admins)) {
			throw new HintedRuntime(
				'No admin has entered an email address',
				$this->l->t('No administrator has set an email address')
			);
		}
		return $admins;
	}
}

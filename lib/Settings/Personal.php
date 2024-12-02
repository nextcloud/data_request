<?php

/**
 * SPDX-FileCopyrightText: 2018 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\DataRequest\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\Settings\ISettings;

class Personal implements ISettings {
	public function getForm(): TemplateResponse {
		return new TemplateResponse('data_request', 'Settings/personal', [], '');
	}

	public function getSection(): string {
		return 'personal-info';
	}

	public function getPriority(): int {
		return 80;
	}
}

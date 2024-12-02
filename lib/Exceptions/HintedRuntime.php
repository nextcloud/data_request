<?php

/**
 * SPDX-FileCopyrightText: 2018 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\DataRequest\Exceptions;

use Throwable;

class HintedRuntime extends \RuntimeException {
	protected string $hint;

	public function __construct(string $message = '', string $hint = '', int $code = 0, ?Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
		$this->hint = $hint;
	}

	public function getHint(): string {
		if (empty($this->hint)) {
			return $this->message;
		}
		return $this->hint;
	}
}

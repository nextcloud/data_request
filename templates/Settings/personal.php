<?php
/**
 * SPDX-FileCopyrightText: 2018 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

style('data_request', 'style');
script('data_request', ['init', 'app']);


?>

<div id="data-request" class="section">
	<h2><?php p($l->t('Account')); ?></h2>
	<p class="settings-hint"><?php p($l->t('You can request an export of your data or account deletion from the system administrator. This can take up to 30 days.')); ?></p>
	<div>
		<button id="data-request-export" data-request="export" class="button"><?php p($l->t('Request data export')); ?></button><span class="warning hidden"></span>
	</div>
	<div>
		<button id="data-request-deletion" data-request="deletion" class="button"><?php p($l->t('Request account deletion')); ?></button><span class="warning hidden"></span>
	</div>
</div>

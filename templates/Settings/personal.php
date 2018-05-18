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

style('data_request', 'style');
script('data_request', ['init', 'app']);


?>

<div id="data-request" class="section">
	<h2><?php p($l->t('Account')); ?></h2>
	<div>
		<button id="data-request-export" data-request="export" class="button"><?php p($l->t('Request data export')); ?></button><span class="warning hidden"></span>
	</div>
	<div>
		<button id="data-request-deletion" data-request="deletion" class="button"><?php p($l->t('Request account deletion')); ?></button><span class="warning hidden"></span>
	</div>
</div>

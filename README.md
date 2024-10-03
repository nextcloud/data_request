<!--
  - SPDX-FileCopyrightText: 2020 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->
# data_request

[![REUSE status](https://api.reuse.software/badge/github.com/nextcloud/data_request)](https://api.reuse.software/info/github.com/nextcloud/data_request)

:man_judge: Nextcloud app to let users request a data export or account removal

## Features

📊 Let users request their own data

💬 Automatic Email sending 

## Bugs

https://github.com/nextcloud/data_request/issues

## Testing

Running the tests requires `npm` and `docker` to be installed.

On the first run the nextcloud image will be downloaded
which may take a while.

Install cypress and run the end to end tests:

```bash
make test
```

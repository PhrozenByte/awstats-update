AWStats Updater 2.4
===================

Update AWStats database and/or generate static HTML pages, either of the specified or all known hosts. By default, `awstats-update` updates the database (`--update`) and creates HTML pages (`--rebuild`) for the current month. You can create HTML pages for all historic data using `--rebuild-all`.

Usage
-----

```shell
awstats-update [OPTION]...
awstats-update [OPTION]... CONFIG...
```

`CONFIG` can either be:
* a absolute path to a config file
* a relative path to a config file, starting from `/etc/awstats/`
* a config spec in the form `[ HOST_TYPE "/" ] HOST`

`awstats-update` supports the following options:

* Help options:
  * `-h`, `--help`: display this help and exit
  * `--version`: output version information and exit

* Application options:
  * `--[no-]update`: [don't] update AWStats database
  * `--[no-]rebuild`: [don't] generate static HTML pages (current month only)
  * `-a`, `--rebuild-all`: generate static HTML pages (all historic data)
  * `-o`, `--overwrite`: re-create existing HTML pages (with `--rebuild-all`)
  * `--no-config[=CONFIG]`: don't skip domains without a config; use `CONFIG`, otherwise use default config (with `--rebuild-all`)
  * `--format=FORMAT`: output HTML pages according to `FORMAT`: `plain`, or `gzip`. You also pass both comma separated. default: `plain`
  * `--lang=LANG`: output HTML pages using language LANG. default: `en`
  * `--data-dir=PATH`: path to AWStats data directory. default: `/var/lib/awstats`
  * `--www-dir=PATH`: path to AWStats www directory. default: `/var/cache/awstats/www`
  * `--standalone=PATH`: path to a standalone `awstats.pl'
  * `-v`, `--verbose`: increase verbosity
  * `-q`, `--quiet`: decrease verbosity

Install
-------

`awstats-update` was tested under Debian 8 (Jessie) and a shared hoster with limited SSH access (standalone mode), only. However, it *should* work great with any other distribution, too. If `awstats-update` doesn't work with your favourite distribution, please file a bug report. It was written to work with `bash`.

**You wanna make `awstats-update` work with your favorite distribution or improve it in general?** Go on, I appreciate it!

`awstats-update` is basically just a shell script, so there are no complex "installation steps" besides moving it to `/usr/local/bin/awstats-update`. Everything else depends on your system, on Debian you might want to do something like the following:

* Disable AWStats' default updater (if you're using Debian, you'll have to set `AWSTATS_ENABLE_BUILDSTATICPAGES="no"` and `AWSTATS_ENABLE_CRONTABS="no"` in `/etc/default/awstats`)
* Remove existing HTML pages: `rm -r /var/cache/awstats/awstats/`
* Create new target directories: `mkdir -p /var/cache/awstats/www/{apache2,apache2-ssl,postfix}`
* Create `/usr/local/bin/awstats-update` and make it executable
* Consider creating a `/etc/cron.daily/awstats-update` with something like the following:
  ```shell
  #!/bin/sh
  /usr/local/bin/awstats-update --quiet "$@"
  ```
* Create a distinct config file for each virtual host, e.g. `/etc/awstats/awstats.apache2.example.com.conf` or `/etc/awstats/awstats.apache2-ssl.example.net.conf` (Tip: AWStats supports `Include` statements)
* Consider using `/var/cache/awstats/www/awstats.php` and `/var/cache/awstats/www/.htaccess` (see `www` directory)

Standalone Usage
----------------

You can also use `awstats-update` in a environment with a standalone `awstats.pl` in a arbitrary path. Move AWStats' config files as well as the `lib`, `plugins` and `lang` directories into the same directory as `awstats.pl`. You can now run `awstats-update` with the `--standalone` option pointing to said `awstats.pl`.

License & Copyright
-------------------

Copyright (C) 2011-2017  Daniel Rudolf <http://www.daniel-rudolf.de/>

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, version 3 of the License only.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the [GNU General Public License](LICENSE) for more details.

![Build Status](https://app.chipperci.com/projects/0e5f8979-31eb-4ee6-9abf-050b76ab0383/status/master) [![Crowdin](https://d322cqt584bo4o.cloudfront.net/snipe-it/localized.svg)](https://crowdin.com/project/snipe-it) [![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/snipe/snipe-it?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge) [![Docker Pulls](https://img.shields.io/docker/pulls/snipe/snipe-it.svg)](https://hub.docker.com/r/snipe/snipe-it/) [![Twitter Follow](https://img.shields.io/twitter/follow/snipeitapp.svg?style=social)](https://twitter.com/snipeitapp)  [![Codacy Badge](https://api.codacy.com/project/badge/Grade/553ce52037fc43ea99149785afcfe641)](https://www.codacy.com/app/snipe/snipe-it?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=snipe/snipe-it&amp;utm_campaign=Badge_Grade)
[![All Contributors](https://img.shields.io/badge/all_contributors-261-orange.svg?style=flat-square)](#contributors) 

## MANAJEMEN ASET BARANG TI - Fungsi PTI

This is internal project for BPS RI - PTI Subdirectorate, built from Snipe-IT FOSS project. Knowing who has which laptop, when it was purchased in order to depreciate it correctly, handling software licenses, etc.

It is built on [Laravel 8](http://laravel.com).

__This is web-based software__. This means there is no executable file (aka no .exe files), and it must be run on a web server and accessed through a web browser. It runs on any Mac OSX, flavor of Linux, as well as Windows, and we have a [Docker image](https://snipe-it.readme.io/docs/docker) available if that's what you're into.

-----

### Installation Roadmap (Yosral)
- (paling awal) run `git clone http://git.bps.go.id/yosral/simpati-pti.git`
- (pull/update branch masing-masing) run `git pull origin *branch_sumber*`
- (install dependencies package) run `composer update`
- (atur environment framework) atur environment host, database, dll. dengan copy file `.env.example`, rename (misal `.env.yosral`)
- (clear cache terlebih dulu sebelum run semua command artisan) run `php artisan cache:clear`, kemudian `composer dump-autoload`
- (buat database [kitloong/laravel-migrations-generator](https://github.com/kitloong/laravel-migrations-generator), [orangehill/iseed](https://github.com/orangehill/iseed)) run `php artisan migrate:refresh`, jika belum berhasil periksa kembali file `.env` yang sebelumnya sudah diset
- (run dengan artisan apache) run `php artisan serve`, kemudian klik link url yang muncul untuk ke web browser
- (wajib buat terlebih dahulu berbagai custom-field sebelum seeding database )
- (proses seeding database) run `php artisan db:seed`
-----

### User's Manual
For help using Snipe-IT, check out the [user's manual](https://snipe-it.readme.io/docs/overview).

-----

### Bug Reports & Feature Requests

Feel free to check out the [GitHub Issues for this project](https://github.com/snipe/snipe-it/issues) to open a bug report or see what open issues you can help with. Please search through existing issues (open *and* closed) to see if your question has already been answered before opening a new issue.

**PLEASE see the [Getting Help Guidelines](https://snipe-it.readme.io/docs/getting-help) and [Common Issues](https://snipe-it.readme.io/docs/common-issues) before opening a ticket, and be sure to complete all of the questions in the Github Issue template to help us to help you as quickly as possible.**

-----

### Upgrading

Please see the [upgrading documentation](https://snipe-it.readme.io/docs/upgrading) for instructions on upgrading Snipe-IT.

------

### Translations!

Please see the [translations documentation](https://snipe-it.readme.io/docs/translations) for information about available languages and how to add translations to Snipe-IT.

-----

### Security

To report a security vulnerability, please email security@snipeitapp.com instead of using the issue tracker. 

-----

This project follows the [all-contributors](https://github.com/kentcdodds/all-contributors) specification. Contributions of any kind welcome!


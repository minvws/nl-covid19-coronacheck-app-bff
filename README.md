# COVID-19 CoronaCheck App - Backend for Frontend (BFF)

## Introduction
This repository contains the lumen app which sits in front of the CoronaCheck backend services.

## Installation
Code should work on any Linux flavored OS.

### Installation steps
Install dependencies. On debian/ubuntu this is done by
```
apt-get install php8.0 php8.0-curl php8.0-redis openssl
```

Check out code and run `composer install`.


### Configuration Files
See `.env.example` or `config/*.php` for configuration options.

Two important configuration items when debugging/testing:
- `SIGNED_TEST_HASH_DURATION` (seconds) is how the fact/hash that a test result has been signed is stored
- `SESSION_DURATION` (seconds) is how long the nonce is stored
- `SIGNATURE_FORMAT` can be `inline`, `inline-double`, `header`, or `none`. Inline double adds `_payload` which is a copy of payload without the base64 encoding.

Note that Signatures must be inline when using php-fpm/proxy_fcgi as Apache does not support
headers larger than 5000 bytes. This will result in the error `AH01070: Error parsing script headers`.

#### CTP Provider Public Keys
The application expects CMS public keys used to sign test results to be located in the directory specified
in the `.env` by `CMS_SIGN_CTP_DIR` in the following format `brb_cms_sign_public.pem`

#### CDN Files
Several API's are loaded statically. These should be placed in the directory specified by `CDN_FILES_DIR`

## Development & Contribution process

The development team works on the repository in a private fork (for reasons of compliance with existing processes) and shares its work as often as possible.

If you plan to make non-trivial changes, we recommend to open an issue beforehand where we can discuss your planned changes.
This increases the chance that we might be able to use your contribution (or it avoids doing work if there are reasons why we wouldn't be able to use it).

Note that all commits should be signed using a gpg key.

# COVID-19 CoronaCheck App - Backend for Frontend (BFF)

## Introduction
This repository contains the lumen app which sits in front of the CoronaCheck backend services.

## Installation
Code should work on any Linux flavored OS but has been tested on Ubuntu 20.04.

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

#### CTP Provider Public Keys
The application expects CMS public keys used to sign test results to be located in the directory specified
in the `.env` by `CMS_SIGN_CTP_DIR` in the following format `brb_cms_sign_public.pem`

#### CDN Files
Several API's are loaded statically. These should be placed in the directory specified by `CDN_FILES_DIR`

## Error codes
The `get_test_ism` api will give an error code if the received test result is incorrect.

Http Code | Status Code | Description
----------|-------------|-----------------------------------
400 | 99981| Test is not in expected format
400 | 99982| Test is empty
400 | 99983| Test signature invalid
400 | 99991| Test sample time in the future
400 | 99992| Test sample time too old (48h)
400 | 99993| Test result was not negative
400 | 99994| Test result signed before
500 | 99995| Unknown error creating signed test result
400 | 99996| Session key no longer valid


## Development & Contribution process

The development team works on the repository in a private fork (for reasons of compliance with existing processes) and shares its work as often as possible.

If you plan to make non-trivial changes, we recommend to open an issue beforehand where we can discuss your planned changes.
This increases the chance that we might be able to use your contribution (or it avoids doing work if there are reasons why we wouldn't be able to use it).

Note that all commits should be signed using a gpg key.

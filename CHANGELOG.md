# [1.8.0](https://github.com/CesarScur/reliquary/compare/v1.7.0...v1.8.0) (2025-07-16)


### Features

* **mailer:** Upgrade symfony to 7.2 and add mailtrap ([297ba3e](https://github.com/CesarScur/reliquary/commit/297ba3e38b219224e55592c0ae44a781ad0ffeef))


### Reverts

* Revert "feat(mailer): add Mailtrap integration and update dependencies" ([04845f4](https://github.com/CesarScur/reliquary/commit/04845f490fbab9442070da7a815516926d59f873))

# [1.7.0](https://github.com/CesarScur/reliquary/compare/v1.6.2...v1.7.0) (2025-07-16)


### Features

* **mailer:** add Mailtrap integration and update dependencies ([5b82904](https://github.com/CesarScur/reliquary/commit/5b82904a4c3fc2d155ac1a3cb6958e353bc7a30f))

## [1.6.2](https://github.com/CesarScur/reliquary/compare/v1.6.1...v1.6.2) (2025-07-16)


### Bug Fixes

* **user:** rename `setVerified` to `setIsVerified` ([3656ec9](https://github.com/CesarScur/reliquary/commit/3656ec960176f7ca084c73379003ad1f174c0a40))

## [1.6.1](https://github.com/CesarScur/reliquary/compare/v1.6.0...v1.6.1) (2025-07-15)


### Bug Fixes

* **logging:** optimize Monolog configuration and enhance log handling ([df8f689](https://github.com/CesarScur/reliquary/commit/df8f689b9017f16d28c191f49ff3e4451cc83ddc))

# [1.6.0](https://github.com/CesarScur/reliquary/compare/v1.5.0...v1.6.0) (2025-07-15)


### Bug Fixes

* **dev:** set default URI for development environment ([168e4cc](https://github.com/CesarScur/reliquary/commit/168e4cc30e0dbe0df6200ef209087052e34e6b40))


### Features

* **errors:** add custom error pages and debug error triggers ([796a4b1](https://github.com/CesarScur/reliquary/commit/796a4b10e3a90dcbd46f6a8658c27613d0a943f8))
* **logging:** add admin log viewer interface ([94cc128](https://github.com/CesarScur/reliquary/commit/94cc128cceab32b5aefc381ce05cc267e472ddb7))
* **logging:** integrate Monolog for enhanced logging capabilities ([29d55a0](https://github.com/CesarScur/reliquary/commit/29d55a02e23c48302db447ec4c51d44a7a44f72b))
* **users:** add user management functionality for admins ([4f93b32](https://github.com/CesarScur/reliquary/commit/4f93b324331f45eac4ea4a3c76e827ca0ccf676b))

# [1.5.0](https://github.com/CesarScur/reliquary/compare/v1.4.0...v1.5.0) (2025-07-15)


### Features

* **apache:** configure domain and SSL for production ([3665f22](https://github.com/CesarScur/reliquary/commit/3665f22818d49e07d52ce433ca52b31821066c78))

# [1.4.0](https://github.com/CesarScur/reliquary/compare/v1.3.0...v1.4.0) (2025-07-15)


### Features

* **security:** enforce HTTPS and secure cookies ([46a3063](https://github.com/CesarScur/reliquary/commit/46a306385a9f733a38b24a553f8cb3298fcba0f0))

# [1.3.0](https://github.com/CesarScur/reliquary/compare/v1.2.0...v1.3.0) (2025-07-15)


### Features

* **home:** optimize layout for mobile and desktop views ([df6e8f1](https://github.com/CesarScur/reliquary/commit/df6e8f1ade129e4195a096abaa94451775070249))
* **relics:** redesign relics page for responsiveness ([5c597c7](https://github.com/CesarScur/reliquary/commit/5c597c70336782581d0abc2e6ac559ece2447ba2))

# [1.2.0](https://github.com/CesarScur/reliquary/compare/v1.1.1...v1.2.0) (2025-07-15)


### Bug Fixes

* **navbar:** hide search bar on small screens ([c5f8664](https://github.com/CesarScur/reliquary/commit/c5f86644549fd50b857e91e3537b53860e41a989))


### Features

* **navbar:** improve mobile responsiveness and accessibility ([2d027d7](https://github.com/CesarScur/reliquary/commit/2d027d73765a99f2f4f5d55624c5d0f9c26f6042))

## [1.1.1](https://github.com/CesarScur/reliquary/compare/v1.1.0...v1.1.1) (2025-07-15)


### Bug Fixes

* **map:** adjust no relics message position to bottom left ([1fd5db5](https://github.com/CesarScur/reliquary/commit/1fd5db58702f2594f7a44d55194dbe2e4794515a))
* **release:** adjust semantic-release order and enhance VERSION file management ([7ac8244](https://github.com/CesarScur/reliquary/commit/7ac8244e7319b00d7a854cd570ced6b1cb598f11))

# [1.1.0](https://github.com/CesarScur/reliquary/compare/v1.0.0...v1.1.0) (2025-07-14)


### Features

* **deployment:** integrate Watchtower for automated production updates ([6dd8613](https://github.com/CesarScur/reliquary/commit/6dd861306484d08e604b17466af3dabdd08ac342))

# 1.0.0 (2025-07-13)


### Features

* **ci:** enhance GitHub Actions workflow with Node.js setup and semantic release dependencies ([70582f1](https://github.com/CesarScur/reliquary/commit/70582f14059bfb059b5197b73c0a3435cab52445))
* **docker:** add asset map compilation step to production Dockerfile ([1d4b791](https://github.com/CesarScur/reliquary/commit/1d4b79187b7125f22e10eaee5c410f8034447d3b))
* **versioning:** implement semantic release configuration for automated versioning ([a807c0e](https://github.com/CesarScur/reliquary/commit/a807c0eabf0ad316e28ac32142938a5f7cb43721))
* **versioning:** integrate dynamic versioning system across application ([96805f1](https://github.com/CesarScur/reliquary/commit/96805f118052e7bc93e4fc28d9a6cd88df677848))

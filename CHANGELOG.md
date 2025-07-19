# [1.15.0](https://github.com/CesarScur/reliquary/compare/v1.14.0...v1.15.0) (2025-07-19)


### Features

* **forms:** add password visibility toggle to forms ([67309a0](https://github.com/CesarScur/reliquary/commit/67309a0f4eb183a70345b6cfc07cfe49c969e13d))

# [1.14.0](https://github.com/CesarScur/reliquary/compare/v1.13.0...v1.14.0) (2025-07-19)


### Features

* **admin:** add password reset button and modal in user management ([c5a9954](https://github.com/CesarScur/reliquary/commit/c5a9954546f067cf6af72b19b345c81c86a0a8ca))
* **templates:** enhance password reset templates with improved UI styling ([fc0e3e8](https://github.com/CesarScur/reliquary/commit/fc0e3e8408528a4309162342ff7430dbbab75c84))

# [1.13.0](https://github.com/CesarScur/reliquary/compare/v1.12.0...v1.13.0) (2025-07-19)


### Features

* **auth:** implement password reset functionality ([5963366](https://github.com/CesarScur/reliquary/commit/5963366fff5d9c1ac9aeb2d956f39bd85cfa25a3))
* **deployment:** automate database migrations via container entrypoint ([be21e65](https://github.com/CesarScur/reliquary/commit/be21e653ed6cbb79124aff8f5d1b6f9e2dca8b87))
* **i18n:** enhance translations and update templates for multilingual support ([9847718](https://github.com/CesarScur/reliquary/commit/9847718b050e55365e9a2e04926f3fcd05fa2858))

# [1.12.0](https://github.com/CesarScur/reliquary/compare/v1.11.2...v1.12.0) (2025-07-19)


### Features

* **admin:** add translation status viewer for missing translations ([65b80be](https://github.com/CesarScur/reliquary/commit/65b80bebbf3f4da6a3b90f7680da39eaa06cc091))
* **i18n:** add locale switching and multi-language support ([5ec2b3c](https://github.com/CesarScur/reliquary/commit/5ec2b3c1a5b41a194ba4c0addc89064faac6b324))
* **i18n:** replace Spanish and French with Portuguese Brazil support ([28fb1a9](https://github.com/CesarScur/reliquary/commit/28fb1a9b47c12e0054fa74e71059412bb87457b1))
* **i18n:** restructure translations into domain-specific files ([46c6957](https://github.com/CesarScur/reliquary/commit/46c6957cf02c81c99c58dfc11651e619f9d9ba66))
* **templates:** refactor relic templates to use shared header partial ([f7e3136](https://github.com/CesarScur/reliquary/commit/f7e3136be077e30efccc69dcc7bda447e2cd6224))


### Reverts

* Revert "feat(templates): enhance Turbo integration for relic-related templates" ([89c6896](https://github.com/CesarScur/reliquary/commit/89c6896c21a3de5d764ccdc16b7c0d9a9e75c584))

## [1.11.2](https://github.com/CesarScur/reliquary/compare/v1.11.1...v1.11.2) (2025-07-17)


### Bug Fixes

* **templates:** remove unused title header from relic list mobile view ([6d65e6e](https://github.com/CesarScur/reliquary/commit/6d65e6ef75f236ffbb1da617fa58c832bf7e2e0a))

## [1.11.1](https://github.com/CesarScur/reliquary/compare/v1.11.0...v1.11.1) (2025-07-17)


### Bug Fixes

* **docker:** update Dockerfile path for Apache build step ([75e7865](https://github.com/CesarScur/reliquary/commit/75e7865f8a3e11a3d4acdc1ac160ffc98e819df6))

# [1.11.0](https://github.com/CesarScur/reliquary/compare/v1.10.0...v1.11.0) (2025-07-17)


### Features

* **docker:** refactor Docker setup to replace nginx with Apache ([8215497](https://github.com/CesarScur/reliquary/commit/8215497863b43f95f3b4441856955dfc81eb5df9))
* **templates:** add responsive relic list with desktop and mobile views ([bda4c02](https://github.com/CesarScur/reliquary/commit/bda4c025ebdfcf043c419f0b132dc09532dbe549))
* **templates:** enhance relic list design with improved table styling ([ae13a9b](https://github.com/CesarScur/reliquary/commit/ae13a9b41c5a81db0c76b37f8b9c784383bf299c))
* **templates:** enhance Turbo integration for relic-related templates ([54199e5](https://github.com/CesarScur/reliquary/commit/54199e5393bfe78d5c5624e19ef5ecc15037ed0d))
* **templates:** remove admin-only "Users" link from navigation menu ([da43056](https://github.com/CesarScur/reliquary/commit/da43056a64bd64488b645c170808a474a62e7e05))

# [1.10.0](https://github.com/CesarScur/reliquary/compare/v1.9.0...v1.10.0) (2025-07-17)


### Features

* **docker:** add new session volume to production compose file ([6647417](https://github.com/CesarScur/reliquary/commit/6647417a1375092ccad76d70f3e393d996479797))
* **templates:** redesign email confirmation template with improved styling ([46bc268](https://github.com/CesarScur/reliquary/commit/46bc2687b767b79ac13ff20568c8f1f1f092fc27))
* **templates:** redesign email confirmation template with improved styling ([5af510b](https://github.com/CesarScur/reliquary/commit/5af510bc64f5afecbde78deecdbaf8201014aee7))

# [1.9.0](https://github.com/CesarScur/reliquary/compare/v1.8.0...v1.9.0) (2025-07-17)


### Bug Fixes

* **mailer:** update email sender address in registration confirmation ([91b629b](https://github.com/CesarScur/reliquary/commit/91b629b3b4364bc9e048c6634e1caa00b453c142))


### Features

* **project:** update project metadata in composer.json ([4732c36](https://github.com/CesarScur/reliquary/commit/4732c36dfecefafb6af36e210f606f187f706d9f))
* **twig:** add `LogFormatterExtension` for log formatting in templates ([8045599](https://github.com/CesarScur/reliquary/commit/8045599c41a15e99ec48e4c2ecb559cea65599db))

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

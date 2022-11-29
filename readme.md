# Core Style Plugin

[![PHP](https://github.com/imaginarymachines/core-style-plugin/actions/workflows/php.yml/badge.svg)](https://github.com/imaginarymachines/core-style-plugin/actions/workflows/php.yml)
[![JS/CSS](https://github.com/imaginarymachines/core-style-plugin/actions/workflows/js-css.yml/badge.svg)](https://github.com/imaginarymachines/core-style-plugin/actions/workflows/js-css.yml)


## Usage

See the [readme.txt](readme.txt) for installation and usage instructions.

## Development


To use the provided development environment, you'll first need to install and launch Docker. Once it's running, the next steps are:

```
git clone git@github.com:imaginarymachines/core-style-plugin.git
cd core-style-plugin
npm preinstall
npm composer install
npm install
npm run build
npm run env start
```

See `package.json` for other available scripts you might want to use during development, like linting and testing.

### Local Development

Local development uses [@wordpress/wp-env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/)

- Start server
	- `npm run env start`
	- [http://localhost:8042]
- Stop server
	- `npm run env stop`

### PHP

- Run phpunit tests:
	`npm run test:php`
- Format PHP code:
	`npm run format:php`
- Lint, but not fix PHP code
	`npm run lint:php`

### JavaScript And CSSS

- Format JS code:
	`npm run format:js`
- Format CSS code:
	`npm run format:css`
- Lint, but not fix JavaScript
	`npm run lint:js`
- Lint, but not fix CSS
	`npm run lint:css`

## Deployments

Deployments [to WP.org plugin repository](https://wordpress.org/plugins/two-factor/) are handled automatically by the GitHub action [.github/workflows/deploy.yml](.github/workflows/deploy.yml). All merges to the `main` branch are commited to the [`trunk` directory](https://plugins.trac.wordpress.org/browser/core-style-plugin/trunk) while all [Git tags](https://github.com/imaginarymachines/core-style-plugin/tags) are pushed as versioned releases [under the `tags` directory](https://plugins.trac.wordpress.org/browser/two-factor/tags).

## Credits And License

Forked from  [WordPress/two-factor](https://github.com/WordPress/two-factor/) and released under [GPLv2 or later](LICENSE.md).

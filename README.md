SQweb Drupal Module
===

[![Build Status](https://travis-ci.org/SQweb-team/SQweb-Drupal-Module.svg?branch=8.x-2.x)](https://travis-ci.org/SQweb-team/SQweb-Drupal-Module)

**This Module allows you to easily integrate SQweb on your Drupal powered website.**

## Requirements

**This Module has been tested with PHP 5.5 and greater and with Drupal 8.22 and greater.**

We are unable to provide official support for earlier versions. For more information about end of life PHP branches, [see this page](http://php.net/supported-versions.php).

## Install

Settings your SQweb Website ID and your language on your Drupal administration, on SQweb section.

## Usage

### 1. Tagging your pages

The SQweb script is added to all your pages after you configure your SQweb Website ID (Ref: Install).

### 2. Checking the credits of your subscribers

SQweb extends Twig with filters and functions to simplify the use of SQweb within your theme.

Use it like this:

```php
{% if sqw_abo() %}
	// CONTENT
{% else %}
	// ADS
{% endif %}
```

### 3. Showing the Multipass button

Finally, use this code to display the Multipass button on your pages:

```php
{{ sqw_button() }}
```

If you want to use a smaller version of the Multipass button :

```php
{{ sqw_button('slim') }}
```

Here is a complete list of sizes :

![Example Buttons](https://cdn.multipass.net/github/buttons@2x.png "Example Buttons")

### 4. More functions


#### Display only a part of your content to non premium users

```php
{{ 'Your content' | sqw_transpartext(percent) }}
```
`percent` is the percent of your content you want to display to everyone.

Example:

```php
{{ 'one two three four' | sqw_transpartext( 50 ) }}
```

Will display for free users:

```
one two
```

#### Display your content later to non paying users

```php
{{ 'Your content' | sqw_waittodisplay(publication_date, wait) }}
```

1. `publication_date` is the date when your content is published on your website.
2. `wait` is the number of day you want to wait before showing this content to free users.

Example:

```php
{{ 'Put your content here' | sqw_waittodisplay('2016/10/01', 3) }}
```

#### Limit the number of articles free users can read per day

```php
{{ 'Your content' | sqw_limitarticle(numbers_of_articles) }}
```

`number_of_articles` is the number of articles a free user can see.

For instance, if I want to display only 5 articles to free users:

```php
{{ 'Your content' | sqw_limitarticle(5) }}
```

## Contributing

We welcome contributions and improvements.

### Coding Style

All PHP code must conform to the [Drupal Coding Standards](https://www.drupal.org/docs/develop/standards/coding-standards).

## Bugs and Security Vulnerabilities

If you encounter any bug or unexpected behavior, you can either report it on Github using the bug tracker, or via email at `hello@sqweb.com`. We will be in touch as soon as possible.

If you discover a security vulnerability within SQweb or this plugin, please send an e-mail to `hello@sqweb.com`. All security vulnerabilities will be promptly addressed.

## License

Copyright (C) 2016 – SQweb

This program is free software ; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation ; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY ; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details

You should have received a copy of the GNU General Public License along with this program. If not, see <http://www.gnu.org/licenses/>.

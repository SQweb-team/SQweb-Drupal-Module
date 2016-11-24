SQweb Drupal 7 Module
===

[![Build Status](https://travis-ci.org/SQweb-team/SQweb-Drupal-Module.svg?branch=7.x-1.x)](https://travis-ci.org/SQweb-team/SQweb-Drupal-Module)

**This Module allows you to easily integrate SQweb on your Drupal v7 powered website.**

##Requirements

**This Module has been tested with PHP 5.5 and greater and with Drupal 7.51 and greater.**

We are unable to provide official support for earlier versions. For more information about end of life PHP branches, [see this page](http://php.net/supported-versions.php).

##Install

Set your SQweb Website ID and your language on your Drupal administration, in the SQweb section. 

##Usage

###1. Tagging your pages

The SQweb script is add to all your pages after you configure your SQweb Website ID (Ref: Install).

###2. Checking the credits of your subscribers

SQweb extends Twig with filters and functions to simplify the use of SQweb within your theme.

Use it like this:

```php
<?php if (SQweb::abo()) { ?>
	//CONTENT
<?php } else { ?>
	//ADS
<?php } ?>
```

###3. Showing the Multipass button

Finally, use this code to display the Multipass button on your pages:

```php
<?php echo SQweb::button(); ?>
```

If you want to use a smaller version of the Multipass button:

```php
<?php echo SQweb::button('slim'); ?>
```

###4. More functions


#### Display only a part of your content to non premium users

```php
<?php SQweb::transpartext('Your content', percent); ?>
```
`percent` is the percent of your content you want to display to everyone.

Example:

```php
<?php SQweb::transpartext('one two three four', 50); ?>
```

Will display for free users:

```
one two
```

#### Display your content later to non paying users

```php
<?php SQweb::waitToDisplay('Your content', publication_date, wait); ?>
```

1. `publication_date` is the date when your content is published on your website.
2. `wait` is the number of day you want to wait before showing this content to free users.

Example:

```php
<?php SQweb::waitToDisplay('Your content', '2016/10/01', 3); ?>
```

#### Limit the number of articles free users can read per day

```php
<?php SQweb::limitArticle('Your content', numbers_of_articles) ?>
```

`number_of_articles` is the number of articles a free user can see.

For instance, if I want to display only 5 articles to free users:

```php
<?php SQweb::limitArticle('Your content', 5) ?>
```

##Contributing

We welcome contributions and improvements.

###Coding Style

All PHP code must conform to the [Drupal Coding Standards](https://www.drupal.org/docs/develop/standards/coding-standards).

##Bugs and Security Vulnerabilities

If you encounter any bug or unexpected behavior, you can either report it on Github using the bug tracker, or via email at `hello@sqweb.com`. We will be in touch as soon as possible.

If you discover a security vulnerability within SQweb or this plugin, please send an e-mail to `hello@sqweb.com`. All security vulnerabilities will be promptly addressed.

##License

Copyright (C) 2016 – SQweb

This program is free software ; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation ; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY ; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details

You should have received a copy of the GNU General Public License along with this program. If not, see <http://www.gnu.org/licenses/>.

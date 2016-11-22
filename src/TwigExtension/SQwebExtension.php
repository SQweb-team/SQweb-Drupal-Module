<?php

namespace Drupal\sqweb\TwigExtension;

use Drupal\sqweb\Lib\SQweb;

class SQwebExtension extends \Twig_Extension {
	/**
	* Here is where we declare our new functions.
	* @return array
	*/
	public function getFunctions() {
		return array(
			new \Twig_SimpleFunction('sqw_abo',
				array('Drupal\sqweb\Lib\SQweb', 'Abo')
				// Here we are self referencing the function we use.
			),
			new \Twig_SimpleFunction('sqw_button',
					array('Drupal\sqweb\Lib\SQweb', 'Button'),
					array('is_safe' => array('html')) // Function can display HTML.
			)
		);
	}

	/**
	* Here is where we declare our new filter.
	* @return array
	*/
	public function getFilters() {
		return array(
			new \Twig_SimpleFilter('sqw_waittodisplay',
				array('Drupal\sqweb\Lib\SQweb', 'waitToDisplay'),
				array('is_safe' => array('html'))
				// Here we are self referencing the function we use to filter the string value.
			),
			new \Twig_SimpleFilter('sqw_transpartext',
				array('Drupal\sqweb\Lib\SQweb', 'Transpartext'),
				array('is_safe' => array('html'))
				// Here we are self referencing the function we use to filter the string value.
			),
			new \Twig_SimpleFilter('sqw_limitarticle',
				array('Drupal\sqweb\Lib\SQweb', 'limitArticle'),
				array('is_safe' => array('html'))
				// Here we are self referencing the function we use to filter the string value.
			),
			new \Twig_SimpleFilter('sqw_is_abo',
				array('Drupal\sqweb\Lib\SQweb', 'isAbo'),
				array('is_safe' => array('html'))
				// Here we are self referencing the function we use to filter the string value.
			)
		);
	}

	/**
	* This is the same name we used on the services.yml file
	* @return string
	*/
	public function getName() {
		return "sqweb.twig_extension";
	}
}
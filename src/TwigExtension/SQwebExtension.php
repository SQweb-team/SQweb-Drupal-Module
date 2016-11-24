<?php

namespace Drupal\sqweb\TwigExtension;

/**
 * Extenden Twig for SQweb.
 */
class SQwebExtension extends \Twig_Extension {

  /**
   * Here is where we declare our new functions.
   *
   * @return array
   *   Array of Twig_SimpleFunction
   */
  public function getFunctions() {
    return array(
      new \Twig_SimpleFunction('sqw_abo',
                array('Drupal\sqweb\Lib\SQweb', 'Abo')
                // Here we are self referencing the function we use.
      ),
      new \Twig_SimpleFunction('sqw_button',
                    array('Drupal\sqweb\Lib\SQweb', 'Button'),
                    // Function can display HTML.
                    array('is_safe' => array('html'))
      ),
    );
  }

  /**
   * Here is where we declare our new filter.
   *
   * @return array
   *   Array of Twig_SimpleFilter
   */
  public function getFilters() {
    return array(
      new \Twig_SimpleFilter('sqw_waittodisplay',
                array('Drupal\sqweb\Lib\SQweb', 'waitToDisplay'),
                array('is_safe' => array('html'))
      ),
      new \Twig_SimpleFilter('sqw_transpartext',
                array('Drupal\sqweb\Lib\SQweb', 'transpartext'),
                array('is_safe' => array('html'))
      ),
      new \Twig_SimpleFilter('sqw_limitarticle',
                array('Drupal\sqweb\Lib\SQweb', 'limitArticle'),
                array('is_safe' => array('html'))
      ),
      new \Twig_SimpleFilter('sqw_is_abo',
                array('Drupal\sqweb\Lib\SQweb', 'isAbo'),
                array('is_safe' => array('html'))
      ),
    );
  }

  /**
   * This is the same name we used on the services.yml file.
   *
   * @return string
   *   Name of twig extension
   */
  public function getName() {
    return "sqweb.twig_extension";
  }

}

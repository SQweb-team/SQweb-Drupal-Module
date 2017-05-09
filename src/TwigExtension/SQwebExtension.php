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
    return [
      new \Twig_SimpleFunction('sqw_abo',
                ['Drupal\sqweb\Lib\SQweb', 'Abo']
                // Here we are self referencing the function we use.
      ),
      new \Twig_SimpleFunction('sqw_button',
                    ['Drupal\sqweb\Lib\SQweb', 'Button'],
                    // Function can display HTML.
                    ['is_safe' => ['html']]
      ),
    ];
  }

  /**
   * Here is where we declare our new filter.
   *
   * @return array
   *   Array of Twig_SimpleFilter
   */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('sqw_waittodisplay',
                ['Drupal\sqweb\Lib\SQweb', 'waitToDisplay'],
                ['is_safe' => ['html']]
      ),
      new \Twig_SimpleFilter('sqw_transpartext',
                ['Drupal\sqweb\Lib\SQweb', 'transpartext'],
                ['is_safe' => ['html']]
      ),
      new \Twig_SimpleFilter('sqw_limitarticle',
                ['Drupal\sqweb\Lib\SQweb', 'limitArticle'],
                ['is_safe' => ['html']]
      ),
      new \Twig_SimpleFilter('sqw_is_abo',
                ['Drupal\sqweb\Lib\SQweb', 'isAbo'],
                ['is_safe' => ['html']]
      ),
    ];
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

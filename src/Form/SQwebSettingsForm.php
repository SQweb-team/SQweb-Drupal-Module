<?php

namespace Drupal\sqweb\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class SQwebSettingsForm extends ConfigFormBase {

  /**
   * This is the same name we used on the services.yml file.
   *
   * @return string
   *   id of settings form
   */
  public function getFormId() {
    return 'sqweb_admin_settings';
  }

  /**
   * This is the same name we used on the services.yml file.
   *
   * @return string
   *   config name ?
   */
  protected function getEditableConfigNames() {
    return [
      'sqweb.settings',
    ];
  }

  /**
   * Build form for SQweb admin on drupal.
   *
   * @return form
   *   form admin SQweb
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('sqweb.settings');

    $form['sqw_id_site'] = array(
      '#type' => 'textfield',
      '#title' => t('ID Site'),
      '#default_value' => $config->get('sqw_id_site'),
      '#size' => 8,
      '#maxlength' => 8,
      '#description' => t('The ID site given on SQweb'),
      '#required' => TRUE,
    );

    $form['sqw_lang'] = array(
      '#type' => 'textfield',
      '#title' => t('Lang'),
      '#default_value' => $config->get('sqw_lang'),
      '#size' => 2,
      '#maxlength' => 2,
      '#description' => t('Lang of your website (example : en, fr, es)'),
      '#required' => TRUE,
    );

    $form['sqw_message'] = array(
      '#type' => 'textarea',
      '#title' => t('Message to display at Adblockers'),
      '#default_value' => $config->get('sqw_message'),
      '#size' => 50,
      '#maxlength' => 255,
      '#description' => t('That displayed an message to adblockers on bottom of your website, empty if you want disable'),
      '#required' => FALSE,
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * Check if value is good.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!$form_state->getValue('sqw_id_site')) {
      $form_state->setErrorByName('sqw_id_site', $this->t('0 is not a valid ID Site'));
    }
    $lang = ['fr', 'en', 'es'];
    if (!in_array($form_state->getValue('sqw_lang'), $lang)) {
      $form_state->setErrorByName('sqw_lang', $this->t('Language unavailable'));
    }
  }

  /**
   * Save value after submit.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $array = $form_state->getValues();
    $this->config('sqweb.settings')
      ->set('sqw_id_site', $array['sqw_id_site'])
      ->set('sqw_lang', $array['sqw_lang'])
      ->set('sqw_message', $array['sqw_message'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}

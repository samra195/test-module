<?php

namespace Drupal\mymodule\Form;

// Classes referenced in this class:
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Messenger\MessengerInterface;

// This is the form being extended:
use Drupal\system\Form\SiteInformationForm;

/**
 * Configure site information settings for this site.
 */
class SiteInformationAlter extends SiteInformationForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Retrieve the system.site configuration
    $site_config = $this->config('system.site');

    // Get the original form from the class we are extending
    $form = parent::buildForm($form, $form_state);

    // Add a text to the site information section of the form for the apikey
    $form['site_information']['siteapikeytext'] = [
			'#type' => 'textfield',
			'#title' => t('Site API Key'),
			'#default_value' => $site_config->get('siteapikey') ?: 'No API Key yet',
			'#description' => t("Custom field to set the API Key"),
		];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('system.site');

    // The siteapikey is retrieved from the submitted form values
    // and saved to the 'siteapikey' element of the system.site configuration.
    $siteapikey_value = $form_state->getValue('siteapikeytext');
    $config->set('siteapikey', $siteapikey_value);

    // Save the configuration
    $config ->save();

	drupal_set_message($this->t('Site API Key is saved is @sitekey.'),['@sitekey' => $siteapikey_value]);

    // Pass the remaining values off to the parent form that is being extended,
    // so that that the parent form can process the values.
    parent::submitForm($form, $form_state);
  }
}

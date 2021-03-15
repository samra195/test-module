<?php

namespace Drupal\mymodule\Routing;

// Classes referenced in this class
use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class SiteApiKeyRouterSubscriber extends RouteSubscriberBase {
  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Change form for the system.site_information_settings route
    // to Drupal\describe_site\Form\DescribeSiteSiteInformationForm
    // Act only on the system.site_information_settings route.
    if ($route = $collection->get('system.site_information_settings')) {
      // Next, set the value for _form to the form override.
      $route->setDefault('_form', 'Drupal\mymodule\Form\SiteInformationAlter');
    }
  }
}

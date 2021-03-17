<?php
namespace Drupal\mymodule\Controller;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\QueryFactory;

/**
 * Contain Json API Controller
 */
class CustomPageJsonResponce extends ControllerBase {

  /**
  * Entity query factory.
  *
  * @var \Drupal\Core\Entity\Query\QueryFactory
  */
  protected $entityQuery;

 /**
 * Constructs a new CustomRestController object.
 * @param \Drupal\Core\Entity\Query\QueryFactory $entityQuery
 * The entity query factory.
 */
  public function __construct(QueryFactory $entity_query) {
    $this->entityQuery = $entity_query;
  }

  /**
  * {@inheritdoc}
  */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query')
   );
  }

  public function getJsonResponce($nodeid) {

    $response_array = [];

    $node_query = [$nodeid];

    //Set configuration variable
    $config = \Drupal::config('system.site');

    if ($node_query) {

      $nodes = $this->entityTypeManager()->getStorage('node')->loadMultiple($node_query);

      foreach ($nodes as $node) {
        //check if node is type of page
        if(!empty($node) && $node->getType() == 'page' && $config->get('siteapikey') != ""){

          //set the responce_array to display data to json
          $response_array[] = [
            'title' => $node->title->value,
          ];

          // Create the Json Response
          // updated.
          $cache_metadata = new CacheableMetadata();
          $cache_metadata->setCacheTags(['node_list']);

          // Create the JSON response object and add the cache metadata.
          $response = new CacheableJsonResponse($response_array);
          $response->addCacheableDependency($cache_metadata);

          return $response;
          }
        }

        $response_array[] = [
            'error' => 'Access Denied',
        ];

        $cache_metadata = new CacheableMetadata();
        $cache_metadata->setCacheTags(['notfound']);

        // Create the JSON response object and add the cache metadata.
        $response = new CacheableJsonResponse($response_array);
        $response->addCacheableDependency($cache_metadata);

        return $response;
    }
  }
}


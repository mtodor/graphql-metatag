<?php

namespace Drupal\graphql_metatag\Plugin\GraphQL\Fields\Entity;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use Drupal\metatag\MetatagManagerInterface;
use GraphQL\Type\Definition\ResolveInfo;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @GraphQLField(
 *   secure = true,
 *   id = "entity_metatags",
 *   name = "entityMetatags",
 *   type = "[Metatag]",
 *   description = @Translation("Loads metatags for the entity."),
 *   parents = {"Entity"}
 * )
 */
class EntityMetatags extends FieldPluginBase implements ContainerFactoryPluginInterface {
  use DependencySerializationTrait;

  /**
   * The metatag manager service.
   *
   * @var \Drupal\metatag\MetatagManagerInterface
   */
  protected $metatagManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $pluginId, $pluginDefinition) {
    return new static(
      $configuration,
      $pluginId,
      $pluginDefinition,
      $container->get('metatag.manager')
    );
  }

  /**
   * Metatags constructor.
   *
   * @param array $configuration
   *   The plugin configuration array.
   * @param string $pluginId
   *   The plugin id.
   * @param mixed $pluginDefinition
   *   The plugin definition array.
   * @param \Drupal\metatag\MetatagManagerInterface $metatagManager
   */
  public function __construct(
    array $configuration,
    $pluginId,
    $pluginDefinition,
    MetatagManagerInterface $metatagManager
  ) {
    parent::__construct($configuration, $pluginId, $pluginDefinition);
    $this->metatagManager = $metatagManager;
  }

  /**
   * {@inheritdoc}
   */
  protected function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    if ($value instanceof ContentEntityInterface) {
      $tags = $this->metatagManager->tagsFromEntityWithDefaults($value);

      // Filter non schema metatags, because schema metatags are processed in
      // EntitySchemaMetatags class.
      $elements = $this->metatagManager->generateRawElements($tags, $value);
      $elements = array_filter($elements, function ($metatag_object) {
        return !NestedArray::getValue($metatag_object, ['#attributes', 'schema_metatag']);
      });

      foreach ($elements as $element) {
        yield $element;
      }
    }
  }

}

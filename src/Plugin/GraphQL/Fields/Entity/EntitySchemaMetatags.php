<?php

namespace Drupal\graphql_metatag\Plugin\GraphQL\Fields\Entity;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * @GraphQLField(
 *   secure = true,
 *   id = "entity_schema_metatags",
 *   name = "entitySchemaMetatags",
 *   type = "[SchemaMetatag]",
 *   description = @Translation("Loads schema.org defined metatags for the entity"),
 *   parents = {"Entity"}
 * )
 */
class EntitySchemaMetatags extends EntityMetatags {

  /**
   * {@inheritdoc}
   */
  protected function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    if ($value instanceof ContentEntityInterface) {
      $tags = $this->metatagManager->tagsFromEntityWithDefaults($value);

      // Process only schema metatags.
      $elements = $this->metatagManager->generateRawElements($tags, $value);
      $elements = array_filter($elements, function ($metatag_object) {
        return NestedArray::getValue($metatag_object, ['#attributes', 'schema_metatag']) === TRUE;
      });

      foreach ($elements as $element) {
        yield $element;
      }
    }
  }

}

<?php

namespace Drupal\graphql_metatag\Plugin\GraphQL\Fields\InternalUrl;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Url;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * @GraphQLField(
 *   id = "url_schema_metatags",
 *   name = "schema_metatags",
 *   type = "[SchemaMetatag]",
 *   description = @Translation("Loads schema.org defined metatags for the URL"),
 *   parents = {"InternalUrl", "EntityCanonicalUrl"}
 * )
 */
class SchemaMetatags extends Metatags {

  /**
   * {@inheritdoc}
   */
  protected function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    if ($value instanceof Url) {
      $resolve = $this->subRequestBuffer->add($value, function () {
        $tags = metatag_get_tags_from_route();
        $tags = NestedArray::getValue($tags, ['#attached', 'html_head']) ?: [];

        $tags = array_filter($tags, function ($tag) {
          return is_array($tag) &&
            NestedArray::getValue($tag, [0, '#attributes', 'schema_metatag']) === TRUE;
        });

        return array_map('reset', $tags);
      });

      return function ($value, array $args, ResolveContext $context, ResolveInfo $info) use ($resolve) {
        $tags = $resolve();
        foreach ($tags->getValue() as $tag) {
          yield $tag;
        }
      };
    }
  }

}

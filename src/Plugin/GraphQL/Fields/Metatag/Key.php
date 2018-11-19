<?php

namespace Drupal\graphql_metatag\Plugin\GraphQL\Fields\Metatag;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * @GraphQLField(
 *   secure = true,
 *   id = "metatag_key",
 *   name = "key",
 *   type = "String",
 *   parents = {"Metatag", "SchemaMetatag"}
 * )
 */
class Key extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    if (isset($value['#tag']) && $value['#tag'] === 'meta') {
      yield isset($value['#attributes']['property']) ? $value['#attributes']['property'] :
        ((isset($value['#attributes']['http-equiv'])) ? $value['#attributes']['http-equiv'] :
          ((isset($value['#attributes']['itemprop'])) ? $value['#attributes']['itemprop'] : $value['#attributes']['name'])
        );
    }
    else if (isset($value['#tag']) && $value['#tag'] === 'link') {
      yield $value['#attributes']['rel'];
    }
  }

}

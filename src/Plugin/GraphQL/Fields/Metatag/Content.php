<?php

namespace Drupal\graphql_metatag\Plugin\GraphQL\Fields\Metatag;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * @GraphQLField(
 *   secure = true,
 *   id = "metatag_content",
 *   name = "content",
 *   type = "String",
 *   description = @Translation("The JSON encoded content of a schema metatag"),
 *   parents = {"SchemaMetatag"}
 * )
 */
class Content extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    yield json_encode($value['#attributes']['content']);
  }

}

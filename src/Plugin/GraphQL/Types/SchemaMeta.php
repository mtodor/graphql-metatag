<?php

namespace Drupal\graphql_metatag\Plugin\GraphQL\Types;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Types\TypePluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * @GraphQLType(
 *   id = "schema_meta",
 *   name = "SchemaMeta",
 *   description = @Translation("Container for schema metatag properties.")
 *   interfaces = {"SchemaMetatag"}
 * )
 */
class SchemaMeta extends TypePluginBase {

  /**
   * {@inheritdoc}
   */
  public function applies($object, ResolveContext $context, ResolveInfo $info = NULL) {
    return TRUE;
  }

}

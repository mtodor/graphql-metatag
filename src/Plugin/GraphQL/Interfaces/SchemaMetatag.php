<?php

namespace Drupal\graphql_metatag\Plugin\GraphQL\Interfaces;

use Drupal\graphql\Plugin\GraphQL\Interfaces\InterfacePluginBase;

/**
 * @GraphQLInterface(
 *   id = "schema_meta_tag",
 *   name = "SchemaMetatag",
 *   type = "schema_metatag",
 *   description = @Translation("SchemaMetatag interface containing schema metatag properties.")
 * )
 */
class SchemaMetatag extends InterfacePluginBase {

}

<?php

namespace Drupal\Tests\foia_webform\Kernel;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\KernelTests\KernelTestBase;
use Drupal\foia_webform\AgencyLookupService;
use Drupal\node\Entity\NodeType;
use Drupal\node\Entity\Node;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Entity\FieldConfig;

/**
 * Class AgencyLookupServiceTest.
 *
 * @package Drupal\Tests\foia_webform\Kernel
 */
class AgencyLookupServiceTest extends KernelTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = [
    'system',
    'user',
    'webform',
    'webform_template',
    'foia_webform',
    'field',
    'node',
    'entity_reference',
    'menu_ui',
    'field_permissions',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installConfig(['system'/*, 'webform', 'webform_template'*/]);
    $this->installSchema('user', 'users_data');
    $this->installEntitySchema('node');
    $this->installEntitySchema('user');

  }

  /**
   * Tests Agency Lookup Service.
   */
  public function testAgencyLookupService() {

    $webform = $this->getMockBuilder('Drupal\webform\Entity\Webform')
      ->disableOriginalConstructor()
      ->setMethods(['id'])
      ->getMock();
    $webform->expects($this->once())
      ->method('id')
      ->will($this->returnValue('a_test_webform'));

    $webformId = $webform->id();

    // Adds Agency Component Content type.
    $node_type_values = [
      'type' => 'agency_component',
      'name' => t('Agency Component'),
      'description' => 'An agency component to which a request can be sent and which will be fulfilling requests.',
      'third_party_settings' => [
        'menu_ui' => [
          'available_menus' => [
            'main',
          ],
          'parent' => 'main',
        ],
      ],
    ];
    $node_type = NodeType::create($node_type_values);
    $node_type->save();

    // @todo get path from variable.
    $path = '/var/www/dojfoia/config/default';
    $yml = yaml_parse(file_get_contents($path . '/field.storage.node.field_request_submission_form.yml'));
    FieldStorageConfig::create($yml)->save();
    $yml = yaml_parse(file_get_contents($path . '/field.field.node.agency_component.field_request_submission_form.yml'));
    FieldConfig::create($yml)->save();

    // Create an agency component entity.
    Node::create([
      'type' => 'agency_component',
      'title' => t('A Test Agency Component'),
      'field_portal_submission_format' => 'api',
      'field_submission_api' => 'http://atest.com',
      'field_request_submission_form' => ['target_id' => $webformId],
    ])->save();

    $etm = \Drupal::entityTypeManager();

    $lookup = new AgencyLookupService($etm);

    $return = $lookup->getComponentFromWebform($webformId);

    $title = $return->label();

    $query = \Drupal::entityQuery('node')
      ->condition('field_request_submission_form', $webformId);
    $nids = $query->execute();

    $node = Node::load($nids[1]);

    $name = $node->label();

    // Title is the same as the one returned from getComponentByWebform.
    $this->assertEquals($name, $title);

  }

}

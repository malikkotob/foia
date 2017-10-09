<?php

namespace Drupal\Tests\foia_webform\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\webform\Entity\Webform;

/**
 * Class FoiaSubmissionServiceApiTest.
 *
 * @package Drupal\Tests\foia_webform\Kernel
 */
class FoiaSubmissionServiceApiTest extends KernelTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = [
    'system',
    'node',
    'field',
    'user',
    'webform',
    'webform_template',
    'foia_webform',
  ];

  /**
   * @var
   */
  private $webform;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installConfig(['system', 'webform', 'webform_template']);
    $this->installSchema('user', 'users_data');
    $this->installEntitySchema('node');
    $this->installEntitySchema('user');

    // Mock a webform.
/*    $this->webform = $this->getMockBuilder('\Drupal\webform\Entity\Webform')
      ->disableOriginalConstructor()
      ->setMethods(['id'])
      ->getMock();
    $this->webform->expects($this->once())
      ->method('id')
      ->will($this->returnValue('a_test_webform'));*/

    $webform = Webform::create(['id' => 'a_test_webform']);
    $webform->set('foia_template', [
      '#type' => 'checkbox',
      '#title' => t("Use FOIA Agency template"),
      '#disabled' => TRUE,
      '#default_value' => 'foia_template',
      '#value' => 'foia_template',
    ]);
    $webform->save();

    // Create Agency Component.


    // Create Webform Submission.


  }

  public function testsendSubmissionToComponent() {

    $webformId = $this->webform->id();

    $this->assertEquals('a_test_webform', $webformId);

  }

}

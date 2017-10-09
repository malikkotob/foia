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
    'user',
    'webform',
    'webform_template',
    'foia_webform',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installConfig(['system', 'webform', 'webform_template']);

    // Create webform.
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

    $template = $webform->get('foia_template')->getValue();

    $this->assertEquals('foia_template', $template)

  }

}

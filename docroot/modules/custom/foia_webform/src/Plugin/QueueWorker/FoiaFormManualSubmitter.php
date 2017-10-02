<?php

namespace Drupal\foia_webform\Plugin\QueueWorker;

/**
 * Manually submit a form submission.
 *
 * @QueueWorker (
 *   id = "foia_form_manual_submitter",
 *   title = @Translation("Manual FOIA Form submitter"),
 * )
 */
class FoiaFormManualSubmitter extends WebformSubmissionInterface {}
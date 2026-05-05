<?php

namespace Drupal\webform_soft_delete;

use Drupal\webform\WebformSubmissionStorage as BaseWebformSubmissionStorage;
use Drupal\Core\Entity\EntityInterface;

/**
 * Overrides WebformSubmissionStorage to implement soft delete.
 */
class WebformSubmissionStorage extends BaseWebformSubmissionStorage {

  /**
   * {@inheritdoc}
   */
  protected function doDelete($entities) {
    /** @var \Drupal\webform\WebformSubmissionInterface[] $entities */
    foreach ($entities as $entity) {
      // Set status to 0 (soft deleted) instead of deleting.
      $entity->set('status', 0);
      $entity->save();
    }
    
    // Clear the static entity cache.
    $this->resetCache(array_keys($entities));
  }

}

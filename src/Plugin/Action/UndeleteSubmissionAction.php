<?php

namespace Drupal\webform_soft_delete\Plugin\Action;

use Drupal\views_bulk_operations\Action\ViewsBulkOperationsActionBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Undeletes a webform submission.
 *
 * @Action(
 *   id = "webform_submission_undelete_action",
 *   label = @Translation("Undelete webform submission"),
 *   type = "webform_submission",
 *   confirm = TRUE,
 * )
 */
class UndeleteSubmissionAction extends ViewsBulkOperationsActionBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    $this->executeMultiple([$entity]);
  }

  /**
   * {@inheritdoc}
   */
  public function executeMultiple(array $entities) {
    $count = 0;
    foreach ($entities as $entity) {
      if ($entity && $entity->hasField('status')) {
        $entity->set('status', 1);
        $entity->save();
        $count++;
      }
    }
    if ($count > 0) {
      return [$this->formatPlural($count, 'Undeleted 1 submission.', 'Undeleted @count submissions.')];
    }
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    $access = $object->access('update', $account, TRUE)
      ->andIf(\Drupal\Core\Access\AccessResult::allowedIfHasPermission($account, 'view soft deleted webform submissions'));
    return $return_as_object ? $access : $access->isAllowed();
  }

}

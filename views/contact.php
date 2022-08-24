<?php

/** @var $this \app\core\View
 * @var $model \app\models\ContactForm
 */

use app\core\form\TextareaField;

$this->title = 'Contact';
?>
<h1>Contact Us</h1>

<?php $form = \app\core\form\Form::begin('', 'post'); ?>
<?= $form->field($model, 'subject') ?>
<?= $form->field($model, 'email') ?>
<?= new TextareaField($model, 'body') ?>
<button type="submit" class="btn btn-primary">Submit</button>
<?php echo $form->end() ?>
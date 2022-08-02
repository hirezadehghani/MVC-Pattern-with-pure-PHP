<h1>Register</h1>

<?php $form = \app\core\form\Form::begin('', "post"); ?>
<div class="row">
  <div class="col">
    <?= $form->field($model, 'firstName') ?>
  </div>
  <div class="col">
    <?= $form->field($model, 'lastName') ?>
  </div>
</div>
<?= $form->field($model, 'email') ?>
<?= $form->field($model, 'password')->passwordField() ?>
<?= $form->field($model, 'confirmPassword')->passwordField() ?>
<button type="submit" class="btn btn-primary">Submit</button>

<?= app\core\form\Form::end() ?>
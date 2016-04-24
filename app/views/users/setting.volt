<?php use Phalcon\Tag as Tag ?>

{{ content() }}

<?= Tag::form([ $this->url->get([ 'for' => 'user-setting' ]), 'class' => 'form setting-form', 'autocomplete' => 'off' ]) ?>

	<div class="form-control">
		<label for="name">Имя</label>
		<input type="text" id="name" class="input" name="name" size="30" value="{{ user.name }}">
	</div>

	<div class="form-control">
		<label for="email">Почта</label>
		<input type="text" id="email" class="input" name="email" size="30" value="{{ user.email }}">
	</div>

	<div class="form-control">
		<?
		echo Tag::submitButton([ 'Сохранить', 'class' => 'btn' ]);
		echo Tag::linkTo([
			$this->url->get([ 'for' => 'home-link' ]),
			'Отмена',
			'class' => 'btn btn-cancle'
		]); ?>
	</div>

{{ end_form }}
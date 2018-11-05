<h1>Создать заметку</h1>
{{ form(
	url(['for': 'notes-create']),
	'class': 'form',
	'data-ajax': 'true',
	'method': 'post'
) }}
	<fieldset>
		<legend>Основное содержимое</legend>

		<div class="form-group required">
			<label class="form-label" for="title">Заголовок</label>
			{{ text_field('title', 'id': 'title', 'class': 'input input-big') }}
		</div>
		<div class="form-group required">
			<label class="form-label" for="content">Содержимое</label>
			{{ text_area('content', 'id': 'content', 'rows': 8, 'style': 'resize: vertical;') }}
		</div>
	</fieldset>

	<fieldset>
		<legend>Meta</legend>

		<div class="form-group">
			<label class="form-label" for="slug">URL</label>
			{{ text_field('slug', 'id': 'slug', 'class': 'input input-big') }}
			<span class="desc">Если будет пусто, то сгенерируется сам из заголовка</span>
		</div>
		<div class="form-group">
			<label class="form-label" for="description">Описание</label>
			{{ text_area('description', 'id': 'description', 'rows': 4, 'style': 'resize: vertical;') }}
			<span class="desc">Используется для списка заметок и meta тега</span>
		</div>
		<div class="form-group">
			<label class="form-label" for="keywords">Ключевые слова</label>
			{{ text_field('keywords', 'id': 'keywords', 'class': 'input input-big') }}
			<span class="desc">Используется для meta тега</span>
		</div>
	</fieldset>

	<fieldset>
		<legend>Опции</legend>

		<div class="form-group checkboxes">
			<label class="form-label">{{ check_field('is_comment', 'value': 1, 'checked': 'checked' ) }} Разрешить комментарии</label>
			<label class="form-label">{{ check_field('is_draft', 'value': 1 ) }} Черновик</label>
		</div>
	</fieldset>
	
	<fieldset>
		<div class="form-group">
			{{ submit_button('Сохранить', 'class': 'btn') }}
			{{ link_to(['for': 'home-link'], 'Отмена', 'class': 'btn btn-cancle') }}
		</div>
	</fieldset>

{{ end_form() }}
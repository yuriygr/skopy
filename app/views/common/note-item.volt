<div class="post">
	<div class="post-meta">
		<span>{{ note.getUser().name }}</span>
		<span>{{ note.getDate() }}</span>
		{% if auth.isLogin() %}
			<span>{{ link_to(['for': 'notes-edit', 'id': note.id], 'Изменить') }}</span>
			<span>{{ link_to(['for': 'notes-delete', 'id': note.id], 'Удалить') }}</span>
		{% endif %}
	</div>
	<h5 class="post-title">{{ link_to(['for': 'notes-link', 'slug': note.slug], note.title) }}</h5>
	<div class="post-content">
	{% if content == 'short' %}
		{{ note.description }}
	{% endif %}
	{% if content == 'full' %}
		{{ note.content }}
	{% endif %}
	</div>
</div>
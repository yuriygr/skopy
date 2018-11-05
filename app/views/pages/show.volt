<h1>{{ page.title }}</h1>
{% if auth.isLogin() %}
	<small>
		<span>{{ link_to(['for': 'page-edit', 'id': page.id], 'Изменить') }}</span>
		<span>{{ link_to(['for': 'page-delete', 'id': page.id], 'Удалить') }}</span>
	</small>
{% endif %}
{{ page.content }}

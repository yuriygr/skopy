<header>
	<div class="container">
		<ul class="header-nav inline">
			<li><b>{{ config.site.title }}</b></li>
			<li>{{ link_to(['for': 'notes-list'], 'Заметки') }}</li>
			<li>{{ link_to(['for': 'page-link', 'slug': 'about'], 'Обо мне') }}</li>
			<li>{{ link_to(['for': 'page-link', 'slug': 'projects'], 'Проекты') }}</li>
		</ul>
		{% if auth.isLogin() %}
			<ul class="header-nav inline u-right">
				<li>{{ link_to(['for': 'notes-create'], '+ Заметка') }}</li>
				<li>{{ link_to(['for': 'page-create'], '+ Страница') }}</li>
				<li>{{ link_to(['for': 'user-logout'], 'Выйти') }}</li>
			<ul>
		{% endif %}
		<div class="u-clearfix"></div>
	</div>
</header>
{{ flashSession.output() }}
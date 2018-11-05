{% for note in notes.items %}
	{{ partial('common/note-item', ['content': 'short']) }}
	{% if !loop.last %}<hr>{% endif %}
{% endfor %}
<hr>
{% if notes.first != notes.total_pages %}
	<div class="paginator">

		{% if notes.current != notes.first %}
			{% set pageBefore = ['for': 'notes-page', 'page': notes.before] %}
			{{ link_to(pageBefore, 'Предыдущая', 'class': 'paginator-item u-left' , 'rel': 'prev') }}
		{% endif %}

		{% if notes.current != notes.last %}
			{% set pageNext = ['for': 'notes-page', 'page': notes.next] %}
			{{ link_to(pageNext, 'Следующая', 'class': 'paginator-item u-right' , 'rel': 'next') }}
		{% endif %}
		
	</div>
{% endif %}
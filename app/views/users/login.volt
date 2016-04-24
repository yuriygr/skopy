{{ content() }}

{{ form( url(['for': 'user-auth']), 'class': 'form login-form', 'autocomplete': 'off' , 'data-ajax': 'true', 'method': 'post') }}
	<div class="form-control">
		{{ text_field('login', 'id': 'login', 'class': 'input', 'placeholder': 'Имя', 'autofocus') }}
	</div>
	<div class="form-control">
		{{ password_field('password', 'id': 'password', 'class': 'input', 'placeholder': 'Пароль') }}
	</div>
	<div class="form-control">
		{{ submit_button('Вход', 'class': 'btn') }}{{ link_to(['for': 'home-link'], 'Отмена', 'class': 'btn btn-cancle') }}
	</div>
{{ end_form() }}

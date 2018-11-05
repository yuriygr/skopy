<h1>Authentication</h1>
{{ form(
	url(['for': 'user-login']),
	'class': 'form login-form',
	'data-ajax': 'true',
	'method': 'post'
) }}
	<fieldset>
		<div class="form-group required">
			<label class="form-label" for="email">E-mail</label>
			{{ text_field('email', 'id': 'email', 'class': 'input input-big', 'placeholder': 'E-mail', 'autofocus') }}
		</div>
		<div class="form-group required">
			<label class="form-label" for="password">Password</label>
			{{ password_field('password', 'id': 'password', 'class': 'input input-big', 'placeholder': 'Password') }}
		</div>
	</fieldset>
	
	<fieldset>
		<div class="form-group">
			{{ submit_button('Log in', 'class': 'btn btn-big') }}
		</div>
	</fieldset>
{{ end_form() }}
{{ tag.getDocType() }}
<html lang="ru">
<head prefix="og: http://ogp.me/ns#">
	{{ tag.getCharset() }}
	{# Совместимость и правила для девайсов #}
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
	<meta name="HandheldFriendly" content="true">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	{# Стилистика для приложения #}
	{{ tag.getFavicon() }}
	<link rel="icon" type="image/png" href="/img/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="/img/favicon-16x16.png" sizes="16x16">
	<link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
	<link rel="apple-touch-icon-precomposed" sizes="180x180" href="/img/apple-touch-icon.png">
	<meta name="apple-mobile-web-app-title" content="{{config.site.title}}">
	<meta name="application-name" content="{{config.site.title}}">
	<meta name="theme-color" content="#ffffff">
	<meta http-equiv="x-dns-prefetch-control" content="on">
	<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
	{{ tag.getGenerator() }}
	{# Мета-файлы #}
	<link rel="manifest" href="/manifest.json">
	{# Мета-теги #}
	{{ tag.getTitle() }}
	{{ tag.getDescription() }}
	{{ tag.getKeywords() }}
	{# Opengrapg #}
	{{ opengraph.output() }}
	{# CSS стили #}
	{{ assets.outputCss('app-fonts') }}
	{{ assets.outputCss('app-css') }}
</head>
<body>

	{{ partial('common/header') }}

	<section class="main">
		<div class="container">
			{{ content() }}
		</div>
	</section>

	{{ partial('common/footer') }}

	{{ assets.outputJs('app-js') }}

</body>
</html>
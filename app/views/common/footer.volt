<footer>
	<div class="container">
		<span>&copy; {{ config.site.title }}, {{ date('Y') }} | Работает на <a target="_blank" href="https://github.com/yuriygr/skopy/">Skopy</a></span>
	</div>
</footer>

<script type="application/ld+json">
{
	"@context": "http://schema.org",
	"@type": "Person",
	"name": "{{ config.site.title }}",
	"jobTitle": "Web Developer",
	"url": "{{ config.site.link }}",
	"address": {
		"@type": "PostalAddress",
		"addressLocality": "Kingisepp",
		"addressRegion": "Russia"
	}
}
</script>

<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	ga('create', '{{ config.application.ga_id }}', 'auto');
	ga('send', 'pageview');
</script>
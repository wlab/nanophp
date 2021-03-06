{% block header %}
	{{ header }}
{% endblock header %}

	{% block body %}
	<body>
		<div id="body-container">
	{% endblock %}

		{% block content_container %}
			<div class="content-container">&nbsp;</div>
		{% endblock content_container %}

	{% block end_body %}
		</div>
	{% endblock %}

	{% block log_bar %}
		{{ 'log' | inc }}
	{% endblock log_bar %}

{% block footer %}
	</body>
</html>
{% endblock footer %}
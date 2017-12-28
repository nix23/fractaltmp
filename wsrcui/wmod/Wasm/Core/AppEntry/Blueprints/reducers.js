{% for sec in app.sections %}
{% for pkg in sec.packages %}
import <%pkg.lcfirstNameWithSection~"Reducers"%> from '~/wsrc/<%pkg.fullNameByAppGroupArray|join('/')%>/Store/reducers';
{% endfor %}
{% endfor %}

export default {
{% for sec in app.sections %}
    <%sec.name%>: {
        {% for pkg in sec.packages %}
        <%pkg.name%>: <%pkg.lcfirstNameWithSection~"Reducers"%>,
        {% endfor %}
    },
{% endfor %}
};
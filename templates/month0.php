{% extends "html.php" %}

{% block content %}
  <h3>This month table </h3>
  <table class="datasheet">
    <tbody>
      <tr>
        <th>Смена</th>
        <th>Начало смены</th>
        <th>Конец смены</th>
        <th>Заезд</th>
        <th>Генеральная уборка</th>
        <th>Текущая уборка</th>
        <th>Сумма</th>
      </tr>
      {% if month %}
        {% for day in month %}
          <tr>
            {% for column in day %}
              {% if column != 'Total' %}
                <td>{{ column }}</td>
              {% endif %}
            {% endfor %}
          </tr>
        {% endfor %}
      {% endif %}
    </tbody>
  </table>
{% endblock %}

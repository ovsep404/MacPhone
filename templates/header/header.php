
{% block stylesheets %}
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/index.css" rel="stylesheet">
{% endblock %}

<div class="header">
  <div class="menu">
    <!-- Logo au centre -->
    <div class="logo">
      <a href="{{ path('app_home') }}">
        <img src="{{ asset('images/logo.png') }}" alt="MacPhone Logo">
      </a>
    </div>

    <!-- Icônes à droite -->
    <div class="icons-right">
    <a href="{{ path('app_shop') }}">
        <img src="{{ asset('images/shop-bag-thin.848x1024.png') }}" alt="Shop">
      </a>
      <a href="{{ path('cart_index') }}">
        <img src="{{ asset('images/shopping-cart.png') }}" alt="Panier">
        {% if app.session.get('panier') is defined and app.session.get('panier')|length > 0 %}
            <span class="cart-count">{{ app.session.get('panier')|length }}</span>
        {% endif %}
      </a>
      {% if is_granted('ROLE_ADMIN') %}
    <a href="{{ path('admin_dashboard') }}"> <!-- Link to the admin space -->
      <img src="{{ asset('images/admin.png') }}" alt="Admin Space">
    </a>
  {% elseif is_granted('ROLE_USER') %}
    <a href="{{ path('user_dashboard') }}"> <!-- Link to the user space -->
      <img src="{{ asset('images/user.png') }}" alt="User Space">
    </a>
  {% else %}
    <a href="{{ path('app_login') }}"> <!-- Link to the login page -->
      <img src="{{ asset('images/login-icon.png') }}" alt="Connexion">
    </a>
  {% endif %}
</div>
  </div>
</div>


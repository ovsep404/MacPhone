
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
      <a href="{{ path('cart_page') }}">
        <img src="{{ asset('images/shopping-cart.png') }}" alt="Panier">
      </a>
      <a href="{{ path('login_page') }}">
        <img src="{{ asset('images/login-icon.png') }}" alt="Connexion">
      </a>
    </div>
  </div>
</div>


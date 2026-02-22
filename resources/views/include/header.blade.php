<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">{{config('app.name')}}</a> <!-- Παει στο config στο app.php και περνει το δεδοεμενο που βρισκεται στο name -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        @auth
        <li class="nav-item">
          <a class="nav-link" href="{{route('logout')}}">Log out</a>
        </li>
        @else
        <li class="nav-item">
          <a class="nav-link" href="{{route('login')}}">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('registration')}}">Registration</a>
        </li>
        @endauth
      </ul>
      <span class="navbar-text">
        @auth
          {{auth()->user()->name}} <!-- Αυτο θα κανει print τον logged in user -->
        @endauth <!-- Με αυτην την εντολη θα δειξει τον user μονο αν ειναι συνδεδεμενος για να μην προκυψει error -->
      </span>
    </div>
  </div>
</nav>
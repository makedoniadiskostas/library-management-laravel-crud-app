@extends('layout')
@section('title', "Home Page")
@section('content')
    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        </div>
    @endif
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    
    
        <form action="/create-book" method="POST">
            @csrf <!-- είναι ένα security protection που προστατεύει τη φόρμα σου από επιθέσεις που λέγονται CSRF attacks (Cross-Site Request Forgery).
                        Χωρίς CSRF protection, κάποιος hacker θα μπορούσε να στείλει fake request εκ μέρους του χρήστη. -->
            <div class="container mt-5">
                <div class="card shadow-sm">
                     <div class="card-body">
                        <h4 class="card-title mb-4">
                            Insert a new Book
                        </h4>
                        <form action="/create-book" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title">Book Title</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Enter book title">
                        </div>

                        <div class="form-group">
                            <label for="body">Book Description</label>
                            <textarea name="body" id="body" class="form-control" rows="4" placeholder="Enter book description"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="categories">Categories</label>
                            <select name="categories[]" id="categories" class="form-control select2" multiple>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary">Save Book</button>
                        </form>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="container mt-5">
        <h2 class="mb-4">All Books</h2>

        <h1>Λίστα Βιβλίων</h1>

        {{-- CATEGORY FILTER --}}
        <div class="mb-4">
            <h4 class="mb-3">Κατηγορίες:</h4>
            <a href="{{ route('books-index') }}" class="badge badge-pill badge-secondary mr-2 mb-2 p-2">
            Όλα
            </a>

            @foreach($categories as $category)
                <a href="{{ route('books-index', ['category' => $category->id]) }}" class="badge badge-pill badge-primary mr-2 mb-2 p-2">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
        <hr>


        {{-- SORT --}}
        <form method="GET" action="/sort-books" style="margin-bottom: 20px;">
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <select name="sort" onchange="this.form.submit()" style="padding: 6px;">           
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                        Newest Sort
                    </option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                        Oldest Sort
                    </option>
                </select>
            </div>
        </form>
        <hr>

        {{-- SEARCH BAR --}}
        <div class="col-md-6">
            <div class="form-group">
                <form method="GET" action="/search">
                    <div class="input-group">
                        <input class="form-control" name="search" placeholder="Search...">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>
        </div>


        @forelse($books as $book)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">
                    {{ $book->title }}
                </h5>
                <div class="mb-2">
                    @foreach($book->categories as $category) <!-- Εδω μπορουμε να το γραψουμε ετσι, αφου στην συναρτηση bookCreate στον BookController κανουμε attach τον πινακα categories στο object book -->
                        <span class="badge bg-primary me-1">
                            {{ $category->name }} <!-- Δειχνουμε την κατηγορια καθε βιβλιου -->
                        </span>
                    @endforeach
                </div>
                <h6 class="card-subtitle mb-2 text-muted">
                    by {{ $book->user->name }}
                </h6>
                <hr>
                <p class="card-text">
                    {{ $book->body }}
                </p>
                <a href="/edit-book/{{$book->id}}" class="btn btn-sm btn-warning">Edit</a>
                <form action="/delete-book/{{$book->id}}" method="POST"  class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger"> Delete </button>
                </form>
            </div>
        </div>
        @empty
            <div class="alert alert-info mb-5">
                Δεν υπάρχουν βιβλία σε αυτή την κατηγορία.
            </div>
        @endforelse
        {{ $books->links() }}
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Choose categories",
                allowClear: true
            });
        });
    </script>
@endsection
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

</head>
<body>

<div class="container mt-5" style="max-width:700px;">
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title mb-4">
                Edit Book
            </h4>
            <form action="/edit-book/{{$book->id}}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Book Title</label>
                    <input type="text" name="title" value="{{$book->title}}" class="form-control">
                </div>
                <div class="form-group">
                    <label>Book Description</label>
                    <textarea name="body"
                              class="form-control"
                              rows="4">{{$book->body}}</textarea>
                </div>
                <div class="form-group">
                    <label>Categories</label>
                    <select name="categories[]" id="categories" class="form-control select2" multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <button class="btn btn-primary"> Save Changes </button>
                <a href="/" class="btn btn-secondary"> Cancel </a>
            </form>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
$(document).ready(function() {

    $('.select2').select2({
        placeholder: "Choose categories",
        width: '100%'
    });

});
</script>
</body>
</html>
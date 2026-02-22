<?php

namespace App\Http\Controllers;

use App\Models\Book; // Παλι μεσω terminal οπως και το model User δημιουργησα το model Post και το εκανα link με το πινακα posts του database
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function createBook(Request $request) { // Το Request εδω φερνει τα δεδομενα απο την συμπληρωμενη φορμα html

        if (!auth()->check()) {
            return redirect('/login')->with('error', 'You must log in first to create a book.'); // Σε περιπτωση που παει καποιος να κανει create book χωρις να ειναι logged in του ερχεται error μηνυμα
        }
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required',
            'categories' => 'required|array'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();
        $book = Book::create([
            'title' => $incomingFields['title'],
            'body' => $incomingFields['body'],
            'user_id' => $incomingFields['user_id']
        ]);

        $book->categories()->attach($incomingFields['categories']);
        
        return redirect('/');
    }





    public function showCreateScreen() {
        $categories = Category::all();
        $books = Book::paginate(5);
        return view('welcome', compact('categories', 'books'));
    }






    public function showEditScreen(Book $book){ // Εφοσον το $post εδω ταιριαζει με το dynamic μερος του edit url, η Laravel αυτοματα θα κανει τον link με το post στο database
        
        if(!auth()->check()){
            return redirect('/login')->with('error', 'You must log in first to create a book.');
        }
        elseif (auth()->user()->id !== $book['user_id']){ //μόνο ο user που δημιούργησε το book μπορεί να το κάνει edit.
            return redirect('/')->with('error', 'You can not edit or delete a book that was not inserted by you!');
        }

        $categories = Category::all(); // Παιρνει ολες τισ κατηγοριες
        
        return view('edit-book', ['book' => $book,
                                  'categories' => $categories
                                  ]); // Επιστρεφει το view edit-post μαζι με το δεδομενο $post που θελουμε να περασουμε μεσα στο edit-post.blade template
                                                     // ['post' => $post] Αυτο θα πει δημιούργησε μια μεταβλητή στο Blade που λέγεται $post και βάλε μέσα της το $post object από τον controller
    }






    public function actuallyUpdateBook(Book $book, Request $request){ // Το $post μας δινει το blog post που θελουμε να κανουμε update και το $request μας δινει το incoming form data δηλ το περιεχομενο του καθε πεδιου
        if (auth()->user()->id !== $book['user_id']){ // Σε περιπτωση που ενας user παει να κανει edit ενα post που δεν ειναι δικο του γινεται redirect στο home
            return redirect('/');
        }

        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']); // Η μεθοδος strip_tags αφαιρει ολα τα tags οπως <b>, <p>, <a>, <script>, <div> κτλ.
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $book->update($incomingFields);
        return redirect('/');
    }





    public function deleteBook(Book $book) {
        if (auth()->user()->id === $book['user_id']){ // Ενας user μπορει να κανει delete ενα post μονο αν ειναι δικο του αλλιως γινεται redirect στο home
            $book->delete();
            return redirect('/');
        }
        return redirect('/')->with('error', 'You can not edit or delete a book that was not inserted by you!');;
    }





    public function searchBook(Request $request) {

        $search = $request->search; // πάρε την τιμή του πεδίου search από το HTTP request

        $books = Book::where(function($query) use($search){ // Αυτό δημιουργεί ένα query που θα ψάξει στον πίνακα books και βρισκει βιβλία που ταιριάζουν με κάποια συνθήκη

            $query->where('title', 'like', "%$search%") // Το like "%$search%" σημαίνει: βρες βιβλία όπου το title ή το body περιέχει το search term
            ->orWhere('body', 'like', "%$search%");
        })
        ->orWhereHas('categories', function($query) use($search){
            $query->where('name', 'like', "$search"); // βρες βιβλία που έχουν category με name που ταιριάζει
        })
        ->orWhereHas('user', function($query) use($search){
            $query->where('name', 'like', "$search");
        })
        ->paginate(5);

        $categories = Category::all();
        return view('welcome', compact('books', 'search', 'categories')); // Το compact('books', 'search', 'categories') στέλνει: ['books' => $books,'search' => $search, 'categories' => $categories] στο view welcome.blade.php

    }


    public function categoryFilter(Request $request) {

        $categories = Category::all();
        $books = Book::query(); // δημιουργείς ένα Query Builder object, δηλαδή ένα "builder" που θα φτιάξει το SQL query. Ειναι δηλαδη εντολή προς τη βάση δεδομένων για να φέρεις, φιλτράρεις ή αλλάξεις δεδομένα.

        if ($request->category) { // Ελέγχει αν υπάρχει parameter category στο URL.
            $books->whereHas('categories', function ($query) use ($request) { //Φέρε μόνο τα βιβλία που έχουν category με id = αυτό που δόθηκε
            $query->where('categories.id', $request->category);
            }); // Αυτό προσθέτει φίλτρο στο query: Φέρε μόνο τα βιβλία που έχουν αυτό το συγκεκριμένο category_id
        }

        $books = $books->paginate(5);

        return view('welcome', compact('books', 'categories'));
    }


    public function sortBooks(Request $request) {
        
        $categories = Category::all();
        $query = Book::query();

        if ($request->sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        }
        else {
            $query->orderBy('created_at', 'desc');
        }

        $books = $query->paginate(5)->appends($request->query()); // Το appends() χρησιμοποιείται στο Laravel pagination για να κρατάει τα query parameters (π.χ. sort, search, category) όταν αλλάζεις σελίδα.

        return view('welcome', compact('books', 'categories'));

    }
}

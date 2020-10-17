<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->middleware('auth', ['except' => ['apiBooks', 'store','destroy','patch','update']]);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $books = $user->books;
        return view('book/index', ['books' => $books]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function apiBooks($user_id, $api_secret)
    {
        $user = User::findOrFail($user_id);

        if($user->api_secret == $api_secret){
        $books = $user->books;

        return response()->json([
            'user' => $user,
            'books' => $books,
        ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,[
            'title' => 'required|max:255',
            'author' => 'min:3',
        ]);

        $user = User::findOrFail($request->user_id);

        if($user->api_secret == $request->api_secret){

            $book = new Book();
            $book->title = $request->title;
            $book->user_id = $request->user_id;
            $book->isbn = $request->isbn;
            $book->author = $request->author;
            $book->description = $request->description;
            $book->page_count = $request->page_count;
            $book->thumbnail = ($request->thumbnail) ? $request->thumbnail : asset('storage/book.png');;

            //if successful
            if($book->save()) {
                return response()->json($book);
            } else {
                return response()->json(['error' => 'invalid'], 422);
            }

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($request->user_id);

        if($user->api_secret == $request->api_secret){
            Book::destroy($id);
        }
    }
}

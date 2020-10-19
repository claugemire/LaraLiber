<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Display a listing of books by user.
     *
     * @param \Illuminate\Http\Request $request
     * @param  integer $user_id
     * @param  string $api_secret
     * @return \Illuminate\Http\Response JSON
     */
    public function apiBooks(Request $request, $user_id, $api_secret)
    {
        $user = User::findOrFail($user_id);

        if ($user->api_secret == $api_secret) {
            $order_by = ($request->order_by) ? $request->order_by : 'order';

            switch ($request->dir) {
                case 'desc':
                    $books = $user->books->sortByDesc($order_by);
                    break;
                default:
                    $books = $user->books->sortBy($order_by);
                    break;
            }

            return response()->json([
                'books' => $books,
                'user' => $user,
            ]);
        } else {
            return response('unauthorized', 302)
                  ->header('Content-Type', 'text/plain');
        }
    }

    /**
     * Store a newly created book in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response JSON
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'author' => 'min:3',
        ]);

        $user = User::findOrFail($request->user_id);

        if ($user->api_secret == $request->api_secret) {
            $book = new Book();
            $book->title = $request->title;
            $book->user_id = $request->user_id;
            $book->isbn = $request->isbn;
            $book->author = $request->author;
            $book->description = $request->description;
            $book->page_count = $request->page_count;
            $book->thumbnail = ($request->thumbnail) ? $request->thumbnail : asset('storage/book.png');

            if ($book->save()) {
                return response()->json($book);
            } else {
                return response()->json(['error' => 'invalid'], 422);
            }
        } else {
            return response('unauthorized', 302)
                  ->header('Content-Type', 'text/plain');
        }
    }

    /**
     * Updates the read status of a book specified by $id
     *
     * @param \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response JSON
     */
    public function patchStatus(Request $request, $id)
    {
        $user = User::findOrFail($request->user_id);

        if ($user->api_secret == $request->api_secret) {
            $book = Book::findOrFail($id);

            $book->read = ($book->read == 0) ? 1 : 0;

            $book->save();

            return response()->json($book->read);
        } else {
            return response('unauthorized', 302)
                  ->header('Content-Type', 'text/plain');
        }
    }

    /**
     * Update the order field of all the books inside of the $request param
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response JSON format
     */
    public function patchAllOrders(Request $request)
    {
        $bookOrder = $request->toArray();
        $orderIndex = 0;
        foreach ($bookOrder as $orderNum) {
            $book = Book::findOrFail($orderNum);
            $book->order = $orderIndex;
            $book->save();
            $orderIndex++;
        }

        return response()->json($bookOrder);
    }

    /**
     * Remove the specified book from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response JSON format
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($request->user_id);

        if ($user->api_secret == $request->api_secret) {
            if(Book::destroy($id)){
                return response()->json(['message' => 'success']);
            }
        } else {
            return response('unauthorized', 302)
                  ->header('Content-Type', 'text/plain');
        }
    }
}

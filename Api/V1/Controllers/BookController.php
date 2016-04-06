<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use JWTAuth;
use App\Book;
use Dingo\Api\Routing\Helpers;

class BookController extends Controller
{
    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Book::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $book = new Book;

        $book->title = $request->get('title');
        $book->author = $request->get('author');
        $book->country = $request->get('country');
        $book->language = $request->get('language');
        $book->price = $request->get('price');
        $book->quantity = $request->get('quantity');

        if($currentUser->books()->save($book))
            return $this->response->created();
        else
            return $this->response->error('could_not_create_book', 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();

        $book = $currentUser->books()->find($id);

        if(!$book)
            throw new NotFoundHttpException; 

        return $book;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();

        $book = $currentUser->books()->find($id);
        if(!$book)
            throw new NotFoundHttpException;

        $book->fill($request->all());

        if($book->save())
            return $this->response->noContent();
        else
            return $this->response->error('could_not_update_book', 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();

        $book = $currentUser->books()->find($id);

        if(!$book)
            throw new NotFoundHttpException;

        if($book->delete())
            return $this->response->noContent();
        else
            return $this->response->error('could_not_delete_book', 500);
    }
}

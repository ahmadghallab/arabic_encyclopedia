<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use Intervention\Image\Facades\Image;

use App\Article;

class ArticleController extends Controller
{
  public function __construct()
  {
    $this->middleware('jwt.auth', [
      'only' => ['store', 'update', 'destroy']
    ]);
  }

  public function index()
  {
    $articles = Article::all();
    return response()->json($articles);
  }

  public function show($id)
  {
    $article = Article::where('id', $id)->first();

    if ($article) {
      return response()->json($article);
    }

    return response()->json(['message' => 'Not Found.'], 404);
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'title' => 'required',
      'body' => 'required'
    ]);

    $data = $request->all();
    $data['user'] = $request->user;

    if ($request->hasFile('image') && $request->file('image')->isValid()) {
      $file = $request->file('image');
      $extension = Carbon::now() . '_' . $file->getClientOriginalName();
      $thumb = Image::make($file->getRealPath())->resize(100, 100, function ($constraint) {
        $constraint->aspectRatio(); //maintain image ratio
      });
      $destinationPath = './uploads/article_images';
      $file->move($destinationPath, $extension);
      $thumb->save($destinationPath.'/thumb_'.$extension);
      $data['image'] = $extension;
    }

    $article = new Article($data);

    if ($article->save()) {
      return response()->json($article, 201);
    }

    return response()->json(['msg' => 'Something went wrong'], 500);
  }
}
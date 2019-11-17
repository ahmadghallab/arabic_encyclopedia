<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
 
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

use App\Article;

class ArticleController extends Controller
{
  public function __construct()
  {
    $this->middleware('jwt.auth', [
      'only' => ['store', 'update', 'destroy', 'updateArticleImage']
    ]);
  }

  public function index()
  {
    $random_articles = Article::select('id','title', 'summary', 'image', 'topic')->with('topic')->inRandomOrder()->limit(3)->get();
    $ordered_articles = Article::select('id','title', 'summary', 'image', 'topic')->with('topic')->paginate(21);
    return response()->json([
      'ordered_articles' => $ordered_articles,
      'random_articles' => $random_articles
    ]);
  }

  public function show($id)
  {
    $article = Article::where('id', $id)->first();

    if ($article) {
      return response()->json($article);
    }

    return response()->json(['message' => 'Not Found.'], 404);
  }

  public function showWithRelated($id)
  {
    $article = Article::with(['topic', 'user'])->where('id', $id)->first();
    $related_articles = Article::where('id', '!=', $id)->where('topic', $article->topic)->limit(6)->get();

    if ($article) {
      return response()->json([
        'article' => $article,
        'related_articles' => $related_articles
      ]);
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

    $article = new Article($data);

    if ($article->save()) {
      return response()->json(['article_id' => $article->id]);
    }

    return response()->json(['msg' => 'Something went wrong'], 500);
  }

  public function getArticleImage($image_name)
  {
    try {
      $path = './uploads/article_images/'.$image_name;
      return new BinaryFileResponse($path);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Not Found.'], 404);
    }
  }

  public function updateArticleImage(Request $request, $id)
  {
    $article = Article::findOrFail($id);
    $action = $request->input('action');
    $destinationPath = './uploads/article_images';

    if ($action == 'delete') {
      if ($article['image'] && file_exists($destinationPath . '/' . $article->image)) {
        unlink($destinationPath . '/' . $article->image);
      }
      $article->image = null;
    } else {
      if ($request->hasFile('image') && $request->file('image')->isValid()) {
        $file = $request->file('image');
        $extension = Carbon::now() . '_' . $file->getClientOriginalName();
        $extension = preg_replace('/[^A-Za-z0-9-.]+/', '-', $extension);
        $new_image = Image::make($file->getRealPath())->resize(750, 350, function ($constraint) {
          $constraint->aspectRatio(); //maintain image ratio
        });
        $new_image->save($destinationPath . '/' . $extension);
      }
      $article->image = $extension;
    }

    if ($article->update()) {
      return response()->json(['image' => $article->image]);
    }

    return response()->json(['msg' => 'Something went wrong'], 500);
  }
  
  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'title' => 'required',
      'body' => 'required'
    ]);

    $article = Article::findOrFail($id);
    $data = $request->except(['token', 'image', 'user']);

    if ($article->update($data)) {
      return response()->json(['msg' => 'resource has been updated'], 201);
    }

    return response()->json(['msg' => 'Something went wrong'], 500);
  }
  public function destroy($id)
  {
    $article = Article::findOrFail($id);
    
    $destinationPath = './uploads/article_images';

    if ($article['image'] && file_exists($destinationPath . '/' . $article->image)) {
      unlink($destinationPath . '/' . $article->image);
    }

    if ($article->delete()) {
      return response()->json(['message' => 'Article deleted.'], 204);
    }

    return response()->json(['message' => 'Something went wrong!'], 500);
  }
}
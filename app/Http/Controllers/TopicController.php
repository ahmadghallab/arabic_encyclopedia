<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Topic;

class TopicController extends Controller
{
  public function __construct()
  {
    $this->middleware('jwt.auth', [
      'only' => ['store', 'update', 'destroy']
    ]);
  }

  public function index()
  {
    $topics = Topic::all();
    return response()->json($topics);
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'title' => 'required'
    ]);

    $title = $request->input('title');
    
    $topic = new Topic(['title' => $title]);

    if ($topic->save()) {
      return response()->json($topic, 201);
    }

    return response()->json(['message' => 'Something went wrong!'], 500);
  }

  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'title' => 'required'
    ]);
    
    $title = $request->input('title');

    $topic = Topic::findOrFail($id);
    $topic->title = $title;

    if ($topic->update()) {
      return response()->json($topic);
    }

    return response()->json(['message' => 'Something went wrong!'], 500);
  }

  public function destroy($id)
  {
    $topic = Topic::findOrFail($id);
    if ($topic->delete()) {
      return response()->json(['message' => 'Topic deleted.'], 204);
    }

    return response()->json(['message' => 'Something went wrong!'], 500);
  }
}
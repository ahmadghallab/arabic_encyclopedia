<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['title', 'image', 'keywords', 'user', 'topic', 'body', 'summary', 'references', 'views', 'published'];

  public function topic()
  {
    return $this->belongsTo('App\Topic', 'topic');
  }

  public function user()
  {
    return $this->belongsTo('App\User', 'user');
  }
}
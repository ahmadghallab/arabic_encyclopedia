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
  protected $fillable = ['title', 'image', 'tags', 'user', 'topic', 'body'];

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = ['updated_at'];

  public function topic()
  {
    return $this->belongsTo('App\Topic', 'topic');
  }

  public function user()
  {
    return $this->belongsTo('App\User', 'user');
  }
}
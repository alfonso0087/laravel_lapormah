<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Laravel\Sanctum\Contracts\HasAbilities;

class PersonalAccessToken extends Model implements HasAbilities
{
  protected $connection = 'mongodb';
  protected $collection = 'personal_access_tokens';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'token',
    'abilities',
  ];

  /**
   * Get the tokenable model that the access token belongs to.
   *
   * @return \Illuminate\Database\Eloquent\Relations\MorphTo
   */
  public function tokenable()
  {
    return $this->morphTo('tokenable');
  }

  /**
   * Find the token instance matching the given token.
   *
   * @param  string  $token
   * @return static|null
   */
  public static function findToken($token)
  {
    if (strpos($token, '|') === false) {
      return static::where('token', hash('sha256', $token))->first();
    }

    [$id, $token] = explode('|', $token, 2);

    return static::where('id', $id)->where('token', hash('sha256', $token))->first();
  }

  /**
   * Determine if the access token has the given ability.
   *
   * @param  string  $ability
   * @return bool
   */
  public function can($ability)
  {
    return in_array('*', $this->abilities) ||
      in_array($ability, $this->abilities);
  }

  /**
   * Determine if the access token has any of the given abilities.
   *
   * @param  array  $abilities
   * @return bool
   */
  public function cant($abilities)
  {
    return ! $this->can($abilities);
  }

  /**
   * Determine if the access token has any of the given abilities.
   *
   * @param  array  $abilities
   * @return bool
   */
  public function canAny(array $abilities)
  {
    foreach ($abilities as $ability) {
      if ($this->can($ability)) {
        return true;
      }
    }

    return false;
  }

  
}

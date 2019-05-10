<?php declare(strict_types=1);
namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class Redrabbit 
{
  public static function userLogin($email, $password): array
  {
    $userInfo = DB::select('CALL user_login_sps(?, ?)', [$email, $password]);
    return is_array($userInfo) ? $userInfo : [ ];
  }

  public static function getForumPosts(int $forumId = 0): array
  {
    $posts = DB::select('CALL forum_sps(?)', [$forumId]);
    return is_array($posts) ? $posts : [ ];
  }
}
?>
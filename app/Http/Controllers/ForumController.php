<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use App\Redrabbit;

class ForumController extends Controller
{
  private $rules = ['description'=> 'required'];
  private const EMAIL_ERROR_MESSAGE = [[
    'success'=> 0,
    'message'=>'Unknown user! Please make you are signed in and try again.'
  ]];
  
  public function index(Request $request): Object 
  {
    $forumPosts =  Redrabbit::getForumPosts();
    $forumOutput = [
      'forumOutput'=> self::displayForumPosts( $forumPosts, $request),
      'forumData'=> $forumPosts
    ];
    return view('forum/index', $forumOutput);
  }

  public function create(): String
  {
    return view('forum/create');
  }

  public function store(Request $request): Object
  {
    if(!Input::get('forum_id'))
      $this->rules['title'] = 'required';
    
    $validate = $request->validate($this->rules);
    
    if(!$request->cookie('email'))
    {
      if(Input::get('is_ajax'))
      {
        return response(json_encode(static::EMAIL_ERROR_MESSAGE))->header('Content-Type', 'application/json');
      }
      else
        return view('forum/create', static::EMAIL_ERROR_MESSAGE);
    }

    $input = [
      'title'=> Input::get('title'),
      'description'=> Input::get('description'),
      'email'=> $request->cookie('email'),
      'forum_id'=> Input::get('forum_id')
    ];
    $add = DB::select('CALL forum_spi(:title, :description, :email, :forum_id)', $input);
    
    $response = is_array($add) && !empty($add) ? [ (array) $add[0] ] : [ ['success'=>0, 'message'=> 'An unknown error has occurred!'] ];
    if(!empty($add))
    {
      if(empty($response[0]['message']))
        $response[0]['message'] = 'Creating post failed!';
      
      if(Input::get('is_ajax'))
        return response(json_encode($response))->header('Content-Type', 'application/json');
      else
        return view('forum/create', $response);
    }
    if(Input::get('is_ajax'))
    {
      if(empty($response[0]['message']))
        $response[0]['message'] = 'THANK YOU!';
      return response($response)->header('Content-Type', 'application/json');
    }
    else
      return view('forum/thank-you');
    
  }

  public function update(int $forumId, Request $request): Object
  {
    if(!$request->cookie('email'))
    {
      return response(json_encode(static::EMAIL_ERROR_MESSAGE));
    }
    else if($forumId <= 0)
    {
      $response = [[
        'success'=> 0,
        'message'=> self::getForumIdInvalidMessage($forumId)
      ]];
      return response(json_encode($response))->header('Content-Type', 'application/json');
    }
    else if(!Input::get('description'))
    {
      $response = [[
        'name'=>'description',
        'success'=> 0,
        'message'=> 'Required'
      ]];
      return response(json_encode($response))->header('Content-Type', 'application/json');
    }
    
    $params = [
      'title'=> Input::get('title'),
      'description'=> Input::get('description'),
      'email'=> $request->cookie('email'),
      'forum_id'=> $forumId,
    ];
    // NEXT: Update database with [$params]
    $update = DB::select('CALL forum_spu(:title, :description, :email, :forum_id)', $params);
    $response = is_array($update) && !empty($update) ? [ (array) $update[0] ]: [];
    if($response[0]['success'] == 0)
      $response[0]['message'] = 'Internal error occurred! You may try refreshing the page and re-submitting your changes.';
    else 
      $response[0]['message'] = 'Thank you! Your changes saved successfully.';
    return response(json_encode($response))->header('Content-Type', 'application/json');
  }

  public function destroy(int $forumId, Request $request): Object
  {
    if(!$request->cookie('email'))
    {
      return response(json_encode(static::EMAIL_ERROR_MESSAGE));
    }
    else if($forumId <= 0)
    {
      $response = [[
        'success'=> 0,
        'message'=> self::getForumIdInvalidMessage($forumId)
      ]];
      return response(json_encode($response))->header('Content-Type', 'application/json');
    }

    
    $params = [
      'email'=> $request->cookie('email'),
      'forum_id'=> $forumId
    ];
    // NEXT: Update database with [$params]
    $update = DB::select('CALL forum_spd(:email, :forum_id)', $params);
    $response = is_array($update) && !empty($update) ? [ (array) $update[0] ]: [];
    if($response[0]['success'] == 0)
      $response[0]['message'] = 'Internal error occurred! You may try refreshing the page and re-submitting your changes.';
    else 
      $response[0]['message'] = 'Your post was deleted successfully.';
    return response(json_encode($response))->header('Content-Type', 'application/json');
  }

  public static function displayForumPosts(array $forumPosts, Request $request): String
  {
    $viewRoot = $_SERVER['DOCUMENT_ROOT'].'/../resources/views/forum/';
    $parentT = \file_get_contents($viewRoot.'post-parent.blade.php');
    $childT = \file_get_contents($viewRoot.'post-child.blade.php');
    $controlsT = \file_get_contents($viewRoot.'post-controls.blade.php');
    $cookieEmail = $request->cookie('email');
    
    $output = '';
    if(!empty($forumPosts))
    {
      foreach($forumPosts AS $row=>$item)
      {
        if(!$item->{'parent_forum_id'})
        {
          $content = $parentT;
          $controls = $cookieEmail && $cookieEmail == $item->email ? $controlsT : '';
          $content = str_replace('[@postControlsPlaceholder]', $controls, $content);
          $content = self::doReplace((array) $item, $content);  
        
          //Attach Children Replies;
          $child = '';
          foreach($forumPosts AS $row2=>$item2)
          {
            if($item2->parent_forum_id == $item->forum_id)
            {
              $controls = $cookieEmail && $cookieEmail == $item2->email ? $controlsT : '';
              $child .= str_replace('[@postControlsPlaceholder]', $controls, $childT); 
              $child = self::doReplace((array) $item2, $child);
            }
          }
          $content = str_replace('[@postReplyPlaceholder]', $child, $content);
          //END children;

          $output .= $content;
        }
      }
    }
    return $output;
  }

  public static function doReplace(array $assocArray, String $content): String
  {
    foreach($assocArray AS $key=>$value)
    {
      $value = preg_replace('/\n|\r/', '<br />', $value);
      $content = str_replace('[@'.$key.']', $value, $content);
    }
    return $content;
  }

  public static function getForumIdInvalidMessage(int $forumId): String
  {
    return 'Internal Error: ForumId [' .$forumId .'] is invalid. Try refreshing the page and re-submitting your post.';
  }
}

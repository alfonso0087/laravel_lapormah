<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use ResponseFormatter;

class PostController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $post = Post::all();
    if (count($post) > 0) {
      return ResponseFormatter::success(
        ['data' => $post],
        'Data retrieved successfully'
      );
    } else {
      return ResponseFormatter::error(
        ['message' => 'Data not found'],
        'Data not found',
        404
      );
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $name = $request->name;
    $detail = $request->detail;

    $post = Post::where('name', $name)->first();
    if ($post) {
      return ResponseFormatter::error(
        ['message' => 'Name already exists'],
        'Create failed',
        503
      );
    }

    if ($name == null || $detail == null) {
      return ResponseFormatter::error(
        ['message' => 'Name or detail is required'],
        'Create failed',
        503
      );
    } else {
      $post = Post::create([
        'name' => $name,
        'detail' => $detail
      ]);
      return ResponseFormatter::success(
        ['data' => $post],
        'Data retrieved successfully'
      );
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request)
  {
    $id = $request->id;
    $post = Post::find($id);
    if ($post) {
      return ResponseFormatter::success(
        ['data' => $post],
        'Data retrieved successfully'
      );
    } else {
      return ResponseFormatter::error(
        ['message' => 'Data not found'],
        'Data not found',
        404
      );
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request)
  {
    // return $id;
    $id = $request->id;
    $name = $request->name;
    $detail = $request->detail;

    $post = Post::find($id);
    if ($post) {
      $post->name = $name == null ? $post->name : $name;
      $post->detail = $detail == null ? $post->detail : $detail;
      $post->save();
      return ResponseFormatter::success(
        ['data' => $post],
        'Data retrieved successfully'
      );
    } else {
      return ResponseFormatter::error(
        ['message' => 'Data not found'],
        'Data not found',
        404
      );
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {
    $id = $request->id;
    $post = Post::find($id);
    if ($post != null) {
      $post->delete();
      return ResponseFormatter::success(
        'Data deleted successfully'
      );
    } else {
      return ResponseFormatter::error(
        ['message' => 'Data not found'],
        'Data not found',
        404
      );
    }
  }
}

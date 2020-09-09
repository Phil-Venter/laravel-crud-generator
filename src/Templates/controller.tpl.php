<?php

namespace [[appns]]Http\Controllers;

use Illuminate\Http\Request;

use [[appns]]Http\Requests;
use [[appns]]Http\Controllers\Controller;

use [[appns]][[model_uc]];

class [[controller_name]]Controller extends Controller
{
  public function __construct()
  {
  }

  public function index(Request $request)
  {
    $[[model_plural]] = [[model_uc]]::all();
    return view('[[view_folder]].index', compact('[[model_plural]]'));
  }

  public function create(Request $request)
  {
    return view('[[view_folder]].add');
  }

  public function edit(Request $request, $id)
  {
    $[[model_singular]] = [[model_uc]]::findOrFail($id);
    return view('[[view_folder]].add', compact('[[model_singular]]'));
  }

  public function show(Request $request, $id)
  {
    $[[model_singular]] = [[model_uc]]::findOrFail($id);
    return view('[[view_folder]].show', compact('[[model_singular]]'));
  }

  public function update(Request $request)
  {
    $[[model_singular]] = null;
    if ($request->id > 0) {
      $[[model_singular]] = [[model_uc]]::findOrFail($request->id);
      } else {
      $[[model_singular]] = new [[model_uc]];
    }

    [[foreach:columns]]
      [[ if:i.type == 'string' ]]
      $[[model_singular]]->[[i.name]] = $request->[[i.name]] ?? '';
      [[endif]]
      [[ if:i.type == 'number' ]]
      $[[model_singular]]->[[i.name]] = $request->[[i.name]] ?? 0;
      [[endif]]
      [[ if:i.type == 'date' ]]
      $[[model_singular]]->[[i.name]] = $request->[[i.name]] ?? date('m/d/Y h:i:s a', time());
      [[endif]]
      [[ if:i.type == 'text' ]]
      $[[model_singular]]->[[i.name]] = $request->[[i.name]] ?? '';
      [[endif]]
      [[ if:i.type == 'boolean' ]]
      $[[model_singular]]->[[i.name]] = $request->[[i.name]] ?? false;
      [[endif]]
      [[ if:i.type == 'unknown' ]]
      $[[model_singular]]->[[i.name]] = $request->[[i.name]];
      [[endif]]
    [[endforeach]]

    $[[model_singular]]->save();

    return route('[[route_path]].index')->redirect();
  }

  public function store(Request $request)
  {
    return $this->update($request);
  }

  public function destroy(Request $request, $id)
  {
    $[[model_singular]] = [[model_uc]]::findOrFail($id);

    $[[model_singular]]->delete();

    return route('[[ route_path ]].index')->redirect();
  }
}

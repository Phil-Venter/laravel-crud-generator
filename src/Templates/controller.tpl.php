<?php

namespace [[appns]]Http\Controllers;

use Illuminate\Http\Request;

use [[appns]]Http\Requests;
use [[appns]]Http\Controllers\Controller;

use [[appns]][[model_uc]];

class [[controller_name]]Controller extends Controller
{
  public function index()
  {
    $[[model_plural]] = [[model_uc]]::paginate(10);
    return view('[[view_folder]].index', compact('[[model_plural]]'));
  }

  public function show($id)
  {
    $[[model_singular]] = [[model_uc]]::findOrFail($id);
    return view('[[view_folder]].show', compact('[[model_singular]]'));
  }

  public function edit($id)
  {
    $[[model_singular]] = [[model_uc]]::findOrFail($id);
    return view('[[view_folder]].add', compact('[[model_singular]]'));
  }

  public function update(Request $request, $id = null)
  {
    $[[model_singular]] = new [[model_uc]];
    if ($id) $[[model_singular]] = [[model_uc]]::findOrFail($id);

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
    return redirect()->route('[[route_path]].index');
  }

  public function create()
  {
    return view('[[view_folder]].add');
  }

  public function store(Request $request)
  {
    return $this->update($request);
  }

  public function destroy($id)
  {
    $[[model_singular]] = [[model_uc]]::findOrFail($id);
    $[[model_singular]]->delete();
    return redirect()->route('[[route_path]].index');
  }
}

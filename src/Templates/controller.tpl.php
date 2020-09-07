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

    $data['model'] = $[[model_plural]];

    return view('[[view_folder]].index', $data);
  }

  public function create(Request $request)
  {
    return view('[[view_folder]].add');
  }

  public function edit(Request $request, $id)
  {
    $[[model_singular]] = [[model_uc]]::findOrFail($id);

    $data['model'] = $[[model_singular]];

    return view('[[view_folder]].add', $data);
  }

  public function show(Request $request, $id)
  {
    $[[model_singular]] = [[model_uc]]::findOrFail($id);

    $data['model'] = $[[model_singular]];

    return view('[[view_folder]].show', $data);
  }

  public function update(Request $request)
  {
    $[[model_singular]] = null;
    if($request->id > 0) {
      $[[model_singular]] = [[model_uc]]::findOrFail($request->id);
    } else {
      $[[model_singular]] = new [[model_uc]];
    }

    [[foreach:columns]]
    [[if:i.type=='string']]
    $[[model_singular]]->[[i.name]] = $request->[[i.name]] ?? '';
    [[endif]]
    [[if:i.type=='number']]
    $[[model_singular]]->[[i.name]] = $request->[[i.name]] ?? 0;
    [[endif]]
    [[if:i.type=='date']]
    $[[model_singular]]->[[i.name]] = $request->[[i.name]] ?? date('m/d/Y h:i:s a', time());
    [[endif]]
    [[if:i.type=='text']]
    $[[model_singular]]->[[i.name]] = $request->[[i.name]] ?? '';
    [[endif]]
    [[if:i.type== 'boolean']]
    $[[model_singular]]->[[i.name]] = $request->[[i.name]] ?? false;
    [[endif]]
    [[if:i.type=='unknown']]
    $[[model_singular]]->[[i.name]] = $request->[[i.name]];
    [[endif]]
    [[endforeach]]

    $[[model_singular]]->save();

    return redirect('/[[route_path]]');
  }

  public function store(Request $request)
  {
    return $this->update($request);
  }

  public function destroy(Request $request, $id)
  {
    $[[model_singular]] = [[model_uc]]::findOrFail($id);

    $[[model_singular]]->delete();

    return redirect('/[[route_path]]');
  }
}

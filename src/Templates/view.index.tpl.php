@extends('[[ custom_master ]]')

@section('content')
<form action="" method="post" id="delete" style="display: none">
  {{ method_field('DELETE') }}
  {{ csrf_field() }}
  <input type="hidden" id="delete-id" name="id" />
</form>
<script>
  function doDelete(id) {
    if (confirm('You really want to delete this record?')) {
      $("#delete-id").val(id);
      $("#delete").attr("action", `{{ route('[[ route_path ]].index') }}/${id}`);
      $("#delete").submit();
    }
  }
</script>
<h2 class="page-header">{{ ucfirst('[[ model_plural ]]') }}</h2>
<a href="{{ route('[[ route_path ]].create') }}" class="btn btn-default pull-right" role="button"><i class="fa fa-plus"></i></a>
<div class="panel panel-default">
  <div class="panel-heading">
    List of {{ ucfirst('[[ model_plural ]]') }}
  </div>
  <div class="panel-body">
    <div class="">
      <table class="table table-striped" id="thegrid">
        <thead>
          <tr>
            [[ foreach: columns ]]
            <th>[[ i.display ]]</th>
            [[ endforeach ]]
            <th colspan="3"></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($[[ model_plural ]] as $[[ model_singular ]])
          <tr>
            [[ foreach: columns ]]
            <td>{{ $[[ model_singular ]]->[[ i.name ]] }}</td>
            [[ endforeach ]]
            <td><a href="{{ route('[[ route_path ]].show', [ '[[ model_singular ]]' => $[[ model_singular ]]->id ]) }}" class="btn btn-default"><i class="fa fa-eye"></i></a></td>
            <td><a href="{{ route('[[ route_path ]].edit', [ '[[ model_singular ]]' => $[[ model_singular ]]->id ]) }}" class="btn btn-info"><i class="fa fa-pencil"></i></a></td>
            <td><a onclick="return doDelete({{ $[[ model_singular ]]->id }})" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
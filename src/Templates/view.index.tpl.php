@extends('[[ custom_master ]]')

@section('content')
<style>
  .page-header {
    display: inline-block;
    border: none;
  }

  #create {
    margin-top: 25px;
  }

  th,
  td {
    text-align: center;
  }

  .center {
    display: flex;
    width: 100%;
    justify-content: center;
  }
</style>

<script>
  function deleteRecord(id) {
    if (confirm('Are you sure you want to delete this record?')) {
      const form = document.createElement(`form`);
      form.method = `POST`;
      form.action = `{{ route('countries.index') }}/${id}`;
      form.style = 'display:none';
      form.innerHTML += `{{ csrf_field() }}`;
      form.innerHTML += `{{ method_field('DELETE') }}`;
      document.querySelector(`body`).append(form);
      form.submit();
    }
  }
</script>

<h2 class="page-header">{{ ucfirst('[[ model_plural ]]') }}</h2>
<a href="{{ route('[[ route_path ]].create') }}" class="btn btn-default pull-right" role="button" id="create"><i class="fa fa-plus"></i></a>

<div class="panel panel-default">
  <div class="panel-heading">
    List of {{ ucfirst('[[ model_plural ]]') }}
  </div>
  <div class="panel-body">
    <table class="table table-striped" id="thegrid">
      <thead>
        <tr>
          [[ foreach: columns ]]
          [[ if: i.type != 'id' ]]
          <th>[[ i.display ]]</th>
          [[ endif ]]
          [[ endforeach ]]
          <th colspan="3" style="width:150px"></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($[[ model_plural ]] as $[[ model_singular ]])
        <tr>
          [[ foreach: columns ]]
          [[ if: i.type != 'id' ]]
          <td>{{ $[[ model_singular ]]->[[ i.name ]] }}</td>
          [[ endif ]]
          [[ endforeach ]]
          <td style="width:50px"><a href="{{ route('[[ route_path ]].show', [ '[[ model_singular ]]' => $[[ model_singular ]]->id ]) }}" class="btn btn-default"><i class="fa fa-eye"></i></a></td>
          <td style="width:50px"><a href="{{ route('[[ route_path ]].edit', [ '[[ model_singular ]]' => $[[ model_singular ]]->id ]) }}" class="btn btn-info"><i class="fa fa-pencil"></i></a></td>
          <td style="width:50px"><a onclick="return deleteRecord({{ $[[ model_singular ]]->id }})" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="center">
      {{ $[[ model_plural ]]->links() }}
    </div>
  </div>
</div>
@endsection
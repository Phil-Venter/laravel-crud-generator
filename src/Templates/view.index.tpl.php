@extends('[[custom_master]]')

@section('content')
<h2 class="page-header">{{ ucfirst('[[model_plural]]') }}</h2>
<a href="{{url('[[route_path]]/create')}}" class="btn btn-primary pull-right" role="button">Add [[model_singular]]</a>

<div class="panel panel-default">
    <div class="panel-heading">
        List of {{ ucfirst('[[model_plural]]') }}
    </div>

    <div class="panel-body">
	<table class="table table-striped" id="thegrid"></table>
    </div>
</div>
<form action="" method="post" id="delete">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <input type="hidden" id="delete-id" name="id" />
</form>
@endsection

@section('scripts')
<script type="text/javascript">
    const url = "{{ url('/[[route_path]]') }}";
    const data = {!!json_encode($model) !!};
    const columns = [
        [[foreach:columns]]
            { title: "[[i.display]]", data: "[[i.name]]" },
        [[endforeach]]
    ];

    let theGrid = null;

    $(document).ready(function() {
        theGrid = $('#thegrid').DataTable({
            data,
            columns,
            columnDefs: [{
                render: (data, type, row) => `<a href="${url}/${row.id}">${data}</a>`,
                targets: 1
            }, {
                render: (data, type, row) => `<a href="${url}/${row.id}/edit" class="btn btn-default">Update</a>`,
                targets: columns.length
            }, {
                render: (data, type, row) => `<a href="#" onclick="return doDelete(${row.id})" class="btn btn-danger">Delete</a>`,
                targets: columns.length + 1
            }]
        });
    });

    function doDelete(id) {
        if (confirm('You really want to delete this record?')) {
            $("#delete-id").val(id);
            $("#delete").attr("action", `${url}/${id}`);
            $("#delete").submit();
        }
    }
</script>
@endsection

@extends('[[custom_master]]')

@section('content')
<h2 class="page-header">{{ ucfirst('[[model_plural]]') }}</h2>
<a href="{{url('[[route_path]]/create')}}" class="btn btn-primary pull-right" role="button">Add [[model_singular]]</a>

<div class="panel panel-default">
    <div class="panel-heading">
        List of {{ ucfirst('[[model_plural]]') }}
    </div>

    <div class="panel-body">
        <div class="">
            <table class="table table-striped" id="thegrid">
                <thead>
                    <tr>
                        [[foreach:columns]]
                        <th>[[i.display]]</th>
                        [[endforeach]]
                        <th style="width:50px"></th>
                        <th style="width:50px"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

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
    var theGrid = null;

    var data = JSON.parse("{!! json_encode($model) !!}").map(_ => Object.values(_));
    var url = "{{ url('/[[route_path]]') }}";

    $(document).ready(function() {
        theGrid = $('#thegrid').DataTable({
            data,
            columnDefs: [{
                render: function(data, type, row) {
                    return `<a href="${url}/${row[0]}">${data}</a>`;
                },
                targets: 1
            }, {
                render: function(data, type, row) {
                    return `<a href="${url}/${row[0]}/edit" class="btn btn-default">Update</a>`;
                },
                targets: [[num_columns]]
            }, {
                render: function(data, type, row) {
                    return `<a href="#" onclick="return doDelete(${row[0]}) class="btn btn-danger">Delete</a>`;
                },
                targets: [[num_columns]] + 1
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
<div class="col-sm-12">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        @foreach ($selected_table_columns as $column )
                        <th scope="col">{{$column}}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data as $data)
                    <tr>
                        @foreach (collect($data)->keys() as $key )
                        <td><small>{{collect($data)->get($key)}}</small></td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        <div class="card-footer bg-white">
            <div class="text-center">
                <form action="{{route('exportDataNow')}}">
                <input type="hidden" value="true" name="export">
                    <button type="submit" class="btn btn-success btn-lg">Export Data</button>
                </form>
            </div>
        </div>
    </div>
</div>

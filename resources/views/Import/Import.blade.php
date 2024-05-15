<div class="col-sm-8 offset-sm-2">
    <div class="card  shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header bg-white text-center h4">
            Import Table Data
        </div>
        <div class="card-body">
            <form enctype="multipart/form-data" method="post" action='{{route("importData")}}'>
                @csrf
                <div class="mb-3">
                    <label for="table_name" class="form-label">Database Table</label>
                    <select class="form-select" aria-label="Default select example" name="table_name">
                        <option>Open this select menu</option>
                        @foreach ($table_names as $name )
                        <option value="{{$name}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="csv_file" class="form-label">Csv File</label>
                    <input type="file" name="csv_file" class="form-control" id="csv_file">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>

            </form>
        </div>
    </div>
</div>

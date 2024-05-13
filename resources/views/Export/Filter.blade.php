<div class="col-sm-8 offset-sm-2 mt-3">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-body">
            <form action="{{route('exportData')}}" method="get">
                <div class="mb-3">
                    <label for="table" class="form-label">Table</label>
                    <select class="form-select" aria-label="Default select example" name="table">
                        <option selected>Open this select menu</option>
                        @foreach ($tables as $table )
                        <option value="{{$table}}" {{request('table')==$table?"selected":''}}>{{$table}}</option>
                        @endforeach
                    </select>
                </div>
                @if(count($table_columns)>0)
                    <div class="mb-3">
                        <label for="table" class="form-label">Table Columns</label>
                        <select class="form-select" aria-label="Default select example" name="table_columns[]" multiple size="5">
                            <option>Open this select menu</option>
                            @foreach ($table_columns as $column )
                            <option value="{{$column}}" >{{$column}}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

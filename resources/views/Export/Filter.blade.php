<div class="col-sm-8 offset-sm-2 mt-3">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header text-center bg-white h4">
            Export Table Data
        </div>
        <div class="card-body">
            <form action="{{route('exportData')}}" method="get">
                <div class="mb-3">
                    <label for="table" class="form-label">Table</label>
                    <select class="form-select" aria-label="Default select example" name="table">
                        <option selected value=''>Open this select menu</option>
                        @foreach ($tables as $table )
                        <option value="{{$table}}" {{request('table')==$table?"selected":''}}>{{$table}}</option>
                        @endforeach
                    </select>
                    @if($table_error!=1)
                    <div style="color: red;">{{ $table_error }}</div> <!-- Display the error message -->
                    @endif

                </div>
                @if(count($table_columns)>0&&request('table')!=null)
                <div class="mb-3">
                    <label for="table" class="form-label">Table Columns</label>
                    <select class="form-select" aria-label="Default select example" name="table_columns[]" multiple size="5">
                        <option>Open this select menu</option>
                        @foreach ($table_columns as $column )
                        <option value="{{$column}}">{{$column}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Advanced {{ucfirst(request('table'))}} Search ({{count($table_data)}})
                    </label>
                    <input type="text" class="form-control" name="search" value="{{request()->get('search')}}">
                    <div id="" class="form-text">
                        <div class="mt-2 text-primary">Example Search Format: {{$searchPlaceholder}}</div>
                    </div>
                </div>

                @endif
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" title="filter table data"><i class="fas fa-filter"></i></button>

                </div>
            </form>
        </div>
    </div>
</div>

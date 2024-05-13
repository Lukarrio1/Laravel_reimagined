<div class="col-sm-8 offset-sm-2 mt-3">
    <div class="card  shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Advanced User Search ({{count($users)}})</label>
                    <input type="text" class="form-control" name="search" value="{{request()->get('search')}}">
                    <div id="" class="form-text">
                        <div class="mt-2 text-danger">Example Search Format: {{$search_placeholder}}</div>
                    </div>
                </div>
                <div class="text-center"><button type="submit" class="btn btn-primary">Search</button></div>
            </form>
        </div>
    </div>
</div>

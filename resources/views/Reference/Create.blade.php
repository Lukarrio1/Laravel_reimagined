<div class="col-sm-8 offset-sm-2 mt-5">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header text-center bg-white h3 fw-bold">
            References Management
        </div>
        <div class="card-body">
            <form action="{{route('viewReferences')}}" id="references_form">
                @csrf
                <div class="mb-3">
                    <label for="owner_model" class="form-label">Owner Model</label>
                    <select id="owner_model" class="form-select" name="owner_model">
                        @foreach($models as $model)
                        <option value="{{$model}}" data-node-type="{{$model}}" {{optional(optional($reference)->owner_model) ==$model || request('owner_model')==$model?"selected":''}}>{{$model}}</option>
                        @endforeach
                    </select>
                    @error('owner_model')
                    <div style="color: red;">{{ $message }}</div> <!-- Display the error message -->
                    @enderror
                </div>
                @if(request()->has('owner_model'))
                <div class="mb-3">
                    <label for="owner_model_display_aid" class="form-label">Owner Model Display Aid</label>
                    <select id="owner_model_display_aid" class="form-select" name="owner_model_display_aid">
                        @foreach($model_fields as $field)
                        <option value="{{$field}}" data-node-type="{{$field}}" {{request('owner_model_display_aid')==$field?"selected":''}}>{{$field}}</option>


                        @endforeach
                    </select>
                    @error('owner_model_display_aid')
                    <div style="color: red;">{{ $message }}</div> <!-- Display the error message -->
                    @enderror
                </div>

                @endif
                @if(request()->has('owner_model_display_aid'))
                <div class="mb-3">
                    <label for="owner_item" class="form-label">Owner Item</label>
                    <select id="owner_item" class="form-select" name="owner_item">
                        @foreach($owners as $owner)
                        <option value="{{$owner->id}}" data-node-type="{{$owner[request('owner_model_display_aid')]}}">{{$owner[request('owner_model_display_aid')]}}</option>
                        @endforeach
                    </select>
                    @error('owner_model_display_aid')
                    <div style="color: red;">{{ $message }}</div> <!-- Display the error message -->
                    @enderror
                </div>

                @endif


                {{-- <div class="mb-3">
                    <label for="role_priority" class="form-label">Role priority</label>
                    <input type="number" class="form-control" id="role_priority" aria-describedby="emailHelp" value="{{isset($role)?optional($role)->priority:old('priority')}}" name="priority">
                @error('priority')
                <div style="color: red;">{{ $message }}</div>
                @enderror
        </div>

        <div class="mb-3">
            <label for="role_name" class="form-label">Permissions (<small class="text-primary">Use shift to select more than 1 permission</small>)</label>
            <select class="form-select" multiple aria-label="Multiple select example" name="permissions[]">
                <option selected>Open this select menu</option>
                @foreach ($permissions as $permission )
                <option value="{{$permission->id}}" {{in_array($permission->id,empty(optional(optional($role)->permissions)->pluck('id'))?[]:
                        optional(optional($role)->permissions)->pluck('id')->toArray()) ? 'selected' : '' }}>
                    {{$permission->name}}</option>
                @endforeach
            </select>
        </div>
        <input type="hidden" value="{{optional($role)->id}}" name="id">
        <div class="mb-3 text-center">
            <button type="submit" class="btn btn-{{isset($role)?'warning':'primary'}}">
                @if(isset($role))
                <i class="fa fa-wrench" aria-hidden="true"></i>
                @else
                <i class="fa fa-pencil" aria-hidden="true"></i>
                @endif</button>

        </div> --}}
        </form>
    </div>
</div>
</div>

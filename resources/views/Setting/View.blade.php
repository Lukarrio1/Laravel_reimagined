@extends('Layouts.app')
@section('content')
<div class="col-sm-8 offset-sm-2">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header text-center bg-white h4">
            Settings
        </div>
        @can('can view settings edit or create form', auth()->user())
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="key" class="form-label">Setting Key (<small class="text-primary">Please request the setting value by pressing the blue button.</small>)</label>
                    <select id="key" class="form-select" name="setting_key">
                        @foreach($keys as $key=>$value)
                        <option value="{{$key}}" {{request()->get('setting_key')==$key?"selected":''}}>{{$value}}</option>
                        @endforeach
                    </select>
                    @error('setting_key')
                    <div style="color: red;">{{ $message }}</div> <!-- Display the error message -->
                    @enderror
                    <div class="mb-3 text-center mt-3">
                        <button class='btn btn-sm btn-primary'>Request Setting</button>
                    </div>
                </div>
            </form>
            <form action="{{route('saveSetting')}}" method="post">
                @csrf
                <div class="mb-3">
                    <input type='hidden' value="{{$setting_key}}" name="setting_key">
                    <label for="key" class="form-label">Setting Value</label>
                    {!!$key_value!!}
                    @error('value')
                    <div style="color: red;">{{ $message }}</div> <!-- Display the error message -->
                    @enderror
                </div>
                <div class="mb-3">
                    <div class="mb-3">
                        <label for="role_name" class="form-label">Allowed for api use</label>
                        <select class="form-select" name="allowed_for_api_use">
                            <option selected>Open this select menu</option>
                            @foreach (['Yes'=>1,'No'=>0] as $key=>$value )
                            <option value="{{$value}}" {{$allowed_for_api_use==$value?'selected':''}}>{{$key}}</option>


                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="mb-3 text-center mt-3">
                    <button class='btn btn-sm btn-warning' type="submit"><i class="fa fa-wrench" aria-hidden="true"></i></button>

                </div>
            </form>
        </div>
        @endcan
    </div>
</div>
<div class="col-sm-8 offset-sm-2 mt-3">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        @can('can view settings data table', auth()->user())
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Settings key</th>
                        <th scope="col">Settings Value</th>
                        <th scope="col">Allowed For Api Use</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($settings as $setting )
                    <tr>
                        <td>{{$setting->getAllSettingKeys($setting->key)}}</td>
                        <td>{!!$setting->getSettingValue('first')!!}</td>
                        <td>{{$setting->allowed_for_api_use?"Yes":"No"}}</td>
                        <td>
                            @can('can view settings delete button',auth()->user())
                            <form action="{{route('deleteSetting',['setting_key'=>$setting->key])}}" method="post">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-danger" type="submit">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endcan

    </div>
</div>
@endsection

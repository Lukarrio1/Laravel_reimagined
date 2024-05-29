@extends('Layouts.app')
@section('content')
<div class="col-sm-8 offset-sm-2">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header text-center bg-white h4">
            Settings
        </div>
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="key" class="form-label">Setting Key (<small class="text-danger">Please request the setting value by pressing the blue button.</small>)</label>
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
                <div class="mb-3 text-center mt-3">
                    <button class='btn btn-sm btn-warning' type="submit"><i class="fa fa-wrench" aria-hidden="true"></i></button>

                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-sm-8 offset-sm-2 mt-3">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">

        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Settings key</th>
                        <th scope="col">Settings Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($settings as $setting )
                    <tr>
                        <td>{{$setting->getAllSettingKeys($setting->key)}}</td>
                        <td>{{!empty($setting->getAllSettingKeys($setting->key))?$setting->getSettingValue('first'):''}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

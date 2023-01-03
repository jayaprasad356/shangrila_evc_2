@extends('layouts.admin.app')

@section('title', translate('Update Settings'))
<style>
    .password-container{
        position: relative;
    }

    .togglePassword{
        position: absolute;
        top: 14px;
        right: 16px;
    }
</style>
@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i> {{translate('update Settings')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.settings.update')}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('Day Electricity Meter Reading')}}</label>
                                <input type="number" value="{{$setting['day_electricity_meter_reading']}}" name="day_electricity_meter_reading"
                                       class="form-control"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('Night Electricity Meter Reading')}}</label>
                                <input type="number" value="{{$setting['night_electricity_meter_reading']}}" name="night_electricity_meter_reading"
                                       class="form-control"
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                       
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('Gas Meter Reading')}}</label>
                                <input type="number" value="{{$setting['day_gas_meter_reading']}}" name="day_gas_meter_reading"
                                       class="form-control"
                                       required>
                            </div>
                        </div>
                    </div>
                
                    <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                </form>
            </div>
        </div>
    </div>

@endsection



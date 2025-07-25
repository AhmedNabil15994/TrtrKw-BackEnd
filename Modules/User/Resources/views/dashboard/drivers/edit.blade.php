@extends('apps::dashboard.layouts.app')
@section('title', __('user::dashboard.drivers.update.title'))
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="{{ url(route('dashboard.home')) }}">{{ __('apps::dashboard.home.title') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ url(route('dashboard.drivers.index')) }}">
                            {{__('user::dashboard.drivers.index.title')}}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{__('user::dashboard.drivers.update.title')}}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <form id="updateForm" user="form" class="form-horizontal form-row-seperated" method="post"
                      enctype="multipart/form-data" action="{{route('dashboard.drivers.update',$user->id)}}">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">

                        {{-- RIGHT SIDE --}}
                        <div class="col-md-3">
                            <div class="panel-group accordion scrollable" id="accordion2">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><a class="accordion-toggle"></a></h4>
                                    </div>
                                    <div id="collapse_2_1" class="panel-collapse in">
                                        <div class="panel-body">
                                            <ul class="nav nav-pills nav-stacked">
                                                <li class="active">
                                                    <a href="#global_setting" data-toggle="tab">
                                                        {{ __('user::dashboard.drivers.update.form.general') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PAGE CONTENT --}}
                        <div class="col-md-9">
                            <div class="tab-content">

                                {{-- UPDATE FORM --}}
                                <div class="tab-pane active fade in" id="global_setting">
                                    <h3 class="page-title">{{__('user::dashboard.drivers.update.form.general')}}</h3>
                                    <div class="col-md-10">

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('user::dashboard.drivers.create.form.company')}}
                                            </label>
                                            <div class="col-md-9">
                                                <select name="company_id" id="single" class="form-control select2"
                                                        data-name="company_id">
                                                    <option value=""></option>
                                                    @foreach ($companies as $company)
                                                        <option value="{{ $company['id'] }}" {{ $company['id'] == $user->company_id ? 'selected' : '' }}>
                                                            {{ $company->translate(locale())->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('user::dashboard.drivers.update.form.name')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="name" class="form-control" data-name="name"
                                                       value="{{ $user->name }}">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('user::dashboard.drivers.update.form.email')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="email" name="email" class="form-control" data-name="email"
                                                       value="{{ $user->email }}">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('user::dashboard.drivers.update.form.mobile')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="mobile" class="form-control" data-name="mobile"
                                                       value="{{ $user->mobile }}">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('user::dashboard.drivers.update.form.password')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="password" name="password" class="form-control"
                                                       data-name="password">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('user::dashboard.drivers.update.form.confirm_password')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="password" name="confirm_password" class="form-control"
                                                       data-name="confirm_password">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('user::dashboard.drivers.update.form.roles')}}
                                            </label>
                                            <div class="col-md-9">
                                                <div class="mt-checkbox-list">
                                                    @foreach ($roles as $role)
                                                        <label class="mt-checkbox">
                                                            <input type="checkbox" name="roles[]"
                                                                   value="{{$role->id}}" {{ $user->roles->contains($role->id) ? 'checked=""' : ''}}>
                                                            {{$role->translate(locale())->display_name}}
                                                            <span></span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        @if ($user->trashed())
                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('apps::dashboard.general.restore')}}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="test"
                                                           data-size="small" name="restore">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('user::dashboard.drivers.update.form.image')}}
                                            </label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                <span class="input-group-btn">
                                                    <a data-input="image" data-preview="holder"
                                                       class="btn btn-primary lfm">
                                                        <i class="fa fa-picture-o"></i>
                                                        {{__('apps::dashboard.general.upload_btn')}}
                                                    </a>
                                                </span>
                                                    <input name="image" class="form-control image" type="text" readonly>
                                                    <span class="input-group-btn">
                                                    <a data-input="image" data-preview="holder"
                                                       class="btn btn-danger delete">
                                                        <i class="glyphicon glyphicon-remove"></i>
                                                    </a>
                                                </span>
                                                </div>
                                                <span class="holder" style="margin-top:15px;max-height:100px;">
                                              <img src="{{url($user->image)}}" style="height: 15rem;">
                                            </span>
                                                <input type="hidden" data-name="image">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                {{-- END UPDATE FORM --}}

                            </div>
                        </div>

                        {{-- PAGE ACTION --}}
                        <div class="col-md-12">
                            <div class="form-actions">
                                @include('apps::dashboard.layouts._ajax-msg')
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-lg green">
                                        {{__('apps::dashboard.general.edit_btn')}}
                                    </button>
                                    <a href="{{url(route('dashboard.drivers.index')) }}" class="btn btn-lg red">
                                        {{__('apps::dashboard.general.back_btn')}}
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

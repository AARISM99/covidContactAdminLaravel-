@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.pushNotification.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.pushNotification.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.pushNotification.fields.id') }}
                        </th>
                        <td>
                            {{ $pushNotification->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pushNotification.fields.firstDevice') }}
                        </th>
                        <td>
                            {{ $pushNotification->firstDevice }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pushNotification.fields.secondDevice') }}
                        </th>
                        <td>
                            {{ $pushNotification->secondDevice }}
                        </td>
                    </tr>

                    <tr>
                        <th>
                            {{ trans('cruds.pushNotification.fields.notificationSent') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $pushNotification->notificationSent ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pushNotification.fields.date_contact') }}
                        </th>
                        <td>
                            {{ $pushNotification->date_contact }}
                        </td>
                    </tr>


                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.pushNotification.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection

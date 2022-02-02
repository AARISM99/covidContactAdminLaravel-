<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPushNotificationRequest;
use App\Http\Requests\StorePushNotificationRequest;
use App\Http\Requests\UpdatePushNotificationRequest;
use Gate;
use Illuminate\Http\Request;
use App\Models\PushNotification;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;


class PushNotificationController extends Controller
{
    // public function index()
    // {
    //     abort_if(Gate::denies('push_notification_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     return view('admin.pushNotifications.index');
    // }

    public function index(Request $request)
    {
        Log::info("it works");
        abort_if(Gate::denies('push_notification_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = PushNotification::query()->select(sprintf('%s.*', (new PushNotification())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'push-notifications_show';
                $editGate = 'push-notifications_edit';
                $deleteGate = 'push-notifications_delete';
                $crudRoutePart = 'push-notifications';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('body', function ($row) {
                return $row->body ? $row->body : '';
            });
            $table->editColumn('sender', function ($row) {
                return $row->sender ? $row->sender : '';
            });
            $table->editColumn('receiver', function ($row) {
                return $row->receiver ? $row->receiver : '';
            });
            $table->editColumn('date', function ($row) {
                return $row->date ? $row->date : '';
            });



            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }



        return view('admin.pushNotifications.index');
    }

    public function create()
    {
        abort_if(Gate::denies('push_notification_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.pushNotifications.create');
    }

    public function store(StorePushNotificationRequest $request)
    {
        $pushNotification = PushNotification::create($request->all());

        return redirect()->route('admin.push-notifications.index');
    }

    public function edit(PushNotification $pushNotification)
    {
        abort_if(Gate::denies('push_notification_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.pushNotifications.edit', compact('pushNotification'));
    }

    public function update(UpdatePushNotificationRequest $request, PushNotification $pushNotification)
    {
        $pushNotification->update($request->all());

        return redirect()->route('admin.push-notifications.index');
    }

    public function show(PushNotification $pushNotification)
    {
        abort_if(Gate::denies('push_notification_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.pushNotifications.show', compact('pushNotification'));
    }

    public function destroy(PushNotification $pushNotification)
    {
        abort_if(Gate::denies('push_notification_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pushNotification->delete();

        return back();
    }

    public function massDestroy(MassDestroyPushNotificationRequest $request)
    {
        PushNotification::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Device;
use App\Models\PushNotification;
use Illuminate\Http\Client\ConnectionException;


class NotificationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }

    public function sendNotification(Request $request){

        $contacts = $request->all();

        Log::info("Nombre des contacts = ");
        Log::info(count($contacts));
        Log::info($contacts);

        // $title = "تنبيه خطير وصلنا أنك مررت بالقرب من شخص مصاب بفيروس كورونا";
        // $body = "لقد وصلنا أنك مررت بأحد الأشخاص الذين يحملون فيروس الكوفيد19 لهذا نوصيك بأن تخضع للحجر الصحي لمدة أسبوع و أن تبلغنا في حال ظهرت عليك أعراض";

        $title = "Risk of catching the covid!";
        $body = "You have been in contact with someone who has the Covid-19 virus, so we recommend that you quarantine for a week and inform us if you develop symptoms.";

        for($i=0; $i<count($contacts); $i++){
            Log::info("I m in the boucle");
            Log::info("contact === ");
            Log::info($contacts[$i]);
            // Log::info($contacts[$i]["secondDeviceId"]);
            $res = Device::where('udid', $contacts[$i]["secondDeviceId"])->firstOrFail();
            Log::info("check device in DB");
            Log::info($res);

            try{
                Log::info("Try");
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAA_bqRh4Q:APA91bGr5S5vQrCkvCXmsXCi9EtTYmVLu5XuSKRFVaRfRwtNlod4JNL8lmDcpVd5tYhkQ8cXOD922aw_mJsRWjRl2JFc7bUVyhhmqUUwl8vI7ZJu19dWSlhb_QL3Ma2064G-P2IylVyR'
                ])->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $res["token"],
                    'notification' => [
                        "title" => $title,
                        "body" => $body
                    ]
                ]);
                Log::info("response == ");
                Log::info($response);

                $notification = array(
                    "title" => $title,
                    "body" => $body,
                    "sender" => $contacts[$i]["firstDeviceId"],
                    "receiver" => $contacts[$i]["secondDeviceId"],
                    "date" => date("Y-m-d H:i:s")
                );

                if($response["success"] == 1){
                    NotificationController::save($notification);
                }


            }
            catch(ConnectionException $e){
                Log::info("erreur send notification");
                Log::info($e);
            }

            // $encoded = json_encode($notification);

            Log::info("notication numero = ");
            Log::info($i);

        }


        return response('The notification sent successfully');
        // return response($request);
    }

    public function save($data)
    {
        Log::info($data);
        // $notification = PushNotification::where("id", $data->id)->firstOrNew();
        $notification = new PushNotification();

        $notification->title = $data["title"];
        $notification->body = $data["body"];
        $notification->sender = $data["sender"];
        $notification->receiver = $data["receiver"];
        $notification->date = $data["date"];
        $notification->save();

        Log::info("notification was saved successfully");
        return response('The notification saved successfully');
    }




    public function getNotifications(Request $request){
        Log::info("request ====== ");
        Log::info($request['udid']);
        $notifications = PushNotification::select('title','body','sender','created_at')->where('receiver',$request['udid'])->get();
        Log::info("notification list ===========");
        Log::info(json_encode($notifications));
        return json_encode($notifications);
    }


    // public function updateNotifications(){
    //     PushNotification::
    // }

}

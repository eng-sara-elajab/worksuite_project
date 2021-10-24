<?php

namespace App\Http\Controllers\Admin;

use App\Event;
use App\EventAttendee;
use App\Helper\Reply;
use App\Http\Requests\Events\StoreEvent;
use App\Http\Requests\Events\UpdateEvent;
use App\Notifications\EventInvite;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AdminEventCalendarController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = __('app.menu.Events');
        $this->pageIcon = 'icon-calender';
        $this->middleware(function ($request, $next) {
            if(!in_array('events',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });

    }

    public function index(){
        $this->employees = User::all();
        $this->events = Event::all();

        return view('admin.event-calendar.index', $this->data);
    }

    public function store(StoreEvent $request){
        $event = new Event();
        $event->event_name = $request->event_name;
        $event->where = $request->where;
        $event->description = $request->description;
        $event->start_date_time = Carbon::parse($request->start_date)->format('Y-m-d').' '.Carbon::parse($request->start_time)->format('H:i:s');
        $event->end_date_time = Carbon::parse($request->end_date)->format('Y-m-d').' '.Carbon::parse($request->end_time)->format('H:i:s');

        if($request->repeat){
            $event->repeat = $request->repeat;
        }
        else{
            $event->repeat = 'no';
        }

        if ($request->send_reminder) {
            $event->send_reminder = $request->send_reminder;
        }
        else {
            $event->send_reminder = 'no';
        }

        $event->repeat_every = $request->repeat_count;
        $event->repeat_cycles = $request->repeat_cycles;
        $event->repeat_type = $request->repeat_type;

        $event->remind_time = $request->remind_time;
        $event->remind_type = $request->remind_type;

        $event->label_color = $request->label_color;
        $event->save();

        // Add repeated event
        if ($request->has('repeat') && $request->repeat == 'yes') {
            $repeatCount = $request->repeat_count;
            $repeatType = $request->repeat_type;
            $repeatCycles = $request->repeat_cycles;
            $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
            $dueDate = Carbon::parse($request->end_date)->format('Y-m-d');

            $dataF = [];
            $dataS = [];
            for ($i = 1; $i < $repeatCycles; $i++) {
                $repeatStartDate = Carbon::createFromFormat('Y-m-d', $startDate);
                $repeatDueDate = Carbon::createFromFormat('Y-m-d', $dueDate);

                if ($repeatType == 'day') {
                    $repeatStartDate = $repeatStartDate->addDays($repeatCount);
                    $repeatDueDate = $repeatDueDate->addDays($repeatCount);
                } else if ($repeatType == 'week') {
                    $repeatStartDate = $repeatStartDate->addWeeks($repeatCount);
                    $repeatDueDate = $repeatDueDate->addWeeks($repeatCount);
                } else if ($repeatType == 'month') {
                    $repeatStartDate = $repeatStartDate->addMonths($repeatCount);
                    $repeatDueDate = $repeatDueDate->addMonths($repeatCount);
                } else if ($repeatType == 'year') {
                    $repeatStartDate = $repeatStartDate->addYears($repeatCount);
                    $repeatDueDate = $repeatDueDate->addYears($repeatCount);
                }
                $dataF[] = $repeatStartDate;
                $dataS[] = $repeatDueDate;

                $event = new Event();
                $event->event_name = $request->event_name;
                $event->where = $request->where;
                $event->description = $request->description;
                $event->start_date_time = $repeatStartDate->format('Y-m-d').' '.Carbon::parse($request->start_time)->format('H:i:s');
                $event->end_date_time = $repeatDueDate->format('Y-m-d').' '.Carbon::parse($request->end_time)->format('H:i:s');

                if($request->repeat){
                    $event->repeat = $request->repeat;
                }
                else{
                    $event->repeat = 'no';
                }

                if ($request->send_reminder) {
                    $event->send_reminder = $request->send_reminder;
                }
                else {
                    $event->send_reminder = 'no';
                }

                $event->repeat_every = $request->repeat_count;
                $event->repeat_cycles = $request->repeat_cycles;
                $event->repeat_type = $request->repeat_type;

                $event->remind_time = $request->remind_time;
                $event->remind_type = $request->remind_type;

                $event->label_color = $request->label_color;
                $event->save();

                $startDate = $repeatStartDate->format('Y-m-d');
                $dueDate = $repeatDueDate->format('Y-m-d');
            }
        }

        if($request->all_employees){
            $attendees = User::allEmployees();
            foreach($attendees as $attendee){
                EventAttendee::create(['user_id' => $attendee->id, 'event_id' => $event->id]);
            }
        }

        if($request->user_id){
            foreach($request->user_id as $userId){
                EventAttendee::firstOrCreate(['user_id' => $userId, 'event_id' => $event->id]);
            }
            $attendees = User::whereIn('id', $request->user_id)->get();
        }

        Notification::send($attendees, new EventInvite($event));
        return Reply::success(__('messages.eventCreateSuccess'));
    }

    public function edit($id){
        $this->employees = User::doesntHave('attendee', 'and', function($query) use ($id){
            $query->where('event_id', $id);
        })
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->get();
        $this->event = Event::findOrFail($id);
        $view = view('admin.event-calendar.edit', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function update(UpdateEvent $request, $id){
        $event = Event::findOrFail($id);
        $event->event_name = $request->event_name;
        $event->where = $request->where;
        $event->description = $request->description;
        $event->start_date_time = Carbon::parse($request->start_date)->format('Y-m-d').' '.Carbon::parse($request->start_time)->format('H:i:s');
        $event->end_date_time = Carbon::parse($request->end_date)->format('Y-m-d').' '.Carbon::parse($request->end_time)->format('H:i:s');

        if($request->repeat){
            $event->repeat = $request->repeat;
        }
        else{
            $event->repeat = 'no';
        }

        if ($request->send_reminder) {
            $event->send_reminder = $request->send_reminder;
        }
        else {
            $event->send_reminder = 'no';
        }

        $event->repeat_every = $request->repeat_count;
        $event->repeat_cycles = $request->repeat_cycles;
        $event->repeat_type = $request->repeat_type;

        $event->remind_time = $request->remind_time;
        $event->remind_type = $request->remind_type;

        $event->label_color = $request->label_color;
        $event->save();

        if($request->all_employees){
            $attendees = User::allEmployees();
            foreach($attendees as $attendee){
                $checkExists = EventAttendee::where('user_id', $attendee->id)->where('event_id', $event->id)->first();
                if(!$checkExists){
                    EventAttendee::create(['user_id' => $attendee->id, 'event_id' => $event->id]);

                    //      Send notification to user
                    $notifyUser = User::withoutGlobalScope('active')->findOrFail($attendee->id);
                    $notifyUser->notify(new EventInvite($event));

                }
            }
        }

        if($request->user_id){
            foreach($request->user_id as $userId){
                $checkExists = EventAttendee::where('user_id', $userId)->where('event_id', $event->id)->first();
                if(!$checkExists){
                    EventAttendee::create(['user_id' => $userId, 'event_id' => $event->id]);

                    //      Send notification to user
                    $notifyUser = User::withoutGlobalScope('active')->findOrFail($userId);
                    $notifyUser->notify(new EventInvite($event));
                }
            }
        }

        return Reply::success(__('messages.eventCreateSuccess'));
    }

    public function show($id){
        $this->event = Event::findOrFail($id);
        return view('admin.event-calendar.show', $this->data);
    }

    public function removeAttendee(Request $request){
        EventAttendee::destroy($request->attendeeId);
        return Reply::dataOnly(['status' => 'success']);
    }

    public function destroy($id){
        Event::destroy($id);
        return Reply::success(__('messages.eventDeleteSuccess'));
    }
}

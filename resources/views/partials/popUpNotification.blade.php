<div class="notification" notification-id="{{$notification->id}}" popup>
    @if($notification->post != null)
    <div class="button"><a href={{url('/question/'. $notification->link->id)}}>{{$notification->content}}</a></div>
    @elseif($notification->badge != null)
    <div class="button"><a href={{url('self/badges')}}>{{$notification->content}}</a></div>
    @else
    <div class="button">{{$notification->content}}</div>
    @endif

    <div class="ml-2">{{date("Y-m-d H:i:s", strtotime($notification->notification_time))}}</div>
    <a class="mark-notification-read">X</a>
</div>
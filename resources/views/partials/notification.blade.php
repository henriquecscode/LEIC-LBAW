<div class="notification" notification-id="{{$notification->id}}">
    <div class="d-flex flex-row {{$notification->pivot->is_read ? 'notification-display-read' : 'notification-display'}}">
        <div class="id_square p-2 pl-3"> {{ $notification->id }} </div> 
        <div class="p-3">
            @if($notification->post != null)
                <a href={{url('/question/'. $notification->link->id)}}>{{$notification->content}}</a>
                @elseif($notification->badge != null)
                <a href={{url('members/' . Auth::id())}}>{{$notification->content}}</a>
                @else
                {{$notification->content}}
                @endif
                {{date("Y-m-d H:i:s", strtotime($notification->notification_time))}}
                @if (!$notification->pivot->is_read)
                <a class="mark-notification-read button button-outline">Mark as read</a>
                @endif
        </div>
    </div>
</div>
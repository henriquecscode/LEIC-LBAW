@if($canSubscribe)

<form method="POST" action="{{ url('subscriptions/post/' . $id) }}">@csrf
    <input class="button" type="submit" value="Subscribe">
</form>

@else
<form method="POST" action="{{ url('unsubscriptions/post/' .  $id) }}">@csrf
    <input class="button" type="submit" value="Unsubscribe">
</form>

@endif
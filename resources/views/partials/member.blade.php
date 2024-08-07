<div class="mytable mr-5">
    <div class="user_name d-flex justify-content-between align-items-center">
        <a class="ml-2" href="{{url('members/'.$member->id)}}"> {{$member->name}} </a>
    </div>
    <h3 class="ml-4 mt-3">Likes: {{$member->likes}}</h3>
    <h3 class="ml-4 mt-3">Bio: {{$member->bio}}</h3>
</div>
@extends('layouts.app')

@section('title', 'Trivia(l)')

@section('path', 'home/profile')

@section('content')

    <div class="mx-auto">
        @include('partials.block', ['member' => $member, 'blocked' => $blocked,  'canEdit' => $canEdit, 'canEditBlock'=>$canEditBlock])
    </div>
    <!-- DADOS -->
    <div class="d-flex justify-content-around mx-5 mt-5">

        <!-- PROFILE IMAGE -->
        @if($member->photo != NULL)
        <div>
            <!-- <label for="image"> image </label> -->
            <input id="image" type="image" name="profile picture" alt="Profile picture" width="200" height="250" src="{{'/storage/images/'.$member->photo}}" required readonly>
        </div>
        @endif
        
        <div id="adjust-profile">
            <label for="name">Name <span style="font-weight:bold" id="member-type">({{$member->member_type}})</span></label>
            <input id="name" type="text" name="name"  value="{{ $member->name }}" readonly required>
        
            <label for="bio"> Bio </label>
            <input id="bio" type="text" name="biography" value="{{ $member->bio }}" required readonly>

            <label for="birth_date">Birth Date</label>
            <input id="birth_date" type="text" name="Birth Date"  value="{{ $member->birth_date }}" readonly required>
        
        </div>
    </div>

    <!-- INTERAÇOES -->
    <div class="text-center mt-5">
        <span><b>Badges</b></span>
        <br>
        @foreach ($badges as $badge)
        <div class="badge1">{{ $badge->name }}</div>
        @endforeach
        </br>
    </div>

    <div class="text-center mt-5">
        <span><b>Questões</b></span>
        <table class="align-items-center justify-content-center text-center mx-auto mt-2" id="questions-block">
            @foreach ($questions as $question)
            <tr><th><a href=" {{ url('question/' . $question->post) }}">{{ $question->title }}</a></th></tr>
            @endforeach
            
        </table>
    </div>
    <br>
    <div class="text-center mt-2">
    <span><b>Comentários</b></span>
        <table class="align-items-center justify-content-center text-center mx-auto mt-2" id="questions-block">
            @foreach ($comments as $comment)
            <tr><th><a href=" {{ url('question/' . $comment->question->id) }}">{{$comment->content}}</a></th></tr>
            @endforeach
        </table>
    </div>
   
    <!-- EMAIL AND PASSWORD - SO PERMITIDO QUANDO MEMBER=OWNER -->
    @if ($canEdit)
    <div class="d-flex justify-content-around mx-5">
        <div>
            <label for="email">E-Mail Address</label>
            <input id="email" type="email" name="email" value="{{ $member->email }}" required readonly>
        </div>

        <div>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" value="*******" required readonly>
        </div>
        
        <!-- BUTTONS -->
        <div>
            <a class="button button-outline" href="{{ url('/members/'. $member->id  . '/edit')}}">  Edit Profile </a>
            <a class="button button-outline" href="{{ url('/self/subscriptions')}}"> Subscrições </a>
            <a class="button button-outline" href="{{ url('/self/notifications')}}">Notificações </a>

        </div>
    </div>
    @else
        @if ($canSubscribe)
            <form method="POST" action="{{ url('/subscriptions/members/' .  $member->id) }}">@csrf
                <input class="button button-outline" type="submit" value="Subscribe">
            </form>
        @else
            <form method="POST" action="{{ url('/unsubscriptions/members/' .  $member->id) }}">@csrf
                <input class="button button-outline" type="submit" value="Unsubscribe">
            </form>
        @endif
        
    @endif

    @if ($canEditMod)
    <div class="d-flex justify-content-around mx-5">
        <!-- BUTTONS -->
        <div>
            <a id="addMod" member-id="{{$member->id}}" class="button button-outline" style="{{$member->member_type=='mod'?'display:none': ''}}">Add moderator</a>
            <a id="removeMod" member-id="{{$member->id}}" class="button button-outline" style="{{$member->member_type=='mod'? '' : 'display:none'}}">Remove moderator</a>
        </div>
    </div>
    @endif

    
@endsection

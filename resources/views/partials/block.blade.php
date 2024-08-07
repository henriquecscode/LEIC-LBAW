<!-- admins cant block/unblock admins -->
<div id="blockblock">
    @if($blocked)
    <div id="unblock-profile-button">
    
        <div class="blocked text-center">UTILIZADOR(A) BLOQUEADO</div>
        @if($canEditBlock)
        <a profile-id-unblock="{{ $member->id }}" class="button profile-unblock-button text-white">Unblock</a>
        @else
            @if($canEdit)
            <br>
            <form class="w-75" method="POST" action="{{ url('appeal') }}">
                @csrf
                <label for="appeal">Appeal</label><br>
                <input type="text" id="text" name="text"><br>
                <button type="submit">Enviar</button>
            </form>
            @endif
        @endif
    </div>
    @else
        @if($canEditBlock)
        <a profile-id-block="{{ $member->id }}" class="button text-white profile-block-button text-white" id="block-profile-button">Block</a>
        @endif
    @endif
</div>
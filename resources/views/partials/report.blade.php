
    <div id="post-report-{{$post->id}}" style="display:none">
        <textarea name="conteudo" post-id="{{$post->id}}">{{old('conteudo')}}</textarea>
        <a class="button button-outline" onclick="cancelReport({{$post->id}})"> Cancelar </a>
        <a class="button button-outline post-report-button">Enviar</a>
    </div>
    <a class="button text-white" onclick="showReport({{$post->id}})">Reportar</a>
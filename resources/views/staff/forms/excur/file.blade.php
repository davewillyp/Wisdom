@if($type == 'care')
<input type="hidden" name="file[]" value='{{ $fileId }}'>
@endif
@if($type == 'erp')
<input type="hidden" name="file" value='{{ $fileId }}'>
@endif
@if($type == 'letter' or $type == 'perm' or $type == 'coc' or $type == 'ep')
<input type="hidden" value='{{ $fileId }}'>
@endif
<div class="alert alert-primary" role="alert">
    <div class="row">
        <div class="col-auto"><i class="far fa-file"></i></div>
        <div class="col"><a href="/staff/forms/excur/file/{{ $fileId }}" target='_blank'>{{ $name }}</a></div>
        <div class="col-auto"><i class="fal fa-trash-alt wis-deletebin" onclick="deleteFile(this,{{$fileId}})"></i></div>
    </div>
</div>
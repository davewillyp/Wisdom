@if($type == 'care')
<input type="hidden" name="file[]" value='{{ $fileId }}'>
@endif
@if($type == 'erp')
<input type="hidden" name="file" value='{{ $fileId }}'>
@endif
@if($type == 'letter' or $type == 'perm' or $type == 'coc' or $type == 'ep')
<input type="hidden" value='{{ $fileId }}'>
@endif
<div>
<i class="far fa-file mr-1"></i><a href="/staff/forms/excur/file/{{ $fileId }}" target="_blank">{{ $name }}</a>
</div>
<div class='container mb-2'>
@foreach ($users as $user)
    <div class='row justify-content-center'>
        <div class='col-8'>
            <div class='shadow wispanel mb-2' onclick="updateDutyUser({{ $user->id }},'{{ $user->firstname }} {{ $user->surname }}')">
                <div class='container'>
                    <div class='row'>
                        <div class='col-auto p-2'>
                            <i class="fal fa-user-circle"></i>
                        </div>
                        <div class='col-auto p-2'>
                            {{ $user->firstname }} {{ $user->surname }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
</div>  
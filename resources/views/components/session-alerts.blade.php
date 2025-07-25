@if(session('success'))
    <x-alert type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-alert type="error" :message="session('error')" />
@endif

@if(session('warning'))
    <x-alert type="warning" :message="session('warning')" />
@endif

@if(session('info'))
    <x-alert type="info" :message="session('info')" />
@endif

@if($errors->any())
    <x-alert type="error" message="Ada kesalahan dalam pengisian form:">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif 
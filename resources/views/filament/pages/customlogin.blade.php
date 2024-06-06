

<x-filament-panels::page.simple>
    <style>
        img.fi-logo {
            /*width: 200px;*/
            /*height: 600px;*/
            opacity: 0;
        }

    </style>

    <x-slot name="heading">
        <center>
            <img src="{{asset('storage/logo_pnm.png')}}" width="200px"  />
            <br>

            <br>
            <h2>Login</h2>
        </center>
    </x-slot>
@if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}

            {{ $this->registerAction }}
        </x-slot>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.before') }}

    <x-filament-panels::form wire:submit="authenticate">

        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        >
        </x-filament-panels::form.actions>
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.after') }}
</x-filament-panels::page.simple>

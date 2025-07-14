@props(['type' => 'success', 'message', 'autodismiss' => true, 'autodismissDelay' => 3000])

@php
    // Definisikan style untuk setiap tipe alert
    $styles = [
        'success' => 'background-color: #064e3b; border-color: #10b981; color: #a7f3d0;',
        'error' => 'background-color: #7f1d1d; border-color: #ef4444; color: #fca5a5;',
        'warning' => 'background-color: #78350f; border-color: #f59e0b; color: #fcd34d;',
        'info' => 'background-color: #1e3a8a; border-color: #3b82f6; color: #93c5fd;'
    ][$type] ?? 'background-color: #064e3b; border-color: #10b981; color: #a7f3d0;';
    
    // SVG Icons
    $icons = [
        'success' => '<svg style="width: 1.25rem; height: 1.25rem; color: #34d399;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
        'error' => '<svg style="width: 1.25rem; height: 1.25rem; color: #f87171;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
        'warning' => '<svg style="width: 1.25rem; height: 1.25rem; color: #fbbf24;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
        'info' => '<svg style="width: 1.25rem; height: 1.25rem; color: #60a5fa;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    ][$type] ?? $icons['success'];
@endphp

<style>
    .alert-transition-enter {
        transition: opacity 0.2s ease-out;
        opacity: 0;
    }
    .alert-transition-enter-end {
        opacity: 1;
    }
    .alert-transition-leave {
        transition: opacity 0.2s ease-in;
        opacity: 1;
    }
    .alert-transition-leave-end {
        opacity: 0;
    }
</style>

<div x-data="{ show: true }" 
     x-show="show"
     x-init="@if($autodismiss) setTimeout(() => { show = false }, {{ $autodismissDelay }}) @endif"
     x-transition:enter="alert-transition-enter"
     x-transition:enter-end="alert-transition-enter-end"
     x-transition:leave="alert-transition-leave"
     x-transition:leave-end="alert-transition-leave-end"
     style="{{ $styles }} border-left-width: 4px; padding: 0.5rem 1rem; margin-top: 24px; margin-left: 32px; border-radius: 0.25rem; display: inline-block; max-width: fit-content; font-size: 0.875rem;" 
     role="alert" 
     id="alert-message">
     
    <div>
        <p style="font-weight: 500; margin: 0;">{{ $message }}</p>
        @if(isset($slot) && strlen(trim($slot)))
            <div style="margin-top: 0.25rem; font-size: 0.75rem;">
                {{ $slot }}
            </div>
        @endif
    </div>
</div> 
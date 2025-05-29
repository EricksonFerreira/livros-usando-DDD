@extends('autores.form', ['autor' => $autor])

@push('styles')
<style>
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered {
        min-height: 38px;
        padding: 0 0.5rem;
    }
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
        margin-top: 0.35rem;
        margin-right: 0.5rem;
    }
</style>
@endpush

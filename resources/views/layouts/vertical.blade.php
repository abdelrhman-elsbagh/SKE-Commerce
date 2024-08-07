<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.shared/title-meta', ['title' => $page_title])
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @yield('css')
    @include('layouts.shared/head-css', ['mode' => $mode ?? '', 'demo' => $demo ?? ''])

    <!-- Load jQuery directly from CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @vite(['resources/js/head.js'])
{{--    @vite(['node_modules/quill/dist/quill.core.css', 'node_modules/quill/dist/quill.snow.css', 'node_modules/quill/dist/quill.bubble.css'])--}}
{{--    @vite(['resources/js/pages/demo.quilljs.js'])--}}


    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <style>
        .select2-container--classic .select2-selection--multiple .select2-selection__choice {
            background-color: #17a2b8; /* info color */
            border: 1px solid #17a2b8; /* info color */
            color: #fff;
            padding: 0 10px;
            margin-top: 5px;
        }

        .select2-container--classic .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
            margin-right: 10px;
        }

        .select2-container--classic .select2-selection--multiple .select2-selection__choice__remove:hover {
            background-color: #138496; /* darker info color */
        }

        .select2-container--classic .select2-selection--multiple {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding: 0.375rem 0.75rem;
        }

        .select2-container--classic .select2-selection--multiple .select2-selection__rendered {
            padding: 0;
        }
        .res-table-card{
            overflow: scroll;
        }
        #basic-datatable{
            width: 100% !important;
        }
        #basic-datatable,
        #basic-datatable th,
        #basic-datatable td{
            text-align: center;
        }
    </style>
</head>

<body>
<div class="wrapper">

    @include('layouts.shared/topbar')

    @include('layouts.shared/left-sidebar')

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <div class="content" id="content">
            <!-- Start Content-->
            @yield('content')
        </div>
        @include('layouts.shared/footer')
    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

</div>

@include('layouts.shared/right-sidebar')
@include('layouts.shared/footer-script')
@vite(['resources/js/app.js', 'resources/js/layout.js'])
@yield('admin-script')

<script>
    // $(document).ready(function() {
    //     console.log('Document ready 1');
    //
    //     $(document).on('click', '.nav-link', function(e) {
    //         console.log("nav-link")
    //         e.preventDefault();
    //         var url = $(this).attr('href');
    //         console.log('Navigating to:', url);
    //
    //         history.pushState(null, '', url);
    //
    //         $.ajax({
    //             url: url,
    //             method: 'GET',
    //             success: function(data) {
    //                 console.log('AJAX success:', data);
    //                 $('#content').html($(data).find('#content').html());
    //             },
    //             error: function(xhr) {
    //                 console.error('Error loading page', xhr);
    //             }
    //         });
    //     });
    //
    //     // Handle back/forward navigation
    //     window.onpopstate = function() {
    //         var url = window.location.pathname;
    //         console.log('Popstate event:', url);
    //         $.ajax({
    //             url: url,
    //             method: 'GET',
    //             success: function(data) {
    //                 $('#content').html($(data).find('#content').html());
    //             },
    //             error: function(xhr) {
    //                 console.error('Error loading page', xhr);
    //             }
    //         });
    //     };
    //
    //     // Load the initial content
    //     var initialUrl = window.location.pathname;
    //     if (initialUrl !== '/') {
    //         console.log('Initial load:', initialUrl);
    //         $.ajax({
    //             url: initialUrl,
    //             method: 'GET',
    //             success: function(data) {
    //                 $('#content').html($(data).find('#content').html());
    //             },
    //             error: function(xhr) {
    //                 console.error('Error loading initial page', xhr);
    //             }
    //         });
    //     }
    // });
</script>
</body>

</html>

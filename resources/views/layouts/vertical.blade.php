<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.shared/title-meta', ['title' => $page_title])
    @yield('css')
    @include('layouts.shared/head-css', ['mode' => $mode ?? '', 'demo' => $demo ?? ''])

    <!-- Load jQuery directly from CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @vite(['resources/js/head.js'])
    @vite(['node_modules/quill/dist/quill.core.css', 'node_modules/quill/dist/quill.snow.css', 'node_modules/quill/dist/quill.bubble.css'])
    @vite(['resources/js/pages/demo.quilljs.js'])

    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/2.0.2/quill.min.js" integrity="sha512-1nmY9t9/Iq3JU1fGf0OpNCn6uXMmwC1XYX9a6547vnfcjCY1KvU9TE5e8jHQvXBoEH7hcKLIbbOjneZ8HCeNLA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


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

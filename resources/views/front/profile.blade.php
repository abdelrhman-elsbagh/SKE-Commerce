@extends('front.layout')

@section('title', 'Ske E-Commerce')

@section('content')
    <main class="page-main">
        <div class="uk-grid" data-uk-grid>
            <div class="uk-width-2-3@l">
                <div class="widjet --profile">
                    <div class="widjet__head">
                        <h3 class="uk-text-lead">Profile</h3>
                    </div>
                    <div class="widjet__body">
                        <div class="user-info">
                            <div class="user-info__avatar"><img src="{{ asset('assets/img/profile.png') }}" alt="profile"></div>
                            <div class="user-info__box">
                                <div class="user-info__title">John Doe</div>
                                <div class="user-info__text">Egypt, Member since May 2022</div>
                            </div>
                        </div>
                        <a class="uk-button uk-button-danger" href="04_profile.html"><i class="ico_edit"></i><span class="uk-margin-small-left">Edit Profile</span></a>
                    </div>
                </div>
                <div class="widjet --bio">
                    <div class="widjet__head">
                        <h3 class="uk-text-lead">Bio</h3>
                    </div>
                    <div class="widjet__body"><span>Here you can put your biography you need try to make it attractive and professional, just be honest and polite.</span></div>
                </div>
                <div class="widjet --activity">

                    <div class="widjet__body">
                        <div class="widjet-game">
                            <div class="widjet-game__media"><a href="10_game-profile.html"><img src="{{ asset('assets/img/game-2.jpg') }}" alt="image"></a></div>
                            <div class="widjet-game__info"><a class="widjet-game__title" href="10_game-profile.html"> Chrome Fear</a>
                                <div class="widjet-game__record">3 hours on record</div>
                                <div class="widjet-game__last-played">last played on 18 Feb, 2022</div>
                            </div>
                        </div>
                        <div class="widjet-game-info">
                            <div class="widjet-game-info__title">Achievement Progress</div>
                            <div class="widjet-game-info__progress"><span>50 of 150</span>
                                <div class="progress-box">
                                    <div class="progress-line" style="width: 80%"></div>
                                </div>
                            </div>
                            <div class="widjet-game-info__acheivement">
                                <ul>
                                    <li><img src="{{ asset('assets/img/acheivement-1.png') }}" alt="acheivement"></li>
                                    <li><img src="{{ asset('assets/img/acheivement-2.png') }}" alt="acheivement"></li>
                                    <li><img src="{{ asset('assets/img/acheivement-3.png') }}" alt="acheivement"></li>
                                    <li><img src="{{ asset('assets/img/acheivement-4.png') }}" alt="acheivement"></li>
                                    <li><img src="{{ asset('assets/img/acheivement-5.png') }}" alt="acheivement"></li>
                                    <li><span>+10</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="widjet__body">
                        <div class="widjet-game">
                            <div class="widjet-game__media"><a href="10_game-profile.html"><img src="{{ asset('assets/img/game-3.jpg') }}" alt="image"></a></div>
                            <div class="widjet-game__info"><a class="widjet-game__title" href="10_game-profile.html"> Retaliate of Prosecution</a>
                                <div class="widjet-game__record">0.2 hours on record</div>
                                <div class="widjet-game__last-played">last played on 25 Apr, 2022</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @if(session('success'))
        @push('scripts')
            <script>
                toastr.success("{{ session('success') }}");
            </script>
        @endpush
    @endif
@endsection

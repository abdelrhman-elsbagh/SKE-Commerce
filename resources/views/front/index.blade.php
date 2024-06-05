@extends('front.layout')

@section('title', 'Ske E-Commerce')

@section('content')
    <main class="page-main">
        <div class="uk-grid uk-child-width-1-6@xl uk-child-width-1-4@l uk-child-width-1-3@s uk-flex-middle uk-grid-small" data-uk-grid>
            <div>
                <div class="game-card">
                    <div class="game-card__box">
                        <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ asset('assets/img/game-1.jpg')}}" alt="Struggle of Rivalry" /></a></div>
                        <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Struggle of Rivalry</a>
                            <div class="game-card__genre">Shooter / Platformer</div>
                            <div class="game-card__rating-and-price">
                                <div class="game-card__rating"><span>4.8</span><i class="ico_star"></i></div>
                                <div class="game-card__price"><span>$4.99 </span></div>
                            </div>
                            <div class="game-card__bottom">
                                <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                                <div class="game-card__users">
                                    <ul class="users-list">
                                        <li><img src="{{ asset('assets/img/user-1.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-2.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-3.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-4.png')}}" alt="user" /></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="game-card">
                    <div class="game-card__box">
                        <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ asset('assets/img/game-2.jpg')}}" alt="Hunt of Duplicity" /></a></div>
                        <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Hunt of Duplicity</a>
                            <div class="game-card__genre">Action / Adventure</div>
                            <div class="game-card__rating-and-price">
                                <div class="game-card__rating"><span>4.6</span><i class="ico_star"></i></div>
                                <div class="game-card__price"><span>$9.99 </span></div>
                            </div>
                            <div class="game-card__bottom">
                                <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                                <div class="game-card__users">
                                    <ul class="users-list">
                                        <li><img src="{{ asset('assets/img/user-1.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-2.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-3.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-4.png')}}" alt="user" /></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="game-card">
                    <div class="game-card__box">
                        <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ asset('assets/img/game-3.jpg')}}" alt="Journey and Dimension" /></a></div>
                        <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Journey and Dimension</a>
                            <div class="game-card__genre">Survival / Strategy</div>
                            <div class="game-card__rating-and-price">
                                <div class="game-card__rating"><span>4.7</span><i class="ico_star"></i></div>
                                <div class="game-card__price"><span>$13.99 </span></div>
                            </div>
                            <div class="game-card__bottom">
                                <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                                <div class="game-card__users">
                                    <ul class="users-list">
                                        <li><img src="{{ asset('assets/img/user-1.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-2.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-3.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-4.png')}}" alt="user" /></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="game-card">
                    <div class="game-card__box">
                        <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ asset('assets/img/game-4.jpg')}}" alt="Reckoning and Freedom" /></a></div>
                        <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Reckoning and Freedom</a>
                            <div class="game-card__genre">Strategy</div>
                            <div class="game-card__rating-and-price">
                                <div class="game-card__rating"><span>4.1</span><i class="ico_star"></i></div>
                                <div class="game-card__price"><span>$49.99 </span></div>
                            </div>
                            <div class="game-card__bottom">
                                <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                                <div class="game-card__users">
                                    <ul class="users-list">
                                        <li><img src="{{ asset('assets/img/user-1.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-2.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-3.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-4.png')}}" alt="user" /></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="game-card">
                    <div class="game-card__box">
                        <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ asset('assets/img/game-5.jpg')}}" alt="Pillage of Redemption" /></a></div>
                        <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Pillage of Redemption</a>
                            <div class="game-card__genre">Survival / Strategy</div>
                            <div class="game-card__rating-and-price">
                                <div class="game-card__rating"><span>4.7</span><i class="ico_star"></i></div>
                                <div class="game-card__price"><span>$13.99 </span></div>
                            </div>
                            <div class="game-card__bottom">
                                <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                                <div class="game-card__users">
                                    <ul class="users-list">
                                        <li><img src="{{ asset('assets/img/user-1.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-2.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-3.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-4.png')}}" alt="user" /></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="game-card">
                    <div class="game-card__box">
                        <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ asset('assets/img/game-6.jpg')}}" alt="Invade of Heroes" /></a></div>
                        <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Invade of Heroes</a>
                            <div class="game-card__genre">Strategy</div>
                            <div class="game-card__rating-and-price">
                                <div class="game-card__rating"><span>4.1</span><i class="ico_star"></i></div>
                                <div class="game-card__price"><span>$49.99 </span></div>
                            </div>
                            <div class="game-card__bottom">
                                <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                                <div class="game-card__users">
                                    <ul class="users-list">
                                        <li><img src="{{ asset('assets/img/user-1.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-2.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-3.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-4.png')}}" alt="user" /></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="game-card">
                    <div class="game-card__box">
                        <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ asset('assets/img/game-7.jpg')}}" alt="Genesis and Renegade" /></a></div>
                        <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Genesis and Renegade</a>
                            <div class="game-card__genre">Shooter / Platformer</div>
                            <div class="game-card__rating-and-price">
                                <div class="game-card__rating"><span>4.8</span><i class="ico_star"></i></div>
                                <div class="game-card__price"><span>$4.99 </span></div>
                            </div>
                            <div class="game-card__bottom">
                                <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                                <div class="game-card__users">
                                    <ul class="users-list">
                                        <li><img src="{{ asset('assets/img/user-1.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-2.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-3.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-4.png')}}" alt="user" /></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div>
                <div class="game-card">
                    <div class="game-card__box">
                        <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ asset('assets/img/game-8.jpg')}}" alt="Barbarians and Truth" /></a></div>
                        <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Barbarians and Truth</a>
                            <div class="game-card__genre">Shooter / Platformer</div>
                            <div class="game-card__rating-and-price">
                                <div class="game-card__rating"><span>4.8</span><i class="ico_star"></i></div>
                                <div class="game-card__price"><span>$4.99 </span></div>
                            </div>
                            <div class="game-card__bottom">
                                <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                                <div class="game-card__users">
                                    <ul class="users-list">
                                        <li><img src="{{ asset('assets/img/user-1.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-2.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-3.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-4.png')}}" alt="user" /></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div>
                <div class="game-card">
                    <div class="game-card__box">
                        <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ asset('assets/img/game-9.jpg')}}" alt="Fire and Demons" /></a></div>
                        <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Fire and Demons</a>
                            <div class="game-card__genre">Shooter / Platformer</div>
                            <div class="game-card__rating-and-price">
                                <div class="game-card__rating"><span>4.8</span><i class="ico_star"></i></div>
                                <div class="game-card__price"><span>$4.99 </span></div>
                            </div>
                            <div class="game-card__bottom">
                                <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                                <div class="game-card__users">
                                    <ul class="users-list">
                                        <li><img src="{{ asset('assets/img/user-1.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-2.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-3.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-4.png')}}" alt="user" /></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div>
                <div class="game-card">
                    <div class="game-card__box">
                        <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ asset('assets/img/game-6.jpg')}}" alt="Strife of Retribution" /></a></div>
                        <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Strife of Retribution</a>
                            <div class="game-card__genre">Strategy</div>
                            <div class="game-card__rating-and-price">
                                <div class="game-card__rating"><span>4.1</span><i class="ico_star"></i></div>
                                <div class="game-card__price"><span>$49.99 </span></div>
                            </div>
                            <div class="game-card__bottom">
                                <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                                <div class="game-card__users">
                                    <ul class="users-list">
                                        <li><img src="{{ asset('assets/img/user-1.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-2.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-3.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-4.png')}}" alt="user" /></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="game-card">
                    <div class="game-card__box">
                        <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ asset('assets/img/game-10.jpg')}}" alt="Crimson Resurrection" /></a></div>
                        <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Crimson Resurrection</a>
                            <div class="game-card__genre">Shooter / Platformer</div>
                            <div class="game-card__rating-and-price">
                                <div class="game-card__rating"><span>4.8</span><i class="ico_star"></i></div>
                                <div class="game-card__price"><span>$4.99 </span></div>
                            </div>
                            <div class="game-card__bottom">
                                <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                                <div class="game-card__users">
                                    <ul class="users-list">
                                        <li><img src="{{ asset('assets/img/user-1.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-2.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-3.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-4.png')}}" alt="user" /></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="game-card">
                    <div class="game-card__box">
                        <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ asset('assets/img/game-4.jpg')}}" alt="Bio Armada" /></a></div>
                        <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Bio Armada</a>
                            <div class="game-card__genre">Strategy</div>
                            <div class="game-card__rating-and-price">
                                <div class="game-card__rating"><span>4.1</span><i class="ico_star"></i></div>
                                <div class="game-card__price"><span>$49.99 </span></div>
                            </div>
                            <div class="game-card__bottom">
                                <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                                <div class="game-card__users">
                                    <ul class="users-list">
                                        <li><img src="{{ asset('assets/img/user-1.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-2.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-3.png')}}" alt="user" /></li>
                                        <li><img src="{{ asset('assets/img/user-4.png')}}" alt="user" /></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

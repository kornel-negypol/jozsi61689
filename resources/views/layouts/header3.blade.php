<div class="header" id="header">
	<div class="row">

				<div class="header-logo col-md-4 col-md-offset-0 col-sm-6 col-xs-6" id="header-logo">
					<a href="#">
						<img src="/images/NP_logo_new.jpg">
					</a>
				</div>

				<div class="header-pic-cont col-md-4 hidden-xs hidden-sm">
					<div id="bolt-kepek">
						<img src="/images/Kep_kozepen.png">
					</div>
                </div>
                <div class="hidden-xs hidden-sm col-md-3 col-md-offset-0 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1" id="header-third-container">
					<div id="header-contact">
						<div class="phone-row"><i class="fa fa-phone fa-rotate-90" aria-hidden="true"></i> +36 1 350 6157</div>
						<div class="phone-row"><i class="fa fa-mobile" aria-hidden="true"></i> +36 70 369 1873</div>
						<div class="email-row"><i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:support@negypolus.hu">support@negypolus.hu</a></div>
					</div>
                </div>
    </div>
                
	<div id="menu-row" class="row">
				<div id="header-menu" class="col-md-12">
					<div id="header-menu-right">
						<ul class="nav navbar-nav ">
							<li><a href="/mainPage" class="" title="Hardver3">Főoldal</a></li>
							<li><a href="/eszkozok" class="" title="Hardver2">IT eszközök beszerzése</a></li>
							<li><a href="/szabalyzatok" class="" title="Hardver1">Szabályzatok</a></li>
							<li><a href="/leltar" class="" title="Hardver">Hardver-szoftver leltár</a></li>
							<li><a href="/docs" class="" title="Rajzok">Rajzok, dokumentumok</a></li>
							<li><a href="/news" title="Tesztek">Hírek, tesztek</a></li>
							<li><a href="/contact"  >Kapcsolat</a></li>
							<li><a href="#" onclick="event.preventDefault(); window.location.href = '/myLogout'"><i class="fa fa-user" aria-hidden="true"></i> Kilépés</a></li>
						</ul>
					</div>
				</div>
	</div>
	<div id="header-instant-search-container">
		<form name="search-form" role="form" method="POST" action="{{ url('/searchTicket') }}">
					{{ csrf_field() }}
					<div id="search-stripe-text">
						<b>HIBAJEGY</b> ÉS <b>FELADAT KEZELŐ RENDSZER</b></span>
						<i id="first-chevron" class="fa fa-chevron-right hidden-xs hidden-sm hidden-md" aria-hidden="true"></i>
						<i id="second-chevron" class="fa fa-chevron-right hidden-xs hidden-sm hidden-md" aria-hidden="true"></i>
						<i id="third-chevron" class="fa fa-chevron-right" aria-hidden="true"></i>
					</div>
					<div id="header-search">
                        <div id="search" class="input-group">
                            <div id="search-before-i"><i class="fa fa-search" aria-hidden="true"></i></div>
                            <input type="text" name="search" value="" placeholder="Keresési feltételek megadása" class="form-control input-md ps-input" id="header-search-textbox"/>
                            <span class="input-group-btn">
                                <button type="submit" id="header-search-button" class="ps-button-instant-search"><!--<i class="fa fa-search"></i>-->Eredmény</button>
                            </span>
                        </div>
                    </div>
		</form>
    </div>

</div>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="@yield('description_custom')@if(!Request::route()->named('seo') && !Request::route()->named('profile')){{trans('seo.description')}}@endif">
  <meta name="keywords" content="@yield('keywords_custom'){{ trans('seo.keywords') }}" />
  <meta name="theme-color" content="{{ auth()->check() && auth()->user()->dark_mode == 'on' ? '#303030' : $settings->color_default }}">
  <title>{{ auth()->check() && User::notificationsCount() ? '('.User::notificationsCount().') ' : '' }}@section('title')@show {{$settings->title.' - '.__('seo.slogan')}}</title>
  <!-- Favicon -->
  <link href="{{ url('public/img', $settings->favicon) }}" rel="icon">

  @if ($settings->google_tag_manager_head != '')
  {!! $settings->google_tag_manager_head !!}
  @endif

  @include('includes.css_general')

  @if ($settings->status_pwa)
    @laravelPWA
  @endif

  @yield('css')

 @if ($settings->google_analytics != '')
  {!! $settings->google_analytics !!}
  @endif
	
	<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-CFD13ZXQL4"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-CFD13ZXQL4');
</script>
	
	<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1168453381237441');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1168453381237441&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
	
	
</head>

<body>
  @if ($settings->google_tag_manager_body != '')
  {!! $settings->google_tag_manager_body !!}
  @endif

  @if ($settings->disable_banner_cookies == 'off')
  <div class="btn-block text-center showBanner padding-top-10 pb-3 display-none">
    <i class="fa fa-cookie-bite"></i> {{trans('general.cookies_text')}}
    @if ($settings->link_cookies != '')
      <a href="{{$settings->link_cookies}}" class="mr-2 text-white link-border" target="_blank">{{ trans('general.cookies_policy') }}</a>
    @endif
    <button class="btn btn-sm btn-primary" id="close-banner">{{trans('general.go_it')}}
    </button>
  </div>
@endif

  <div id="mobileMenuOverlay" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"></div>

  @auth
    @if (! request()->is('messages/*') && ! request()->is('live/*'))
    @include('includes.menu-mobile')
  @endif
  @endauth

  @if ($settings->alert_adult == 'on')
    <div class="modal fade" tabindex="-1" id="alertAdult">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body p-4">
          <p>{{ __('general.alert_content_adult') }}</p>
        </div>
        <div class="modal-footer border-0 pt-0">
          <a href="https://google.com" class="btn e-none p-0 mr-3">{{trans('general.leave')}}</a>
          <button type="button" class="btn btn-primary" id="btnAlertAdult">{{trans('general.i_am_age')}}</button>
        </div>
      </div>
    </div>
  </div>
  @endif


  <div class="popout popout-error font-default"></div>

@if (auth()->guest() && request()->path() == '/' && $settings->home_style == 0
    || auth()->guest() && request()->path() != '/' && $settings->home_style == 0
    || auth()->guest() && request()->path() != '/' && $settings->home_style == 1
    || auth()->check()
    )
  @include('includes.navbar')
  @endif

  <main @if (request()->is('messages/*') || request()->is('live/*')) class="h-100" @endif role="main">
    @yield('content')

    @if (auth()->guest() 
          && ! request()->route()->named('profile')
          && ! request()->is(['creators', 'category/*', 'creators/*'])
          || auth()->check()
          && request()->path() != '/'
          && ! request()->route()->named('profile')
          && ! request()->is([
            'my/bookmarks', 
            'my/likes', 
            'my/purchases', 
            'explore', 
            'messages', 
            'messages/*', 
            'creators', 
            'category/*', 
            'creators/*', 
            'live/*'
            ])          
          )

          @if (auth()->guest() && request()->path() == '/' && $settings->home_style == 0
                || auth()->guest() && request()->path() != '/' && $settings->home_style == 0
                || auth()->guest() && request()->path() != '/' && $settings->home_style == 1
                || auth()->check()
                  )

                  @if (auth()->guest() && $settings->who_can_see_content == 'users')
                    <div class="text-center py-3 px-3">
                      @include('includes.footer-tiny')
                    </div>
                  @else
                    @include('includes.footer')
                  @endif

          @endif

  @endif

  @guest

  @if (Helper::showLoginFormModal())
      @include('includes.modal-login')
    @endif

  @endguest

  @auth

    @if ($settings->disable_tips == 'off')
     @include('includes.modal-tip')
   @endif

    @include('includes.modal-payperview')

    @if ($settings->live_streaming_status == 'on')
      @include('includes.modal-live-stream')
    @endif

    @if ($settings->allow_scheduled_posts)
      @include('includes.modal-scheduled-posts')
    @endif
    
  @endauth

  @guest
    @include('includes.modal-2fa')
  @endguest
</main>

  @include('includes.javascript_general')

  @yield('javascript')

@auth
  <div id="bodyContainer"></div>
@endauth
</body>
</html>

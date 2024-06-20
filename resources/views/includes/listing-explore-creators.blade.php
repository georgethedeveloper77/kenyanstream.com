@foreach ($users as $user)

<a href="{{url($user->username)}}">
<div class="w-100 h-100 d-block" style="background: @if ($user->cover != '') url({{ route('resize', ['path' => 'cover', 'file' => $user->cover, 'size' => 480]) }})  @endif #505050 center center; border-radius: 6px; background-size: cover;">

  <div class="card-cover position-relative" style="height: 50px">
    @if ($user->free_subscription == 'yes')
    <span class="badge-free px-2 py-1 text-uppercase position-absolute rounded">{{ __('general.free') }}</span>
  @endif
  </div>

  <li class="list-group-item mb-2 border-0" style="background: rgba(0,0,0,.35);">
         <div class="media">
          <div class="media-left mr-3">
              <img class="media-object rounded-circle avatar-user-home" src="{{Helper::getFile(config('path.avatar').$user->avatar)}}"  width="95" height="95">
          </div>
          <div class="media-body text-truncate">
            <h5 class="media-heading mb-1">
              <a href="{{url($user->username)}}" class="stretched-link text-white">
                <strong>{{$user->hide_name == 'yes' ? $user->username : $user->name}}</strong>
              </a>
              
            </h5>
          </div>
      </div>
  </li>
	</div><!-- cover -->
  </a>
  @endforeach

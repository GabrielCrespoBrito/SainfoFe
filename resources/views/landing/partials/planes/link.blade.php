<a target="_blank" class="link-confirm link link-{{ $plan->is_demo ? 'login' : 'register' }}" href="{{ $plan->is_demo ? route('login', ['demo' => '1']) : route('register', ['id' => $plan->id]) }}">  
  {{  $plan->is_demo ? 'Ingresa' : 'Registrate' }}
</a>
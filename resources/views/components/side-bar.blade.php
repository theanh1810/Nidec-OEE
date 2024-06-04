<aside class="main-sidebar sidebar-light-warning elevation-4">

    <a
        href="{{ route('home', [0]) }}"
        class="brand-link d-flex align-items-center"
        style="margin-left: 8%;"
    >
        <img
            src="{{ asset('dist/img/sti.png')}}"
            alt="AdminLTE Logo"
            width="100%"
            height="auto"
            style="opacity: .8; width: 2.9rem;"
        >
        <div class="brand-text font-weight-light ml-2">
            STI-MES
        </div>
    </a>

    <div class="sidebar">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img
                    src="{{asset('dist/img/')}}/{{isset(Auth::user()->avatar) ? Auth::user()->avatar : ''}}"
                    class="img-circle elevation-2"
                    alt="User Image"
                >
            </div>
            <div class="info">
                <a
                    href="#"
                    class="d-block id-user"
                    style="text-transform: capitalize; font-weight: bold;"
                >
                    {{isset(Auth::user()->name) ? Auth::user()->name : ''}}
                </a>
            </div>
        </div>

        <nav class="mt-2">
            <ul
                class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false"
            >
                @foreach($menu as $item)
                    @if($checkRole($item['roles']))
                        <li class="nav-item has-treeview">
                            <a
                                href="{{ $item['route'] }}"
                                class="nav-link {{ $item['class'] }}"
                            >
                                <i class="nav-icon {{ $item['icon'] }}"></i>
                                <p>
                                    {{ $item['title'] }}
                                    @if(isset($item['children']))
                                        <i class="fas fa-angle-left right"></i>
                                    @endif
                                </p>
                            </a>
                            @if(isset($item['children']))
                                <ul class="nav nav-treeview">
                                    @foreach($item['children'] as $child)
                                        @if($checkRole($child['roles']))
                                            <li class="nav-item {{ (isset($child['hide']) && $child['hide'] == true) ? 'hide' : '' }}">
                                                <a
                                                    href="{{ $child['route'] }}"
                                                    class="nav-link {{ $child['class'] }}"
                                                >
                                                <i class="far fa-circle {{ $child['icon'] }}"></i>
                                                <p>{{ $child['title'] }}</p>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </li>    
                    @endif
                @endforeach
            </ul>
        </nav>

    </div>
    
</aside>
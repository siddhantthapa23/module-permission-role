<div class="fixed">
    <aside class="main-sidebar box-shadow">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel bg-light-red">
                <div class="pull-left image">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('uploads/users/' . auth()->user()->avatar) }}" class="img-circle" width="48" height="48" alt="{{ auth()->user()->full_name }}"> 
                    @else
                        <img src="{{ URL::asset('images/default-avatar.png') }}" alt="User" width="48" height="48" /> 
                    @endif
                </div>

                <div class="pull-left info">
                    <p>{{ auth()->user()->full_name }}</p>

                    @php
                        $roles = implode(" ", auth()->user()->getRoleNames()->toArray());
                    @endphp

                    <p>{{ $roles }}</p>
                </div>
            </div>
        
            <!-- Sidebar Menu -->
            <ul class="sidebar-menu" data-widget="tree">
                <li>
                    <a href="{{ route('dashboard') }}" id="dashboard">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
        
                @foreach($modules as $key => $module)

                    @if (array_key_exists('childrens', $module))
                        <li class="@if(count($module['childrens']) > 0) treeview @endif">
                            <a href="@if(count($module['childrens']) > 0) javascript:void(0); @else {!! route(str_slug($module['name'] . '.index')) !!} @endif"
                                @if(count($module['childrens']) > 0) class="menu-toggle" @endif>
                                    <i class="{{ $module['icon'] }}"></i><span>{{ $module['name'] }}</span>
                                @if(count($module['childrens'])>0)
                                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                                @endif
                            </a>
                            @if(count($module['childrens']) > 0)
                                <ul class="treeview-menu box-shadow">
                                    @foreach($module['childrens'] as $children)
                                        <li id="{{ strtolower(str_slug($children['name'])) }}">
                                            <a href="{{ route(str_slug($module['name']) . '.' . strtolower(str_slug($children['name'])).'.index') }}">
                                                <i class="{{ $children['icon'] }}"></i>{{ $children['name'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @else
                        <li>
                            <a href="{{ route(str_slug($module['name']) . '.index') }}">
                                <i class="{{ $module['icon'] }}"></i>
                                <span>{{ $module['name'] }}</span>
                            </a>
                        </li>
                    @endif
                    
                @endforeach

            </ul>
            <!-- /.sidebar-menu -->

        </section>
        <!-- /.sidebar -->
    </aside>
</div>
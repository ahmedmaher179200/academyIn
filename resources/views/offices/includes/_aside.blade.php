<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{auth('office')->user()->getImage()}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{auth('office')->user()->username}}</p>
                <a href="#"><i class="fa fa-circle text-success"></i>statue</a>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
            <li class="{{request()->is('*/offices')? 'active':''}}">
                <a href="{{url('offices')}}"><i class="fa fa-users"></i><span>dashboard</span></a>
            </li>

            <li class="{{(request()->is('*/offices/teachers') || request()->is('*/offices/teachers/*'))? 'active':''}}">
                <a href="{{url('offices/teachers')}}"><i class="fa fa-users"></i><span>teachers</span></a>
            </li>

        </ul>
    </section>
</aside>
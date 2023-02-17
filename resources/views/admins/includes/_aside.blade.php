<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{auth('admin')->user()->getImage()}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{auth('admin')->user()->username}}</p>
                <a href="#"><i class="fa fa-circle text-success"></i>{{ trans('admin.statue') }}</a>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
            <li class="{{request()->is('admins')? 'active':''}}">
                <a href="{{url('admins')}}"><i class="fa fa-users"></i><span>{{ trans('admin.dashboard') }}</span></a>
            </li>

            @if (auth('admin')->user()->isAbleTo('read-admins'))
                <li class="{{(request()->is('*/admins/admins') || request()->is('*/admins/admins/*'))? 'active':''}}">
                    <a href="{{url('admins/admins')}}"><i class="fa fa-users"></i><span>{{ trans('admin.admins') }}</span></a>
                </li>
            @endif

            @if (auth('admin')->user()->isAbleTo('read-roles'))
                <li class="{{(request()->is('*/admins/roles') || request()->is('*/admins/roles/*'))? 'active':''}}">
                    <a href="{{url('admins/roles')}}"><i class="fa fa-users"></i><span>{{ trans('admin.roles') }}</span></a>
                </li>
            @endif

            @if (auth('admin')->user()->isAbleTo('read-countries'))
                <li class="{{(request()->is('*/admins/countries') || request()->is('*/admins/countries/*'))? 'active':''}}">
                    <a href="{{url('admins/countries')}}"><i class="fa fa-users"></i><span>{{ trans('admin.countries') }}</span></a>
                </li>
            @endif

            @if (auth('admin')->user()->isAbleTo('read-curriculums'))
                <li class="{{(request()->is('*/admins/main_subjects') || request()->is('*/admins/main_subjects/*'))? 'active':''}}">
                    <a href="{{url('admins/main_subjects')}}"><i class="fa fa-users"></i><span>{{ trans('admin.main subjects') }}</span></a>
                </li>
            @endif

            @if (auth('admin')->user()->isAbleTo('read-curriculums'))
                <li class="{{(request()->is('*/admins/curriculums') || request()->is('*/admins/curriculums/*'))? 'active':''}}">
                    <a href="{{url('admins/curriculums')}}"><i class="fa fa-users"></i><span>{{ trans('admin.curriculums') }}</span></a>
                </li>
            @endif

            {{-- @if (auth('admin')->user()->isAbleTo('read-promo_codes'))
                <li class="{{(request()->is('*/admins/promo_codes')|| request()->is('*/admins/promo_codes/*'))? 'active':''}}">
                    <a href="{{url('admins/promo_codes')}}"><i class="fa fa-users"></i><span>promo_codes</span></a>
                </li>
            @endif --}}

            @if (auth('admin')->user()->isAbleTo('read-questions'))
                <li class="{{(request()->is('*/admins/questions') || request()->is('*/admins/questions/*'))? 'active':''}}">
                    <a href="{{url('admins/questions')}}"><i class="fa fa-users"></i><span>{{ trans('admin.questions') }}</span></a>
                </li>
            @endif

            @if (auth('admin')->user()->isAbleTo('read-students'))
                <li class="{{(request()->is('*/admins/students') || request()->is('*/admins/students/*'))? 'active':''}}">
                    <a href="{{url('admins/students')}}"><i class="fa fa-users"></i><span>{{ trans('admin.students') }}</span></a>
                </li>
            @endif

            @if (auth('admin')->user()->isAbleTo('read-teachers'))
                <li class="{{(request()->is('*/admins/teachers') || request()->is('*/admins/teachers/*'))? 'active':''}}">
                    <a href="{{url('admins/teachers')}}"><i class="fa fa-users"></i><span>{{ trans('admin.teachers') }}</span></a>
                </li>
            @endif

            {{-- @if (auth('admin')->user()->isAbleTo('read-class_types'))
                <li class="{{(request()->is('*/admins/levels_cost')|| request()->is('*/admins/levels_cost/*'))? 'active':''}}">
                    <a href="{{url('admins/levels_cost')}}"><i class="fa fa-users"></i><span>levels cost</span></a>
                </li>
            @endif --}}

            {{-- @if (auth('admin')->user()->isAbleTo('read-class_types'))
                <li class="{{(request()->is('*/admins/years_cost')|| request()->is('*/admins/years_cost/*'))? 'active':''}}">
                    <a href="{{url('admins/years_cost')}}"><i class="fa fa-users"></i><span>years cost</span></a>
                </li>
            @endif --}}

            {{-- @if (auth('admin')->user()->isAbleTo('read-class_types'))
                <li class="{{(request()->is('*/admins/countries_cost') || request()->is('*/admins/countries_cost/*'))? 'active':''}}">
                    <a href="{{url('admins/countries_cost')}}"><i class="fa fa-users"></i><span>countries cost</span></a>
                </li>
            @endif --}}

            {{-- @if (auth('admin')->user()->isAbleTo('read-class_types'))
                <li class="{{(request()->is('*/admins/company_percentages') || request()->is('*/admins/company_percentages/*'))? 'active':''}}">
                    <a href="{{url('admins/company_percentages')}}"><i class="fa fa-users"></i><span>companies percentage</span></a>
                </li>
            @endif --}}

            {{-- @if (auth('admin')->user()->isAbleTo('read-class_types')) 
                <li class="{{(request()->is('*/admins/students_numbers_cost')|| request()->is('*/admins/students_numbers_cost/*'))? 'active':''}}">
                    <a href="{{url('admins/students_numbers_cost')}}"><i class="fa fa-users"></i><span>students numbers cost</span></a>
                </li>
            @endif --}}

            @if (auth('admin')->user()->isAbleTo('read-settings'))
                <li class="{{(request()->is('*/admins/settings/edit') || request()->is('*/admins/settings/edit/*'))? 'active':''}}">
                    <a href="{{url('admins/settings/edit')}}"><i class="fa fa-users"></i><span>{{ trans('admin.settings') }}</span></a>
                </li>
            @endif

            @if (auth('admin')->user()->isAbleTo('read-class_types'))
                <li class="{{(request()->is('*/admins/class_types') || request()->is('*/admins/class_types/*'))? 'active':''}}">
                    <a href="{{url('admins/class_types')}}"><i class="fa fa-users"></i><span>{{ trans('admin.class types') }}</span></a>
                </li>
            @endif

            @if (auth('admin')->user()->isAbleTo('read-offers'))
                <li class="{{(request()->is('*/admins/offers') || request()->is('*/admins/offers/*'))? 'active':''}}">
                    <a href="{{url('admins/offers')}}"><i class="fa fa-users"></i><span>{{ trans('admin.offers') }}</span></a>
                </li>
            @endif
            
            @if (auth('admin')->user()->isAbleTo('read-classes'))
                <li class="{{(request()->is('*/admins/classes') || request()->is('*/admins/classes/*'))? 'active':''}}">
                    <a href="{{url('admins/classes')}}"><i class="fa fa-users"></i><span>{{ trans('admin.classes') }}</span></a>
                </li>
            @endif

            <li class="{{(request()->is('*/admins/contact_us') || request()->is('*/admins/contact_us/*'))? 'active':''}}">
                <a href="{{url('admins/contact_us')}}"><i class="fa fa-users"></i><span>{{ trans('admin.contact us') }}</span></a>
            </li>
        </ul>
    </section>
</aside>
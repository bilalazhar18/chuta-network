<nav id="wt-nav" class="wt-nav navbar-expand-lg">
  
    <div class="collapse navbar-collapse wt-navigation" id="navbarNav">
        <ul class="navbar-nav">
          
            <?php
           
            if(Auth::user()) {
                 $role = DB::table('model_has_roles')->select('role_id')->where('model_id', Auth::user()->id)
            ->first();
            if (!empty($role)) {
            if($role->role_id==1)
            {
                    ?>
            <li>
                    <a href="{{{ url('/') }}}">
                    Home
                    </a>
                    </li>
                   
                    <?php
                    }
                    elseif($role->role_id==2){
                    ?>

                    <li>
                    <a href="{{{ url('/') }}}">
                    Home
                    </a>
                    </li>
                    <li>
                    <a href="{{url('search-results?type=student')}}">
                    {{{ trans('lang.view_students') }}}
                    </a>
                    </li>
              <?php 
          }
              else{

               ?>
                <li>
                    <a href="{{{ url('/') }}}">
                    Home
                    </a>
                    </li>
                    <li>
                    <a href="{{url('search-results?type=student')}}">
                    {{{ trans('lang.view_students') }}}
                    </a>
                    </li>

                    <li>
                    <a href="{{url('search-results?type=job')}}">
                    {{{ trans('lang.browse_jobs') }}}
                    </a>
                    </li>

                    <li>
                 <!--    <a href="{{url('search-results?type=resource')}}">
                    {{{ trans('lang.browse_resources') }}}

                    </a> -->

                <a href="" data-toggle="modal" data-target="#exampleModal">{{{ trans('lang.browse_resources') }}}</a>
                </li>

            <?php 
            }
        }
    }
        else{
            ?>
             <li>
                    <a href="{{{ url('/') }}}">
                    Home
                    </a>
                    </li>
                    <li>
                    <a href="{{url('search-results?type=student')}}">
                    {{{ trans('lang.view_students') }}}
                    </a>
                    </li>

                    <li>
                    <a href="{{url('search-results?type=job')}}">
                    {{{ trans('lang.browse_jobs') }}}
                    </a>
                    </li>

                    <li>
                    <a href="{{url('search-results?type=resource')}}">
                    {{{ trans('lang.browse_resources') }}}
                    </a>

                     
                </li>
    <?php }
            ?> 
        
        </ul>
    </div>
</nav>

@extends(file_exists(resource_path('views/extend/back-end/master.blade.php')) ? 'extend.back-end.master' : 'back-end.master')
@section('content')
    <div class="dpts-listing" id="reviews">
        @if (Session::has('message'))
            <div class="flash_msg">
                <flash_messages :message_class="'success'" :time ='5' :message="'{{{ Session::get('message') }}}'" v-cloak></flash_messages>
            </div>
        @elseif (Session::has('error'))
            <div class="flash_msg">
                <flash_messages :message_class="'danger'" :time ='5' :message="'{{{ Session::get('error') }}}'" v-cloak></flash_messages>
            </div>
        @endif
        <section class="wt-haslayout wt-dbsectionspace la-review-holder">
            <div class="row">
                
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 float-right">
                    <div class="wt-dashboardbox">
                        <div class="wt-dashboardboxtitle wt-titlewithsearch">
                            <h2>{{{ trans('lang.review_options') }}}</h2>
                            <a href="javascript:void(0);" v-if="this.is_show" @click="deleteChecked('{{ trans('lang.ph_delete_confirm_title') }}', '{{ trans('lang.ph_review_delete_message') }}')" class="wt-skilldel">
                                <i class="lnr lnr-trash"></i>
                                <span>{{ trans('lang.del_select_rec') }}</span>
                            </a>
                        </div>
                        @if ($review_options->count() > 0)
                            <div class="wt-dashboardboxcontent wt-categoriescontentholder">
                                <table class="wt-tablecategories" id="checked-val">
                                    <thead>
                                        <tr>
                                            
                                            <th>User Name</th>
                                            <th>Email</th>
                                            <th>Response</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $counter = 0;

                                         @endphp
                                        @foreach ($review_options as $option)
                                        @php $user = \App\User::find($option->user_id);
                                         
                                        @endphp
                                            <tr class="del-{{{ $option->id }}}" v-bind:id="optionID">
                                               
                                                <td>{{{ ucwords(\App\Helper::getUserName($option->user_id)) }}}</td>
                                                <td>{{{ $user->email }}}</td>
                                             
                                                 <td>
                                                    @if($option->feedback==1)
                                                    Yes
                                                    @else
                                                    No
                                                    @endif                                              
                                                 </td>
                                                <td>
                                                    <div class="wt-actionbtn">
                                                        
                                                        <delete :title="'{{trans("lang.ph_delete_confirm_title")}}'" :id="'{{$option->id}}'" :message="'{{trans("lang.ph_review_delete_message")}}'" :url="'{{url('admin/deletefeed/')}}'"></delete>
                                                    </div>
                                                </td>
                                            </tr>
                                            @php $counter++; @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                                @if( method_exists($review_options,'links') ) {{ $review_options->links('pagination.custom') }} @endif
                            </div>
                        @else
                            @if (file_exists(resource_path('views/extend/errors/no-record.blade.php'))) 
                                @include('extend.errors.no-record')
                            @else 
                                @include('errors.no-record')
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

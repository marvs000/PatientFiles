@extends('layouts.masterLayout')
@section('title', 'List of Pediatricians | Pedix')
<style type="text/css">
div#patientListing:hover
  {
     background-color: #E3E3E3;
  }

</style>
@section('content')
<section class="content-header" style="margin-bottom:20px">
      <h1>
          List of Peditricians
     </h1>
 </section>

    @if (session('delete'))
          <small style=" padding-top: 5px;color: white;background-color: red;font-style: italic;" class="box-title alert alert-{{session ('delete.type')}} form-control" >
                  {{session('delete.text')}}
          </small> 
    @endif
    @if (session('added'))
          <small style=" padding-top: 5px;color: white;background-color: red;font-style: italic;" class="box-title alert alert-{{session ('added.type')}} form-control" >
                  {{session('added.text')}}
          </small> 
    @endif
	<div class="panel panel-default" style="margin-top:20px;">
  <div class="panel-body" >
      <div class="input-group pull-left" style="width: 50%" > 
          <a href="/account/add_account" class="btn btn-primary"><span class="ion ion-person-add"></span> Add Pediatricians</a>
      </div>
       <div class="input-group pull-right" style="width: 50%" >           
             <input  type="text" id="search-pediatrician" class="form-control" placeholder="Search for...">
            <span class="input-group-btn">
              <button id="search_ped" class="btn btn-default" type="button">Go!</button>
            </span>
      </div>
      <br>
      <br>
      <hr>
<!-- CARD TABLE -->  
          @foreach($users as $user) 
            @if (! $user->user_type_id == 1)
            @else
           <div  class="row container-fluid contact-name"    id="patientListing"   >
             <ul style="padding:0;" class="patient-list-style" >                 
                <li style="list-style: none;">
                  <div class="row">
                      <div class="col-md-3 ">
                          <img   class="img-circle" src="/img/generic.png" style="width: 50%" >
                      </div>
                  <div class="col-md-9">
                      <div class="col-md-8">
                          <h3><strong>{{$user->firstname .' '. $user->middlename.' '.$user->lastname}}</strong></h3>
                      <div style="color:#848688;">
                          <small>
                              <b>User ID: &nbsp</b>
                              <span>{{ $user->id }}</span>
                          </small>
                      </div> 
                      </br>
                      <div style="color:#848688;">
                          <small><b >Birth Date:&nbsp</b>{{date('F d,Y',strtotime($user->birthdate))}}</small>
                      </div>
                      <div style="color:#848688;"><small>
                      <b>Gender:</b><span>@if ($user->gender == 0) {{'Female'}} @else {{'Male'}}@endif</span><span></span><span> </span></small><span>&nbsp;</span><div style="color:#848688;"><small><b>Date Registered:&nbsp</b><span>{{date('F d,Y',strtotime($user->created_at))}} </span><span></span><span> </span></small></div>
                      </div>
                  </div>
                  <div  class="col-md-4" style="padding-top:4rem;"><a class="btn btn-primary" href="/account/edit_account/{{$user->id}}">Edit Account</a>&nbsp | &nbsp
                        <a class="deletePedModal btn btn-danger" href="#" data-toggle="modal" data-id="{{ $user->id }}" data-pedname="{{$user->firstname .' '.$user->lastname}}" data-target="#deletePedConfirm">Delete Account</a>
                  </div>
              <!--    <div  class="col-md-4" style="padding-top:4rem;" data-reactid=".0.0.0.2.0.1.1.1.0.$0.0.1.1"><a class="btn btn-danger" href="/delete_account/{{$user->id}}" data-reactid=".0.0.0.2.0.1.1.1.0.$0.0.1.1.0">Delete Account</a>
                  </div>
                  -->
                  </div>
                  </div>
                </li>
                </ul>       
                </div>
  @endif
  @endforeach

                    <!--CONFIRM DELETE MODAL-->
                        <div id="deletePedConfirm" data-name="" class="modal fade finishVacsConfirm" role="dialog">
                          <div class="modal-dialog">
                            <!-- Modal content-->
                              <div class="modal-content" >
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title" style="color: white">Confirm Deletion ?</h4>
                                </div>
                                <div class="modal-body">
                                   <b>Delete:</b> <span id="ped_name"></span><span>&nbsp?</span><br> 
                                </div>
                                <div class="modal-footer">
                                  <a href="#" class="btn btn-primary delete_ped_button">Yes</a>
                                  <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                </div>
                              </div>
                          </div>
                        </div>
                    <!--end modal-->

          <!-- END CARD TABLE -->                
       </div>
</div>     
@stop
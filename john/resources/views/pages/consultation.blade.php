@extends('layouts.masterLayout')




@section('title', 'Consultation | Patient Files')
@section('content')

<section class="content-header">
          <h1>
           Consultation
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">consultation</a></li>
          </ol>
      </section>
      <hr>
      @if(session('success'))
          <small style=" padding-top: 5px;color: white;background-color: red;font-style: italic;" class="box-title alert alert-{{session ('success.type')}} form-control" >
                                    {{session('success.text')}}
          </small>
             
      @endif
<div class="row">

  <div class="col-lg-12 col-sm-12">
   <div class="box box-primary">
                <div class="box-body box-profile">
                <div class="row">
                  <div class="col-lg-3">
                      <img  class="profile-user-img img-responsive img-circle" src="/img/prof_pic.png" alt="User profile picture" >
                  </div>
                      <div class="col-lg-9">
                     
                        </div>
                   </div> 
            </div>
            </div>
</div>

<div class="col-lg-12 col-sm-12">
    
 <div class="panel panel-default">
  <div class="panel-body">  
    <!-- Nav tabs -->
    <div class="nav-tabs-custom">
    <ul class="nav nav-tabs" role="tablist">
      <li class="active">
        <a data-toggle="tab" href="#vitals" role="tab">Vitals</a>
      </li>
      <li class="nav-item">
        <a data-toggle="tab" href="#vaccine" role="tab">Vaccine</a>
      </li>
      <li class="nav-item">
        <a data-toggle="tab" href="#prescription" role="tab">Prescription</a>
      </li>
      <li class="nav-item">
        <a data-toggle="tab" href="#diagnosis" role="tab">Diagnosis</a>
      </li>
       <li class="nav-item">
        <a data-toggle="tab" href="#labrequest" role="tab">Lab Request</a>
      </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane active" id="vitals" role="tabpanel">
            
          <form role="form" action="/saveVitals/{{$prof->id}}" method="POST">

          <div class="modal-body">
              {!! csrf_field() !!}

              <div class="row form-group">
                  <div class="col-lg-6">
                      <label for="number">Height:</label>
                      <input max="999" type="number" name="height" class="form-control" placeholder="Height in centimeters">
                  </div>
                  <div class="col-lg-6">
                      <label for="number">Weight:</label>
                      @if($errors->has('weight'))
                          <span class="error" style="color: red">{{ $errors->first('weight') }}</span>
                      @endif
                      <input max="999" type="number" name="weight" class="form-control" placeholder="Weight in kilograms">
                  </div>
              </div>

              <div class="row form-group">
                  <div class="col-lg-6">
                    <label for="number">Pulse Rate:</label>
                      @if($errors->has('pulse'))
                          <span class="error" style="color: red">{{ $errors->first('pulse') }}</span>
                      @endif
                    <input max="999" type="number" name="pulse" class="form-control" placeholder="Pulse Rate (Pulse per minute)">
                  </div>
                  <div class="col-lg-6">
                    <label for="number">Respiratory Rate:</label>
                      @if($errors->has('respiratory'))
                          <span class="error" style="color: red">{{ $errors->first('respiratory') }}</span>
                      @endif
                    <input max="999" type="number" name="respiratory" class="form-control" placeholder="Respiratory Rate">
                  </div>
              </div>
              
              <div class="row form-group">
                  <div class="col-lg-6">
                    <label for="number">Body Temperature:</label>
                      @if($errors->has('temp'))
                          <span class="error" style="color: red">{{ $errors->first('temp') }}</span>
                      @endif
                    <input max="999" type="number" name="temp" class="form-control" placeholder="Temperature in Celsius">
                  </div>
                  <div class="col-lg-6">
                    <label class="control-label" for="mens">Last Menstrual:</label>
                      @if($errors->has('mens'))
                          <span class="error" style="color: red">{{ $errors->first('mens') }}</span>
                      @endif
                      <div class="input-group">
                          <input name="mens" type="text" name="idTourDateDetails" id="idTourDateDetails" readonly="readonly" class="form-control" placeholder="Click here to pick date of last menstruation">
                          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      </div>
                  </div>
              </div>

              <div class="row form-group">
                  <div class="col-lg-6">
                    <label for="number">Blood Pressure (Systolic):</label>
                      @if($errors->has('sys'))
                          <span class="error" style="color: red">{{ $errors->first('sys') }}</span>
                      @endif
                    <input max="999" type="number" name="sys" class="form-control" placeholder="Systolic Value">
                  </div>
                  <div class="col-lg-6">
                    <label for="number">Blood Pressure (Diastolic):</label>
                      @if($errors->has('dia'))
                          <span class="error" style="color: red">{{ $errors->first('dia') }}</span>
                      @endif
                    <input max="999" type="number" name="dia" class="form-control" placeholder="Diastolic Value">
                  </div>
              </div>

              
              <div class="form-group">
                <label for="number">Doctor Notes:</label>
                      @if($errors->has('notes'))
                          <span class="error" style="color: red">{{ $errors->first('notes') }}</span>
                      @endif
                <textarea name="notes" class="form-control" rows="5" placeholder="Doctors vital notes"></textarea>
              </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" name="saveVitals" class="btn btn-primary">Save</button>
          </div>
         </form>
      </div>

      <div class="tab-pane" id="vaccine" role="tabpanel">2

      </div>

      <div class="tab-pane" id="prescription" role="tabpanel">


     <div class="row">

        <div class="col-lg-6">
      <form>
        <div class="row">
            <div class="col-lg-4">
              <label>Generic</label>
              <input class="form-control" type="text" placeholder="Generic" />
            </div>
             <div class="col-lg-4">
               <label>Brand</label>
               <input class="form-control" type="text" placeholder="Brand" />
            </div>
             <div class="col-lg-2">
               <label>Dosage</label>
               <input class="form-control" type="text" placeholder="Dosage" />
            </div>
             <div class="col-lg-2">
               <label>Quantity</label>
               <input class="form-control" type="text" placeholder="Quantity" />
            </div>
            <div class="col-lg-12">
               <label>Sig</label>
               <input class="form-control" type="text" placeholder="Sig" />
            </div>
             <div class="col-lg-4">
                <br>
               <input class="form-control btn btn-primary " value="Submit"   />
            </div>
        </div>

      </form>
      </div>

     <div class="col-lg-6">
      <div class="container-fluid">
        <h2>Prescriptions</h2>        
        <table class="table table-hover table-responsive">
          <thead>
            <tr>
              <th>Generic Name</th>
              <th>Brand</th>
              <th>Dosage</th>
              <th>Quantity</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Bear Brand</td>
              <td>Bonakid</td>
              <td>500mg</td>
              <td>10pcs</td>
            </tr>
          </tbody>
        </table>
      </div>  
      </div>
      <a href="/pdf"> PDF </a>
      <hr>


  </div>


      </div>

      <div class="tab-pane" id="diagnosis" role="tabpanel">4

      </div>

      <div class="tab-pane" id="labrequest" role="tabpanel">5

      </div>
    </div>

  </div>
  </div>
</div>
</div>


</div> <!-- END ROW --> 


@stop
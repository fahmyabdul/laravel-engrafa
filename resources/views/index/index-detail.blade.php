@extends('layouts.template')

@section('doc_title', isset($documentTitle) ? $documentTitle : 'Document Title')

@section('doc_desc', isset($documentDescription) ? $documentDescription : 'Document Description')

@section('global-mandatory-styles')
@stop

@section('page-level-plugin-styles')
@stop

@section('theme-global-styles')
@stop

@section('page-level-styles')
<style>
    .example-modal .modal {
      position: relative;
      top: auto;
      bottom: auto;
      right: auto;
      left: auto;
      display: block;
      z-index: 1;
    }

    .example-modal .modal {
      background: transparent !important;
    }
  </style>
@stop

@section('theme-layout-styles')
@stop

@section('custom-scripts')
@stop

@section('page-header') 
  <h1>Index Detail</h1>
@stop

@section('page-breadcrumb')
  <li><a class="active" href="{{route('index.detail')}}"><i class="fa fa-file"></i> Index Detail</a></li>
@stop


@section('body-inner-content')
<div class="row">
  <div class="col-md-9">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h4>
          <i class="fa fa-sticky-note"></i>
          <span class="pull-right">
            <a href="">
              <i class="fa fa-print fa-fw" data-toggle="tooltip" title="print"></i>
            </a>
            <a href="">
              <i class="fa fa-usb fa-fw" data-toggle="tooltip" title="related document"></i>
            </a>
            <a href="">
              <i class="fa fa-download fa-fw" data-toggle="tooltip" title="download"></i>
            </a>
            <a href="{{route('index')}}">
              <i class="fa fa-close fa-fw" data-toggle="tooltip" title="close"></i>
            </a>
          </span>
        </h4>
      </div>
      <div class="box-body" style="height: 600px;">
        <iframe src="{{url('http://localhost:8000/ViewerJS/index.html#../test.pdf')}}" width="100%" height="600px"></iframe>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        Box Footer Here
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h4>
          <i class="fa fa-usb fa-fw"></i>
          Some File
          <span class="pull-right">
            <i class="fa fa-angle-double-right"></i>
          </span>
        </h4>
      </div>
      <div class="box-body">
        
          <div class="box box-primary">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#file-group" href="#collapseOne">
                  Description
                </a>
              </h4>
            </div>
            <div id="collapseOne" class="collapse in">
              <div class="box-body">
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
              </div>
              <div class="box-footer">
                <span class="pull-left">
                  <a href="" data-toggle="tooltip" title="">
                    <i class="fa fa-eye fa-fw"></i>
                  </a>
                  <a href="" data-toggle="tooltip" title="edit">
                    <i class="fa fa-edit fa-fw"></i>
                  </a>
                  <a href="" data-toggle="tooltip" title="share">
                    <i class="fa fa-share fa-fw"></i>
                  </a>
                  <a href="" data-toggle="tooltip" title="delete">
                    <i class="fa fa-trash fa-fw"></i>
                  </a>
                </span>
              </div>
            </div>
          </div>

          <div class="box box-primary">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#file-group" href="#collapseTwo">
                  Contributor
                </a>
              </h4>
            </div>
            <div id="collapseTwo" class="collapse in">
              <div class="box-body">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">
                    <i class="fa fa-user fa-fw"></i>
                    Creator
                  </li>
                  <li class="list-group-item">
                    <i class="fa fa-user fa-fw"></i>
                    Editor
                  </li>
                  <li class="list-group-item">
                    <i class="fa fa-user fa-fw"></i>
                    Validator
                  </li>
                </ul>
              </div>
              <div class="box-footer">
              </div>
            </div>
          </div>

          <div class="box box-primary">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#file-group" href="#collapseThree">
                  Modified By
                </a>
              </h4>
            </div>
            <div id="collapseThree" class="collapse in">
              <div class="box-body">
                <table id="table1" class="table table-bordered table-hover" data-toggle="table" data-click-to-select="true">
                  <thead>
                  <tr>
                    <th>File</th>
                    <th>Modified By</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td>
                      <i class="fa fa-usb fa-fw"></i>
                      <span>Some Text</span>
                    </td>
                    <td>
                      <i class="fa fa-fw fa-user"></i>
                      <span>
                        Some Text
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <i class="fa fa-usb fa-fw"></i>
                      <span>Some Text</span>
                    </td>
                    <td>
                      <i class="fa fa-fw fa-user"></i>
                      <span>
                        Some Text
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <i class="fa fa-usb fa-fw"></i>
                      <span>Some Text</span>
                    </td>
                    <td>
                      <i class="fa fa-fw fa-user"></i>
                      <span>
                        Some Text
                      </span>
                    </td>
                  </tr>
                </table>
              </div>
              <div class="box-footer">
              </div>
            </div>

        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      </div>
    </div>
  </div>
</div>
@stop

@section('body-modals')
@stop

@section('core-plugins')
@stop

@section('page-level-plugins')
@stop

@section('theme-global-scripts')
@stop

@section('page-level-scripts')

@stop

@section('theme-layout-scripts')
@stop 

@section('page-specific-scripts')
@stop
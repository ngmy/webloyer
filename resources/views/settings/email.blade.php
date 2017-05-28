@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 col-md-offset-0">
            <div class="list-group">
                {!! link_to_route('settings.email', 'E-Mail Settings', [], ['class' => 'list-group-item selected']) !!}
            </div>
        </div>

        <div class="col-md-8 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">E-Mail Settings</div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {!! Form::open(['route' => ['settings.email'], 'method' => 'post', 'role' => 'form','class' => 'form-horizontal']) !!}
                        <div class="form-group required">
                            <label for="driver" class="col-md-4 control-label">Driver</label>
                            <div class="col-md-6">
                                <label>
                                    {!! Form::select('driver', ['smtp' => 'SMTP', 'mail' => 'PHP', 'sendmail' => 'Sendmail'], $mailSetting->driver()->value(), ['class' => 'form-control']) !!}
                                </label>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label for="from_address" class="col-md-4 control-label">From E-Mail Address</label>
                            <div class="col-md-6">
                                @if (isset($mailSetting->from()['address']))
                                    {!! Form::email('from_address', $mailSetting->from()['address'], ['class' => 'form-control', 'id' => 'from_address']) !!}
                                @else
                                    {!! Form::email('from_address', null, ['class' => 'form-control', 'id' => 'from_address']) !!}
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="from_name" class="col-md-4 control-label">From Name</label>
                            <div class="col-md-6">
                                @if (isset($mailSetting->from()['name']))
                                    {!! Form::text('from_name', $mailSetting->from()['name'], ['class' => 'form-control', 'id' => 'from_name']) !!}
                                @else
                                    {!! Form::text('from_name', null, ['class' => 'form-control', 'id' => 'from_name']) !!}
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="smtp_host" class="col-md-4 control-label">SMTP Host</label>
                            <div class="col-md-6">
                                {!! Form::text('smtp_host', $mailSetting->smtpHost(), ['class' => 'form-control', 'id' => 'smtp_host']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="smtp_port" class="col-md-4 control-label">SMTP Port</label>
                            <div class="col-md-6">
                                {!! Form::text('smtp_port', $mailSetting->smtpPort(), ['class' => 'form-control', 'id' => 'smtp_port']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="smtp_encryption" class="col-md-4 control-label">Encryption</label>
                            <div class="col-md-6">
                                <label>
                                    {!! Form::select('smtp_encryption', ['' => '', 'tls' => 'TLS', 'ssl' => 'SSL'], $mailSetting->smtpEncryption()->value(), ['class' => 'form-control']) !!}
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="smtp_username" class="col-md-4 control-label">SMTP Username</label>
                            <div class="col-md-6">
                                {!! Form::text('smtp_username', $mailSetting->smtpUsername(), ['class' => 'form-control', 'id' => 'smtp_username']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="smtp_password" class="col-md-4 control-label">SMTP Password</label>
                            <div class="col-md-6">
                                {!! Form::text('smtp_password', $mailSetting->smtpPassword(), ['class' => 'form-control', 'id' => 'smtp_password', 'autocomplete' => 'off']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sendmail_path" class="col-md-4 control-label">Sendmail Path</label>
                            <div class="col-md-6">
                                {!! Form::text('sendmail_path', $mailSetting->sendmailPath(), ['class' => 'form-control', 'id' => 'sendmail_path']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
